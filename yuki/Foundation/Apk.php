<?php

namespace yuki\Foundation;

use Illuminate\Support\Facades\Storage;

class Apk
{
    /**
     * @var array
     */
    protected $config;

    public function __construct()
    {
        $this->config = config('googleplay');
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
        $basePath = $this->config['apk_base_path'];

        if ($packageName === null) {
            return $basePath;
        }

        if ($packageName && $versionCode === null) {
            return $basePath . DIRECTORY_SEPARATOR . $packageName;
        }

        return $basePath . DIRECTORY_SEPARATOR . $packageName . DIRECTORY_SEPARATOR . $this->resolveApkFilename($packageName, $versionCode);
    }

    /**
     * @param $packageName
     * @param $versionCode
     * @return string
     */
    public function resolveApkUrl(string $packageName, int $versionCode): string
    {
        return $this->storage()
                    ->url($packageName . '/' . $this->resolveApkFilename($packageName, $versionCode));
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function storage()
    {
        return Storage::disk('apks');
    }
}
