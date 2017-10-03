<?php

use yuki\Scrapers\Metainfo;

if (! function_exists('metaCache')) {
    /**
     * Retrieve a cached copy of the metadata whenever possible.
     *
     * @param string                  $package
     * @param \yuki\Scrapers\Metainfo $fetchMetadata
     * @return mixed
     */
    function metaCache($package, Metainfo $fetchMetadata)
    {
        return Cache::remember('apk-metainfo:' . $package, config('googleplay.metainfo_cache_ttl'), function () use ($package, $fetchMetadata) {
            return $fetchMetadata->build($package)
                                 ->run()
                                 ->output();
        });
    }
}

if (! function_exists('apkDirectory')) {
    /**
     * @param string|null $packageName
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
     * @param string $packageName
     * @param string $versionCode
     * @return string
     */
    function buildApkFilename($packageName, $versionCode)
    {
        return sprintf('%s.%s.apk', $packageName, $versionCode);
    }
}

if (! function_exists('apkPath')) {
    /**
     * @param string      $packageName
     * @param string|null $versionCode
     * @return string
     */
    function apkPath($packageName, $versionCode = null)
    {
        return Storage::url($packageName . '/' . buildApkFilename($packageName, $versionCode));
    }
}
