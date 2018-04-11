<?php

namespace yuki\Repositories;

use Illuminate\Support\Facades\Cache;
use yuki\Scrapers\Metainfo;

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
     * MetainfoRepository constructor.
     *
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
            return $this->fetchPackageInfo($package);
        });
    }

    /**
     * @param string $package
     * @return mixed
     */
    public function fetchPackageInfo(string $package)
    {
        return $this->metainfo->build($package)
                              ->run()
                              ->output();
    }
}
