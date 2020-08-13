<?php

namespace yuki\Scrapers\Store;

use InvalidArgumentException;
use Illuminate\Support\Facades\Storage;

class SplitStoreApp
{
    use CanBeUpdated;

    protected array $hashes;

    protected array $sizes;

    /**
     * @param string $packageName
     * @param int    $versionCode
     * @param array  $hashes
     * @param array  $sizes
     */
    public function __construct(string $packageName, int $versionCode, array $hashes, array $sizes)
    {
        $this->packageName = $packageName;
        $this->versionCode = $versionCode;
        $this->hashes = $hashes;
        $this->sizes = $sizes;
    }

    public static function createFromPayload($raw)
    {
        foreach (['packageName', 'versionCode', 'sha1', 'splitSha1', 'size', 'splitSize'] as $property) {
            if (! property_exists($raw, $property)) {
                throw new InvalidArgumentException("Invalid metadata: '{$property}' is missing from payload.");
            }
        }

        $hashes = ['base' => $raw->sha1, 'split' => $raw->splitSha1];
        $sizes = ['base' => $raw->size, 'split' => $raw->splitSize];

        return new static($raw->packageName, $raw->versionCode, $hashes, $sizes);
    }

    public function getPackageName(): string
    {
        return $this->packageName;
    }

    public function getVersionCode(): int
    {
        return $this->versionCode;
    }

    public function getArchiveName(): string
    {
        return sprintf('%s.%s.apks', $this->getPackageName(), $this->getVersionCode());
    }

    public function getPathToSplits(): array
    {
        $path = $this->getDownloadLocation();

        return ["{$path}_base", "{$path}_split"];
    }

    public function getDownloadLocation(): string
    {
        return $this->storage()->path("{$this->packageName}/{$this->packageName}.{$this->versionCode}");
    }

    protected function storage()
    {
        return Storage::disk('apks');
    }
}
