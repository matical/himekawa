<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Badging\Badging;
use himekawa\AvailableApp;
use yuki\Scrapers\Metainfo;
use Illuminate\Support\Facades\Storage;

class AvailableAppsRepository
{
    use CachesAccess;

    /**
     * @var \yuki\Badging\Badging
     */
    protected $badging;

    /**
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metainfo;

    /**
     * @var \yuki\Scrapers\Versioning
     */
    protected $versioning;

    /**
     * AvailableAppsRepository constructor.
     *
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @param \yuki\Badging\Badging   $badging
     */
    public function __construct(Metainfo $metainfo, Badging $badging)
    {
        $this->metainfo = $metainfo;
        $this->badging = $badging;
    }

    /**
     * @param string $package
     * @return \himekawa\WatchedApp|null
     */
    public function findPackage($package)
    {
        return WatchedApp::where('package_name', $package)
                         ->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function cachedAllWithWatched()
    {
        return $this->taggedCached('apps', 'available-apps:all-watched', function () {
            return AvailableApp::with('watchedBy')
                               ->get();
        });
    }

    /**
     * @param string $packageName
     * @return \himekawa\AvailableApp
     */
    public function create($packageName)
    {
        $package = WatchedApp::where('package_name', $packageName)
                             ->first();

        $metadata = metacache($packageName);

        $badging = $this->badging->package(
            $packageName,
            buildApkFilename(
                $packageName,
                $metadata->versionCode
            )
        )->getPackage();

        $rawBadging = $this->badging->getRawBadging();

        return $package->availableApps()->create([
            'version_code' => $metadata->versionCode,
            'version_name' => $badging['versionName'],
            'size'         => $metadata->size,
            'hash'         => $metadata->sha1,
            'raw_badging'  => $rawBadging,
        ]);
    }

    /**
     * @param string               $toKeep
     * @param \himekawa\WatchedApp $package
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection
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
     * @param                      $toKeep
     * @param \himekawa\WatchedApp $package
     * @return \Illuminate\Support\Collection
     */
    public function getOldAppsById($toKeep, WatchedApp $package)
    {
        return $package->availableApps()
                       ->skip($toKeep)
                       ->take(PHP_INT_MAX)
                       ->pluck('id');
    }

    /**
     * @param array $id
     * @return int
     */
    public function deleteEntries($id)
    {
        return AvailableApp::destroy($id);
    }

    /**
     * @param $watchedApps
     * @param $package
     */
    public function deleteFiles($watchedApps, $package)
    {
        $filesToDelete = $watchedApps->map(function ($item, $key) use ($package) {
            return buildApkFilename($package, $item->version_code);
        });

        foreach ($filesToDelete as $file) {
            Storage::delete($package . DIRECTORY_SEPARATOR . $file);
        }
    }
}
