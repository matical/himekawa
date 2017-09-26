<?php

namespace yuki\Scrapers;

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
     * Check if that particular package requires any updates.
     *
     * @param $packageName
     * @param $latestVersionCode
     * @return bool
     */
    public function areUpdatesAvailable($packageName, $latestVersionCode)
    {
        $package = $this->availableApps->findPackage($packageName);
        $latestApp = $package->latestApp();

        // If there are no "available" apps for that particular package, it'll be marked as
        // available for update.
        if (is_null($latestApp) || $latestApp->version_code <= $latestVersionCode) {
            return true;
        }

        return false;
    }
}
