<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Badging\Badging;
use himekawa\AvailableApp;
use yuki\Scrapers\Store\StoreApp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class AvailableAppsRepository
{
    use CachesAccess;

    /** @var \yuki\Badging\Badging */
    protected $badging;

    /** @var \yuki\Repositories\MetainfoRepository */
    protected $metainfo;

    /**
     * AvailableAppsRepository constructor.
     *
     * @param \yuki\Badging\Badging                 $badging
     * @param \yuki\Repositories\MetainfoRepository $metainfo
     */
    public function __construct(Badging $badging, MetainfoRepository $metainfo)
    {
        $this->badging = $badging;
        $this->metainfo = $metainfo;
    }

    /**
     * @param string $package
     * @return \himekawa\WatchedApp|null|mixed
     */
    public function findPackage($package)
    {
        return WatchedApp::where('package_name', $package)
                         ->first();
    }

    /**
     * @param \yuki\Scrapers\Store\StoreApp $storeApp
     * @return \himekawa\WatchedApp|mixed|null
     */
    public function findWithStoreApp(StoreApp $storeApp)
    {
        return $this->findPackage($storeApp->getPackageName());
    }

    /**
     * @param \yuki\Scrapers\Store\StoreApp $storeApp
     * @return \himekawa\AvailableApp
     */
    public function create($storeApp)
    {
        $package = WatchedApp::where('package_name', $storeApp->getPackageName())
                             ->first();

        $badging = $this->badging->parsed();

        /** @var AvailableApp $newApp */
        $newApp = $package->availableApps()->create([
            'version_code' => $storeApp->getVersionCode(),
            'version_name' => $badging['versionName'],
            'size'         => $storeApp->expectedSizeInBytes(),
            'hash'         => $storeApp->expectedHash(),
        ]);

        $newApp->badging()->create(['raw_badging' => $this->badging->getRawBadging()]);

        return $newApp;
    }

    /**
     * @param int                  $toKeep
     * @param \himekawa\WatchedApp $package
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOldApps($toKeep, WatchedApp $package)
    {
        // Thanks mysql
        return $package->availableApps()
                       ->skip($toKeep)
                       ->take(PHP_INT_MAX)
                       ->get();
    }

    /**
     * Deletes both physical files and DB entries.
     *
     * @param \Illuminate\Database\Eloquent\Collection $availableApps Collection of available apps
     * @param string                                   $package       Name of the package
     * @return int Number of files deleted
     */
    public function deleteFiles(Collection $availableApps, $package): int
    {
        // Delete physical files
        $availableApps->map(fn (AvailableApp $item) => "{$package}/{$item->filename}")
                      ->each(fn (string $file) => Storage::delete($file)); // Autofails on deletion failure

        // Delete DB entries
        return $this->deleteEntries($availableApps->pluck('id'));
    }

    /**
     * @param \Illuminate\Support\Collection $id
     * @return int
     */
    public function deleteEntries($id): int
    {
        return AvailableApp::destroy($id);
    }
}
