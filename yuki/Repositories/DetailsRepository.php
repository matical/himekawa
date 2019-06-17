<?php

namespace yuki\Repositories;

use yuki\Scrapers\Details;
use Illuminate\Support\Facades\Cache;

class DetailsRepository
{
    /**
     * @var int
     */
    protected $cacheExpiry;

    /**
     * @var \yuki\Scrapers\Details
     */
    protected $details;

    /**
     * @param \yuki\Scrapers\Details $details
     */
    public function __construct(Details $details)
    {
        $this->details = $details;
        $this->cacheExpiry = config('googleplay.metainfo_cache_ttl');
    }

    /**
     * @param $package
     * @return mixed
     */
    public function getDetailsInfo($package)
    {
        return Cache::remember("apk-details:{$package}", $this->cacheExpiry, function () use ($package) {
            return $this->fetchDetails($package);
        });
    }

    /**
     * @param string $package
     * @return mixed
     */
    public function fetchDetails(string $package)
    {
        return $this->details->build($package)
                             ->run()
                             ->getOutput();
    }
}
