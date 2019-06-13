<?php

use yuki\Version;
use Carbon\Carbon;
use yuki\Facades\Apk;
use Carbon\CarbonImmutable;
use yuki\Announce\Announcement;
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
        return Apk::resolveApkDirectory($packageName, $versionCode);
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
        return Apk::resolveApkFilename($packageName, $versionCode);
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
        return Apk::resolveApkUrl($packageName, $versionCode);
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
     * @return \Carbon\CarbonImmutable
     */
    function timestamp_format($timestamp)
    {
        return CarbonImmutable::createFromTimestamp($timestamp);
    }
}

if (! function_exists('announcement')) {
    /**
     * @return \yuki\Announce\Announcement
     */
    function announcement()
    {
        return app(Announcement::class);
    }
}

if (! function_exists('lastRun')) {
    /**
     * @return \yuki\Scheduler\LastRun
     */
    function lastRun()
    {
        return app('lastRun');
    }
}

if (! function_exists('humanReadableSize')) {
    /**
     * @param int $sizeInBytes
     * @return string
     */
    function humanReadableSize(int $sizeInBytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        if ($sizeInBytes === 0) {
            return '0 ' . $units[1];
        }

        for ($i = 0; $sizeInBytes > 1024; $i++) {
            $sizeInBytes /= 1024;
        }

        return round($sizeInBytes, 2) . ' ' . $units[$i];
    }
}

if (! function_exists('carbon')) {
    /**
     * @param $time
     * @return \Carbon\Carbon
     */
    function carbon($time)
    {
        return new Carbon($time);
    }
}
