<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Badging\Badging;
use himekawa\AvailableApp;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Collection;

class AvailableAppsRepository
{
    use CachesAccess;

    /** @var \yuki\Badging\Badging */
    protected $badging;

    /** @var \yuki\Scrapers\Versioning */
    protected $versioning;

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
     * @return \himekawa\AvailableApp
     */
    public function create($storeApp)
    {
        $package = WatchedApp::where('package_name', $storeApp->getPackageName())
                             ->first();

        $badging = $this->badging->parsed();

        $newApp = $package->availableApps()->create([
            'version_code' => $storeApp->getVersionCode(),
            'version_name' => $badging['versionName'],
            'size'         => $storeApp->expectedSizeInBytes(),
            'hash'         => $storeApp->expectedHash(),
        ]);

        return tap($newApp, function (AvailableApp $newApp) {
            $newApp->badging()->create([
                'raw_badging' => $this->badging->getRawBadging(),
            ]);
        });
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
     * @param \Illuminate\Support\Collection $id
     * @return int
     */
    public function deleteEntries($id): int
    {
        return AvailableApp::destroy($id);
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
        $filesToDelete = $availableApps->map(fn (AvailableApp $item) => buildApkFilename($package, $item->version_code));
        $filesToDelete->each(fn ($file)                              => Storage::delete("$package/$file"));

        // Delete DB entries
        return $this->deleteEntries($availableApps->pluck('id'));
    }
}
