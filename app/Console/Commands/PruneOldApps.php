<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use yuki\Scrapers\UpdateManager;
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
     * @var \yuki\Scrapers\UpdateManager
     */
    protected $update;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\UpdateManager               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(UpdateManager $update, AvailableAppsRepository $availableAppsRepository)
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

        /** @var \yuki\Scrapers\Store\StoreApp $storeApp */
        foreach ($this->update->allApkMetadata() as $storeApp) {
            $packageName = $storeApp->getPackageName();

            $watched = $this->availableApps->findPackage($packageName);
            $oldAvailableApps = $this->availableApps->getOldApps($this->maxAppsAllowed, $watched);

            if ($oldAvailableApps->isEmpty()) {
                continue;
            }

            $numberOfDeletedApps = $this->availableApps->deleteFiles($oldAvailableApps, $packageName);

            $this->log("Deleted $numberOfDeletedApps app(s) for {$packageName}");
        }
    }

    public function log($message)
    {
        $this->info($message);
        logger()->info($message);
    }
}
