<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use yuki\Scrapers\Metainfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CheckForAppUpdates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:checkupdates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @return mixed
     */
    public function handle(Metainfo $metainfo)
    {
        $metadata = $this->fetchAppMetadata($metainfo);
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @param $metainfo
     * @return array|null An array containing all app metadata, indexed by the package name
     */
    protected function fetchAppMetadata($metainfo)
    {
        $watchedPackages = WatchedApp::pluck('package_name');

        foreach ($watchedPackages as $package) {
            $this->info('Checking ' . $package . '...');
            $fetchMetadata = $metainfo->make();

            $result[$package] = metaCache($package, $fetchMetadata);
        }

        return $result;
    }
}
