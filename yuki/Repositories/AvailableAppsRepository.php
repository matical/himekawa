<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Badging\Badging;
use himekawa\AvailableApp;
use Illuminate\Support\Facades\Storage;

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
        return $this->taggedCached('apps', 'available-apps:all-watched', fn () => AvailableApp::with('watchedBy')
                                                                                         ->get());
    }

    /**
     * @param string $packageName
     * @return \himekawa\AvailableApp
     */
    public function create($packageName)
    {
        $package = WatchedApp::where('package_name', $packageName)
                             ->first();

        $metadata = $this->metainfo->getPackageInfo($packageName);

        $badging = $this->getBadging($packageName, $metadata);

        $newApp = $package->availableApps()->create([
            'version_code' => $metadata->versionCode,
            'version_name' => $badging['versionName'],
            'size'         => $metadata->size,
            'hash'         => $metadata->sha1,
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
    public function deleteEntries($id)
    {
        return AvailableApp::destroy($id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $watchedApps
     * @param $package
     */
    public function deleteFiles($watchedApps, $package)
    {
        $filesToDelete = $watchedApps->map(fn ($item) => buildApkFilename($package, $item->version_code));

        foreach ($filesToDelete as $file) {
            Storage::delete($package . DIRECTORY_SEPARATOR . $file);
        }
    }

    /**
     * @param $packageName
     * @param $metadata
     * @return array
     */
    protected function getBadging($packageName, $metadata): array
    {
        $badging = $this->badging->package(
            $packageName,
            buildApkFilename(
                $packageName,
                $metadata->versionCode
            )
        )->getPackage();

        return $badging;
    }
}
