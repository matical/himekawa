<?php

namespace yuki;

use himekawa\WatchedApp;
use yuki\Scrapers\Metainfo;
use yuki\Scrapers\Versioning;

class Update
{
    /**
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metainfo;

    /**
     * @var \yuki\Scrapers\Versioning
     */
    protected $versioning;

    /**
     * Update constructor.
     *
     * @param \yuki\Scrapers\Metainfo   $metainfo
     * @param \yuki\Scrapers\Versioning $versioning
     */
    public function __construct(Metainfo $metainfo, Versioning $versioning)
    {
        $this->metainfo = $metainfo;
        $this->versioning = $versioning;
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @return array|null An array containing all app metadata, indexed by the package name
     */
    public function allApkMetadata(): ?array
    {
        $watchedPackages = WatchedApp::pluck('package_name');

        foreach ($watchedPackages as $package) {
            $result[$package] = metacache($package);
        }

        return $result;
    }

    /**
     * Check if there are any updates available.
     *
     * @param array $appMetadata
     * @return array|null An array of apps that require updates
     */
    public function checkForUpdates($appMetadata): ?array
    {
        $appsRequiringUpdates = [];

        foreach ($appMetadata as $app) {
            // Queue up the apps that have updates pending
            if ($this->versioning->areUpdatesAvailableFor($app->packageName, $app->versionCode)) {
                $appsRequiringUpdates[] = $app;
            }
        }

        return $appsRequiringUpdates;
    }
}
