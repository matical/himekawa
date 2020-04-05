<?php

namespace yuki\Scrapers;

use himekawa\AvailableApp;
use yuki\Repositories\AvailableAppsRepository;

class Versioning
{
    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;

    /**
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(AvailableAppsRepository $availableAppsRepository)
    {
        $this->availableApps = $availableAppsRepository;
    }

    /**
     * Check if a local package requires any updates.
     *
     * @param string $packageName                Package identifier
     * @param int    $latestPlaystoreVersionCode Version code of the latest app available on the Play Store
     * @return bool Whether updates are available
     */
    public function areUpdatesAvailableFor($packageName, $latestPlaystoreVersionCode): bool
    {
        $latestLocalVersion = $this->getLatestLocalApp($packageName);

        // If there are no "available" apps for that particular package, it'll be marked as
        // available for update.
        if ($latestLocalVersion === null) {
            return true;
        }

        if ($latestPlaystoreVersionCode > $latestLocalVersion->version_code) {
            return true;
        }

        return false;
    }

    /**
     * @param string $package
     * @return \himekawa\AvailableApp|null
     */
    protected function getLatestLocalApp(string $package): ?AvailableApp
    {
        return $this->availableApps->findPackage($package)
                                   ->latestApp();
    }
}
