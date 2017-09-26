<?php

use yuki\Scrapers\Metainfo;

if (! function_exists('metaCache')) {
    /**
     * Retrieve a cached copy of the metadata whenever possible.
     *
     * @param                         $package
     * @param \yuki\Scrapers\Metainfo $fetchMetadata
     * @return mixed
     */
    function metaCache($package, Metainfo $fetchMetadata)
    {
        return Cache::remember('apk-metainfo:' . $package, 15, function () use ($package, $fetchMetadata) {
            return $fetchMetadata->build($package)
                                 ->run()
                                 ->output();
        });
    }
}

if (! function_exists('apkDirectory')) {
    /**
     * @param null $packageName
     * @return \Illuminate\Config\Repository|mixed|string
     */
    function apkDirectory($packageName = null)
    {
        if (is_null($packageName)) {
            return config('googleplay.apk_path');
        }

        return config('googleplay.apk_path') . DIRECTORY_SEPARATOR . $packageName;
    }
}

if (! function_exists('buildApkFilename')) {
    /**
     * @param $packageName
     * @param $versionCode
     * @return string
     */
    function buildApkFilename($packageName, $versionCode)
    {
        return sprintf('%s.%s.apk', $packageName, $versionCode);
    }
}
