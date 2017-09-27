<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Parsers\Badging;
use yuki\Scrapers\Metainfo;

class AvailableAppsRepository
{
    protected $badging;
    protected $metainfo;

    public function __construct(Metainfo $metainfo, Badging $badging)
    {
        $this->metainfo = $metainfo;
        $this->badging = $badging;
    }

    /**
     * @param $package
     * @return \himekawa\WatchedApp|null
     */
    public function findPackage($package)
    {
        return WatchedApp::where('package_name', $package)
                         ->first();
    }

    /**
     * @param   $packageName
     */
    public function create($packageName)
    {
        $package = WatchedApp::where('package_name', $packageName)
                             ->first();

        $metadata = metaCache($packageName, $this->metainfo);

        $badging = $this->badging->package(
            $packageName,
            buildApkFilename(
                $packageName,
                $metadata->versionCode
            )
        )->getPackage();

        $rawBadging = $this->badging->getRawBadging();

        $package->availableApps()->create([
            'version_code' => $metadata->versionCode,
            'version_name' => $badging['versionName'],
            'size'         => $metadata->size,
            'hash'         => $metadata->sha1,
            'raw_badging'  => $rawBadging,
        ]);
    }
}
