<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use yuki\Scrapers\UpdateManager;
use Illuminate\Support\Collection;
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
     * @var \yuki\Scrapers\UpdateManager
     */
    protected $updater;

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;

    /**
     * @var int
     */
    protected $maxAppsAllowed;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\UpdateManager               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(UpdateManager $update, AvailableAppsRepository $availableAppsRepository)
    {
        parent::__construct();

        $this->updater = $update;
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

        // TODO: Update to detect split apps
        /** @var \yuki\Scrapers\Store\StoreApp $storeApp */
        foreach ($this->fetchMetadata() as $storeApp) {
            $watched = $this->availableApps->findWithStoreApp($storeApp);
            $oldAvailableApps = $this->availableApps->getOldApps($this->maxAppsAllowed, $watched);

            if ($oldAvailableApps->isEmpty()) {
                continue;
            }

            $packageName = $storeApp->getPackageName();

            $numberOfDeletedApps = $this->availableApps->deleteFiles($oldAvailableApps, $packageName);
            $this->log("Deleted $numberOfDeletedApps app(s) for {$packageName}");
        }
    }

    protected function fetchMetadata()
    {
        $single = $this->getSingles()
                       ->map(fn ($package) => $this->updater->singles($package));

        $split = $this->getSplits()
                      ->map(fn ($package) => $this->updater->splits($package));

        return [$single, $split];
    }

    // TODO: Dedup bottom (duped in CheckForAppUpdates)
    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getSingles(): Collection
    {
        return WatchedApp::single()->pluck('package_name');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getSplits(): Collection
    {
        return WatchedApp::split()->pluck('package_name');
    }

    protected function log($message)
    {
        $this->info($message);
        logger()->info($message);
    }
}
