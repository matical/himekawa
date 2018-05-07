<?php

namespace yuki\Foundation;

use Illuminate\Support\Facades\Storage;

class Apk
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Build the "target" apk file.
     *
     * @param string $packageName
     * @param int    $versionCode
     * @return string
     */
    public function resolveApkFilename(string $packageName, int $versionCode): string
    {
        return sprintf('%s.%s.apk', $packageName, $versionCode);
    }

    /**
     * Build the apk directory.
     *
     * @param string|null $packageName
     * @param int|null    $versionCode
     * @return string
     */
    public function resolveApkDirectory(?string $packageName = null, ?int $versionCode = null): string
    {
        $apkPath = $this->config['apk_base_path'];

        if ($packageName === null) {
            return $apkPath;
        }

        if ($packageName && $versionCode === null) {
            return $apkPath . DIRECTORY_SEPARATOR . $packageName;
        }

        return $apkPath . DIRECTORY_SEPARATOR . $packageName . DIRECTORY_SEPARATOR . $this->resolveApkFilename($packageName, $versionCode);
    }

    /**
     * @param $packageName
     * @param $versionCode
     * @return string
     */
    public function resolveApkUrl(string $packageName, int $versionCode): string
    {
        return Storage::url($packageName . '/' . buildApkFilename($packageName, $versionCode));
    }
}
