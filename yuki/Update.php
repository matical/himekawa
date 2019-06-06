<?php

namespace yuki;

use himekawa\WatchedApp;
use yuki\Scrapers\Versioning;
use Illuminate\Support\Collection;
use yuki\Repositories\MetainfoRepository;

class Update
{
    /**
     * @var \yuki\Repositories\MetainfoRepository
     */
    protected $metainfo;

    /**
     * @var \yuki\Scrapers\Versioning
     */
    protected $versioning;

    /** @var int Delay in seconds */
    protected $delay = 2;

    /**
     * Update constructor.
     *
     * @param \yuki\Repositories\MetainfoRepository $metainfo
     * @param \yuki\Scrapers\Versioning             $versioning
     */
    public function __construct(MetainfoRepository $metainfo, Versioning $versioning)
    {
        $this->metainfo = $metainfo;
        $this->versioning = $versioning;
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @return array|null An array containing all app metadata, indexed by the package name
     */
    public function allApkMetadata(): array
    {
        $result = [];

        foreach ($this->pluckPackages() as $package) {
            $result[$package] = $this->metainfo->getPackageInfo($package);
            sleep($this->delay);
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

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function pluckPackages(): Collection
    {
        return WatchedApp::pluck('package_name');
    }
}
