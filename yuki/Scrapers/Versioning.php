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
     * Check if a particular package requires any updates.
     *
     * @param $packageName Package identifier
     * @param $latestPlaystoreVersionCode Version code of the latest app available on the Play Store
     * @return bool Whether updates are available
     */
    public function areUpdatesAvailableFor($packageName, $latestPlaystoreVersionCode)
    {
        $package = $this->availableApps->findPackage($packageName);

        // If there are no "available" apps for that particular package, it'll be marked as
        // available for update.
        if (is_null($package->latestApp())) {
            return true;
        }

        $latestLocalVersionCode = $package->latestApp()->version_code;

        if ($latestPlaystoreVersionCode > $latestLocalVersionCode) {
            return true;
        }

        return false;
    }
}
