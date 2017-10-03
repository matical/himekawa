<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use yuki\Scrapers\Versioning;
use Illuminate\Console\Command;

class CheckForAppUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates.';

    /**
     * @var array An array containing all app metadata, indexed by the package name
     */
    protected $appMetadata = [];

    /**
     * Array of apps with available updates.
     *
     * @var array
     */
    protected $appsRequiringUpdates = [];

    /**
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metainfo;

    /**
     * @var \yuki\Scrapers\Versioning
     */
    protected $versioning;

    /**
     * @var \yuki\Scrapers\Download
     */
    protected $download;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\Metainfo   $metainfo
     * @param \yuki\Scrapers\Versioning $versioning
     * @param \yuki\Scrapers\Download   $download
     */
    public function __construct(Metainfo $metainfo, Versioning $versioning, Download $download)
    {
        parent::__construct();

        $this->metainfo = $metainfo;
        $this->versioning = $versioning;
        $this->download = $download;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->appMetadata = $this->fetchAppMetadata();
        $this->appsRequiringUpdates = $this->areUpdatesAvailable($this->appMetadata);

        // If there are no apps that require updates, exit
        if (empty($this->appsRequiringUpdates)) {
            $this->info('No apps are available for update.');

            return 0;
        }

        $bar = $this->output->createProgressBar(count($this->appsRequiringUpdates));

        foreach ($this->appsRequiringUpdates as $app) {
            $bar->advance();
            $bar->setMessage('Downloading ' . $app->packageName);
            $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                           ->run()
                           ->store();
        }

        $bar->finish();
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @return array|null An array containing all app metadata, indexed by the package name
     */
    protected function fetchAppMetadata()
    {
        $watchedPackages = WatchedApp::pluck('package_name');

        foreach ($watchedPackages as $package) {
            $this->line("Checking $package");
            $fetchMetadata = $this->metainfo->make();

            $result[$package] = metaCache($package, $fetchMetadata);
        }

        return $result;
    }

    /**
     * Check if there are any updates available.
     *
     * @param $appMetadata
     * @return array|null An array of apps that require updates
     */
    protected function areUpdatesAvailable($appMetadata): ?array
    {
        foreach ($appMetadata as $app) {
            // Queue up the apps that have updates pending
            if ($this->versioning->areUpdatesAvailable($app->packageName, $app->versionCode)) {
                $appsRequiringUpdates[] = $app;
            }
        }

        return $appsRequiringUpdates;
    }
}
