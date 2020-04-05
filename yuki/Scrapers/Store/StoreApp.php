<?php

namespace yuki\Scrapers\Store;

use yuki\Facades\Apk;
use InvalidArgumentException;
use yuki\Scrapers\Versioning;
use Illuminate\Support\Facades\Storage;

class StoreApp
{
    protected string $packageName;

    protected int $versionCode;

    protected string $storeHash;

    protected int $sizeInBytes;

    /**
     * @param string $packageName
     * @param int    $versionCode
     * @param string $storeHash
     * @param int    $sizeInBytes
     */
    public function __construct(string $packageName, int $versionCode, string $storeHash, int $sizeInBytes)
    {
        $this->packageName = $packageName;
        $this->versionCode = $versionCode;
        $this->storeHash = $storeHash;
        $this->sizeInBytes = $sizeInBytes;
    }

    public static function createFromPayload($raw)
    {
        $properties = ['packageName', 'versionCode', 'sha1', 'size'];

        foreach ($properties as $property) {
            if (! property_exists($raw, $property)) {
                throw new InvalidArgumentException("Invalid body: '{$property}' is missing from payload.");
            }
        }

        return new static($raw->packageName, $raw->versionCode, $raw->sha1, $raw->size);
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getVersionCode(): int
    {
        return $this->versionCode;
    }

    public function expectedHash(): string
    {
        return $this->storeHash;
    }

    public function expectedSizeInBytes(): int
    {
        return $this->sizeInBytes;
    }

    /**
     * Filename the APK is *expected* to have.
     *
     * @return string
     */
    public function filename(): string
    {
        return Apk::resolveApkFilename($this->packageName, $this->versionCode);
    }

    /**
     * Path relative to the APK directory.
     *
     * @return string
     */
    public function relativePath()
    {
        return sprintf('%s/%s', $this->packageName, $this->filename());
    }

    /**
     * Fully qualified filename.
     *
     * @return string
     */
    public function fullPath(): string
    {
        return $this->storage()->path($this->relativePath());
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function verifyHash(): bool
    {
        $downloadedHash = sha1_file($this->fullPath());

        if (! $downloadedHash) {
            throw new \Exception('No file');
        }

        return $this->storeHash === $downloadedHash;
    }

    public function canBeUpdated()
    {
        return app(Versioning::class)->areUpdatesAvailableFor($this->packageName, $this->versionCode);
    }

    public function exists()
    {
        return $this->storage()->exists($this->relativePath());
    }

    public function deleteDownload()
    {
        if (! $this->exists()) {
        }

        return $this->storage()->delete($this->relativePath());
    }

    protected function storage()
    {
        return Storage::disk('apks');
    }
}
