<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ksmz\json\Json;

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

    /**
     * @var string
     */
    protected $apkListLocation;

    /** @var string */
    protected $rawContents;

    /** @var array */
    protected $newAppsAdded = [];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->apkListLocation = config('himekawa.paths.apps_to_import');
        $this->rawContents = file_get_contents($this->apkListLocation);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $appsToWatch = $this->decodeAndCollectApps($this->rawContents)
                            ->pluck('package_name');
        $missingApps = $appsToWatch->diff($this->getCurrentlyWatchedApps());

        if ($missingApps->isEmpty()) {
            $this->info('No new apps to import.');

            return;
        }

        $this->line(' New Apps Detected');
        $this->info(' -----------------');

        $missingApps->each(function ($missing) {
            $this->line(' ' . $missing);
        });

        if (! $this->confirm('Do you wish to add these new packages to the watchlist?')) {
            return;
        }

        $this->info('Adding new apps...');
        $this->output->newLine();

        foreach ($missingApps as $app) {
            $this->newAppsAdded[] = $this->watchApp($app);
        }

        $numberOfNewApps = count($this->newAppsAdded);
        $this->line("Added <info>$numberOfNewApps</info> new " . Str::plural('app', $numberOfNewApps) . ' to the watch list.');
    }

    /**
     * @param string $rawContent
     * @return \Illuminate\Support\Collection
     */
    protected function decodeAndCollectApps(string $rawContent): Collection
    {
        return collect(Json::decode($rawContent)->apps);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getCurrentlyWatchedApps(): Collection
    {
        return WatchedApp::pluck('package_name');
    }

    /**
     * @param $packageName
     * @return \himekawa\WatchedApp
     */
    protected function watchApp($packageName): WatchedApp
    {
        $package = $this->decodeAndCollectApps($this->rawContents)
                        ->firstWhere('package_name', $packageName);

        return tap(new WatchedApp(), function (WatchedApp $watchedApp) use ($package) {
            $watchedApp->name = $package->name;
            $watchedApp->slug = $package->slug;
            $watchedApp->original_title = $package->original_title;
            $watchedApp->package_name = $package->package_name;

            $watchedApp->save();
        });
    }
}
