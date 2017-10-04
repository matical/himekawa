<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use himekawa\WatchedApp;
use Illuminate\Console\Command;
use yuki\Repositories\AvailableAppsRepository;

class PruneOldApps extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:prune-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;


    /**
     * @var int
     */
    protected $maxAppsAllowed;

    /**
     * @var int
     */
    protected $appsDeleted;

    /**
     * @var \yuki\Update
     */
    protected $update;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Update                               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(Update $update, AvailableAppsRepository $availableAppsRepository)
    {
        parent::__construct();

        $this->update = $update;
        $this->availableApps = $availableAppsRepository;
        $this->maxAppsAllowed = config('himekawa.max_apps');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allPackages = $this->update->allApkMetadata();

        foreach ($allPackages as $package) {
            $watchedApp = $this->availableApps->findPackage($package->packageName);

            $oldApps = $this->availableApps->getOldApps($this->maxAppsAllowed, $watchedApp);
            $this->availableApps->deleteFiles($oldApps, $package->packageName);

            $oldAppsById = $this->availableApps->getOldAppsById($this->maxAppsAllowed, $watchedApp);
            $appsDeleted = $this->availableApps->deleteEntries($oldAppsById->toArray());

            $this->info('Deleted ' . $appsDeleted . ' apps for ' . $package->packageName);
        }
    }
}
