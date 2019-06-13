<?php

namespace yuki\Scheduler;

use Carbon\CarbonImmutable;
use Illuminate\Support\Arr;
use Illuminate\Cache\Repository as CacheRepository;

class LastRun
{
    /** @var string */
    protected $lastCheckKey;

    /** @var \Illuminate\Cache\Repository */
    protected $cache;

    /**
     * @param array                        $config
     * @param \Illuminate\Cache\Repository $cache
     */
    public function __construct(array $config, CacheRepository $cache)
    {
        $this->lastCheckKey = Arr::get($config, 'cache.last-check');
        $this->cache = $cache;
    }

    /**
     * @return void
     */
    public function markLastCheck()
    {
        $this->cache->forever($this->lastCheckKey, now()->timestamp);
    }

    /**
     * @return \Carbon\CarbonImmutable
     */
    public function lastCheck()
    {
        if ($lastRun = $this->cache->get($this->lastCheckKey)) {
            return $this->createFromTimestamp($lastRun);
        }
    }

    /**
     * @param $timestamp
     * @return \Carbon\CarbonImmutable
     */
    protected function createFromTimestamp($timestamp)
    {
        return CarbonImmutable::createFromTimestamp($timestamp);
    }
}
