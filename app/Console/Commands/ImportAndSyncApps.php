<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use Illuminate\Support\Str;
use yuki\Import\ImportManager;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImportAndSyncApps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync APK watch list if required.';

    /** @var string */
    protected $apkListLocation;

    /** @var array */
    protected $newAppsAdded = [];

    protected ImportManager $import;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Import\ImportManager $import
     */
    public function __construct(ImportManager $import)
    {
        parent::__construct();
        $this->apkListLocation = config('himekawa.paths.apps_to_import');
        $this->import = $import;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Throwable
     */
    public function handle()
    {
        $this->import->parse();

        $missingSingleApps = $this->import->onlySingle()
                                          ->pluck('package')
                                          ->diff($this->getCurrentlyWatchedApps());

        $missingSplitApps = $this->import->onlySplits()
                                         ->pluck('package')
                                         ->diff($this->getCurrentlyWatchedApps(true));

        if ($missingSingleApps->isEmpty() || $missingSplitApps->isEmpty()) {
            $this->info('No new apps to import.');

            return 0;
        }

        $this->line(' New Apps Detected');
        $this->info(' -----------------');

        $this->output->newLine();
        $this->info('[Single]');
        $missingSingleApps->each(fn ($missing) => $this->line(" {$missing}"));

        $this->output->newLine();
        $this->info('[Split]');
        $missingSplitApps->each(fn ($missing) => $this->line(" {$missing}"));

        if (! $this->confirm('Do you wish to add these new packages to the watchlist?')) {
            return 0;
        }

        $this->info('Adding new apps...');
        $this->output->newLine();

        try {
            DB::transaction(function () use ($missingSplitApps, $missingSingleApps) {
                foreach ($missingSingleApps as $app) {
                    $this->newAppsAdded[] = $this->watchApp($app);
                }

                foreach ($missingSplitApps as $app) {
                    $this->newAppsAdded[] = $this->watchApp($app, true);
                }
            });
        } catch (\Exception $exception) {
            $this->warn("Something went wrong, no changes were made.\n\n");
            $this->warn($exception->getMessage());

            return 1;
        }

        $numberOfNewApps = count($this->newAppsAdded);
        $this->line("Added <info>$numberOfNewApps</info> new " . Str::plural('app', $numberOfNewApps) . ' to the watch list.');
    }

    /**
     * @param bool $split
     * @return \Illuminate\Support\Collection
     */
    protected function getCurrentlyWatchedApps(bool $split = false): Collection
    {
        return WatchedApp::where('use_split', $split)
                         ->pluck('package_name');
    }

    /**
     * @param string $packageName
     * @param bool   $split
     * @return \himekawa\WatchedApp
     */
    protected function watchApp($packageName, $split = false): WatchedApp
    {
        $package = $this->import->package($packageName, $split);

        $watching = new WatchedApp();

        $watching->name = $package['name'];
        $watching->slug = $package['slug'];
        $watching->original_title = $package['original'];
        $watching->package_name = $package['package'];
        $watching->use_split = $split;

        $watching->save();

        return $watching;
    }
}
