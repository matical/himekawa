<?php

use yuki\Version;
use Cake\Chronos\Chronos;
use yuki\Repositories\MetainfoRepository;

if (! function_exists('metacache')) {
    /**
     * Retrieve a cached copy of the metadata whenever possible.
     *
     * @param string $package
     * @return mixed
     */
    function metacache($package)
    {
        return app(MetainfoRepository::class)->getPackageInfo($package);
    }
}

if (! function_exists('apkDirectory')) {
    /**
     * @param string|null $packageName
     * @param int|null    $versionCode
     * @return string
     */
    function apkDirectory($packageName = null, int $versionCode = null)
    {
        $apkPath = config('googleplay.apk_base_path');

        if (is_null($packageName)) {
            return $apkPath;
        }

        if ($packageName && is_null($versionCode)) {
            return $apkPath . DIRECTORY_SEPARATOR . $packageName;
        }

        return $apkPath . DIRECTORY_SEPARATOR . $packageName . DIRECTORY_SEPARATOR . sprintf('%s.%s.apk', $packageName, $versionCode);
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

if (! function_exists('git')) {
    /**
     * @return \yuki\Version
     */
    function git()
    {
        return new Version();
    }
}

if (! function_exists('timestamp_format')) {
    /**
     * @param $timestamp
     * @return Chronos
     */
    function timestamp_format($timestamp)
    {
        return Chronos::createFromTimestamp($timestamp);
    }
}
