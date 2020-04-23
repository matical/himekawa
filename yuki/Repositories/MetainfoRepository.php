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
        return Cache::remember("apk-metainfo:{$package}", $this->cacheExpiry, fn () => $this->getPackage($package));
    }

    /**
     * @param string $package
     * @return mixed
     */
    protected function getPackage(string $package)
    {
        return $this->metainfo->fetch($package);
    }
}
