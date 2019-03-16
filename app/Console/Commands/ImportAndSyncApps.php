<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use Illuminate\Console\Command;

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
    protected $description = 'Command description';

    /**
     * @var string
     */
    protected $apkListLocation;

    /** @var string */
    protected $rawContents;

    /** @var array */
    protected $newAppsAdded;

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
        $this->newAppsAdded = [];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apps = $this->decodeAndCollectApps($this->rawContents);
        $plucked = $apps->pluck('package_name');
        $missingApps = $plucked->diff($this->getCurrentlyWatchedApps());

        if ($missingApps->isEmpty()) {
            $this->info('No new apps to import.');

            return;
        }

        $this->line(' New Apps Detected');
        $this->info(' -----------------');
        foreach ($missingApps as $app) {
            $this->line(' ' . $app);
        }

        if (! $this->confirm('Do you wish to add these new packages to the watchlist?')) {
            return;
        }

        $this->info('Adding new apps...');
        $this->output->newLine();

        foreach ($missingApps as $app) {
            $this->newAppsAdded[] = $this->appToAdd($app);
        }

        $numberOfNewApps = count($this->newAppsAdded);
        $this->line("Added <info>$numberOfNewApps</info> new app(s).");
    }

    protected function decodeAndCollectApps($rawContent)
    {
        return collect(json_decode($rawContent)->apps);
    }

    protected function getCurrentlyWatchedApps()
    {
        return WatchedApp::pluck('package_name');
    }

    protected function appToAdd($appsToAdd)
    {
        $package = $this->decodeAndCollectApps($this->rawContents)
                        ->firstWhere('package_name', $appsToAdd);

        return tap(new WatchedApp(), function (WatchedApp $watchedApp) use ($package) {
            $watchedApp->name = $package->name;
            $watchedApp->slug = $package->slug;
            $watchedApp->original_title = $package->original_title;
            $watchedApp->package_name = $package->package_name;

            $watchedApp->save();
        });
    }
}
