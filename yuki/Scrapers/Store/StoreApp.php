<?php

namespace yuki\Scrapers\Store;

use yuki\Facades\Apk;
use InvalidArgumentException;
use UnexpectedValueException;
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

    /**
     * Create a new instance from a JSON payload.
     *
     * @param $raw
     * @return static
     */
    public static function createFromPayload($raw)
    {
        $properties = ['packageName', 'versionCode', 'sha1', 'size'];

        foreach ($properties as $property) {
            if (! property_exists($raw, $property)) {
                throw new InvalidArgumentException("Invalid metadata: '{$property}' is missing from payload.");
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
     * Path relative to the APK directory (for flysystem use).
     *
     * @return string
     */
    public function relativePath()
    {
        return sprintf('%s/%s', $this->packageName, $this->filename());
    }

    /**
     * Fully qualified filename to the APK.
     *
     * @return string
     */
    public function fullPath(): string
    {
        return $this->storage()->path($this->relativePath());
    }

    /**
     * Checks the downloaded file's hash matches the reported hash.
     *
     * @return bool
     * @throws \UnexpectedValueException
     */
    public function verifyHash(): bool
    {
        $downloadedHash = sha1_file($this->fullPath());

        if (! $downloadedHash) {
            // TODO: Use different exception
            throw new UnexpectedValueException("Failed to create hash for {$this->fullPath()}");
        }

        return $this->storeHash === $downloadedHash;
    }

    /**
     * Check if the package requires any updates.
     *
     * @return bool
     */
    public function canBeUpdated(): bool
    {
        return app(Versioning::class)->areUpdatesAvailableFor($this->packageName, $this->versionCode);
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->storage()->exists($this->relativePath());
    }

    /**
     * Attempt to delete corresponding local file.
     *
     * @return bool
     * @throws \UnexpectedValueException
     */
    public function deleteDownload()
    {
        if (! $this->exists()) {
            throw new UnexpectedValueException('No local file to delete.');
        }

        return $this->storage()->delete($this->relativePath());
    }

    /**
     * Get the storage instance.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function storage()
    {
        return Storage::disk('apks');
    }
}
