<?php

namespace yuki\Scrapers;

use himekawa\WatchedApp;
use yuki\Scrapers\Store\StoreApp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use yuki\Repositories\MetainfoRepository;
use Symfony\Component\Process\Exception\ProcessFailedException;

class UpdateManager
{
    /**
     * @var \yuki\Repositories\MetainfoRepository
     */
    protected $metainfo;

    /**
     * Delay in seconds.
     *
     * @var int
     */
    protected $delay;

    /**
     * @param \yuki\Repositories\MetainfoRepository $metainfo
     */
    public function __construct(MetainfoRepository $metainfo)
    {
        $this->metainfo = $metainfo;
        $this->delay = config('googleplay.delay');
    }

    /**
     * Fetch metadata based on the watch list.
     *
     * @param bool $verbose Control exception reporting
     * @return \Illuminate\Support\Collection|null A collection containing all app metadata, keyed by the package name
     */
    public function allSingleMetadata(bool $verbose = false): Collection
    {
        $result = collect();

        foreach ($this->watchedPackages() as $package) {
            try {
                $fetched = $this->metainfo->getPackageInfo($package);
            } catch (ProcessFailedException $exception) {
                Log::warning("Failed to fetch metainfo for $package");
                if ($verbose) {
                    report($exception);
                }
                continue;
            }

            $result[$package] = StoreApp::createFromPayload($fetched);
            sleep($this->delay);
        }

        return $result;
    }

    /**
     * Check if there are any updates available.
     *
     * @param \Illuminate\Support\Collection $appMetadata
     * @return \Illuminate\Support\Collection A collection of apps that require updates
     */
    public function checkForUpdates(Collection $appMetadata): Collection
    {
        return $appMetadata->filter(fn (StoreApp $app) => $app->canBeUpdated());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function watchedPackages(): Collection
    {
        return WatchedApp::whereNull('disabled')
                         ->pluck('package_name');
    }
}
