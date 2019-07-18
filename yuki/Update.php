<?php

namespace yuki;

use himekawa\WatchedApp;
use yuki\Scrapers\Versioning;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use yuki\Repositories\MetainfoRepository;
use Symfony\Component\Process\Exception\ProcessFailedException;

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

    /**
     * Delay in seconds.
     *
     * @var int
     */
    protected $delay = 15;

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
            try {
                $fetched = $this->metainfo->getPackageInfo($package);
            } catch (ProcessFailedException $exception) {
                Log::warning("Failed to fetch metainfo for $package");
                report($exception);
                continue;
            }

            $result[$package] = $fetched;
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
