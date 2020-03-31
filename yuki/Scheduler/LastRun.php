<?php

namespace yuki\Scheduler;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cache;

class LastRun
{
    /** @var string */
    protected $lastCheckKey;

    public function __construct()
    {
        $this->lastCheckKey = config('himekawa.cache.last-check');
    }

    /**
     * @return void
     */
    public function markLastCheck()
    {
        $this->cache()->forever($this->lastCheckKey, now()->timestamp);
    }

    /**
     * @return \Carbon\CarbonImmutable
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function lastCheck()
    {
        if ($lastRun = $this->cache()->get($this->lastCheckKey)) {
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

    /**
     * @return \Illuminate\Contracts\Cache\Repository
     */
    protected function cache()
    {
        return Cache::driver();
    }
}
