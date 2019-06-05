<?php

namespace yuki\Repositories;

use yuki\Scrapers\Metainfo;
use Illuminate\Support\Facades\Cache;

class MetainfoRepository
{
    /**
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metainfo;

    /**
     * @var int
     */
    protected $cacheExpiry;

    /**
     * @param \yuki\Scrapers\Metainfo $metainfo
     */
    public function __construct(Metainfo $metainfo)
    {
        $this->metainfo = $metainfo;
        $this->cacheExpiry = config('googleplay.metainfo_cache_ttl');
    }

    /**
     * @param $package
     * @return mixed
     */
    public function getPackageInfo($package)
    {
        return Cache::remember("apk-metainfo:{$package}", $this->cacheExpiry, function () use ($package) {
            return $this->retrievePackage($package);
        });
    }

    /**
     * @param string $package
     * @return mixed
     */
    protected function retrievePackage(string $package)
    {
        return $this->metainfo->build($package)
                              ->run()
                              ->output();
    }
}
