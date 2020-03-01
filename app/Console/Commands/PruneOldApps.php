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
    protected $description = 'Cleanup outdated APKs.';

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
        $this->log('Checking for old apps to prune.');

        foreach ($this->update->allApkMetadata() as $package) {
            $packageName = $package->packageName;

            $watched = $this->availableApps->findPackage($packageName);
            $oldApps = $this->availableApps->getOldApps($this->maxAppsAllowed, $watched);

            if ($oldApps->isEmpty()) {
                continue;
            }

            // Delete physical files
            $this->availableApps->deleteFiles($oldApps, $packageName);

            // Delete DB entries
            $numberOfDeletedApps = $this->availableApps->deleteEntries($oldApps->pluck('id'));

            $this->log("Deleted $numberOfDeletedApps app(s) for {$packageName}");
        }
    }

    public function log($message)
    {
        $this->info($message);
        logger()->info($message);
    }
}
