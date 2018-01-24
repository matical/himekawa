<?php

namespace himekawa\Console\Commands;

use yuki\Update;
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
    protected $apps;

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
        $this->apps = $availableAppsRepository;
        $this->maxAppsAllowed = config('himekawa.max_apps');
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        info('Checking for old apps to prune.');
        $allPackages = $this->update->allApkMetadata();

        foreach ($allPackages as $package) {
            $watchedApp = $this->apps->findPackage($package->packageName);

            $oldApps = $this->apps->getOldApps($this->maxAppsAllowed, $watchedApp);
            $this->apps->deleteFiles($oldApps, $package->packageName);

            $oldAppsById = $this->apps->getOldAppsById($this->maxAppsAllowed, $watchedApp);
            $appsDeleted = $this->apps->deleteEntries($oldAppsById->toArray());

            $this->info("Deleted $appsDeleted apps for {$package->packageName}");
            info("Deleted $appsDeleted apps for  {$package->packageName}");
        }
    }
}
