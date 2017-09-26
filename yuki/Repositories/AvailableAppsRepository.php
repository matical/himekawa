<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use yuki\Parsers\Badging;
use yuki\Scrapers\Metainfo;

class AvailableAppsRepository
{
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
     * @param                         $packageName
     * @param \yuki\Scrapers\Metainfo $metainfo
     */
    public function create($packageName, Metainfo $metainfo)
    {
        $package = WatchedApp::where('package_name', $packageName)
                             ->first();

        $metadata = metaCache($packageName, $metainfo);

        $badging = (new Badging())->package(
            $packageName,
            buildApkFilename(
                $packageName,
                $metadata->versionCode
            )
        )->getPackage();

        $package->availableApps()->create([
            'version_code' => $metadata->versionCode,
            'version_name' => $badging['versionName'],
            'size'         => $metadata->size,
            'hash'         => $metadata->sha1,
        ]);
    }
}
