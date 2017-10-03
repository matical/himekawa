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
        $this->appMetadata = $this->fetchAppMetadata($metainfo);

        foreach ($this->appMetadata as $app) {
            // Queue up the apps that have updates pending
            if ($versioning->areUpdatesAvailable($app->packageName, $app->versionCode)) {
                $this->appsRequiringUpdates[] = $app;
            }
        }

        // If there are no apps that require updates, exit
        if (empty($this->appsRequiringUpdates)) {
            $this->info('No apps available for update.');

            return;
        }

        foreach ($this->appsRequiringUpdates as $app) {
            $this->info('Downloading ' . $app->packageName);

            $download->build($app->packageName, $app->versionCode, $app->sha1)
                     ->run()
                     ->store();
        }
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @return array|null An array containing all app metadata, indexed by the package name
     */
    protected function fetchAppMetadata(Metainfo $metainfo)
    {
        $watchedPackages = WatchedApp::pluck('package_name');

        foreach ($watchedPackages as $package) {
            $this->line("Checking $package");
            $fetchMetadata = $metainfo->make();

            $result[$package] = metaCache($package, $fetchMetadata);
        }

        return $result;
    }
}
