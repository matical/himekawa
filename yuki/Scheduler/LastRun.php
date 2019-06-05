<?php

namespace yuki\Scheduler;

use Cake\Chronos\Chronos;
use Illuminate\Support\Facades\Cache;

class LastRun
{
    /**
     * @var string
     */
    protected $lastCheckKey;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->lastCheckKey = array_get($config, 'cache.last-check');
    }

    /**
     * @param $key
     */
    public function mark($key)
    {
        Cache::forever($key, now()->timestamp);
    }

    /**
     * @return void
     */
    public function markLastCheck()
    {
        $this->mark($this->lastCheckKey);
    }

    /**
     * @return \Cake\Chronos\Chronos
     */
    public function lastCheck()
    {
        if ($lastRun = Cache::get($this->lastCheckKey)) {
            return $this->createFromTimestamp($lastRun);
        }
    }

    /**
     * @param $timestamp
     * @return \Cake\Chronos\Chronos
     */
    protected function createFromTimestamp($timestamp)
    {
        return Chronos::createFromTimestamp($timestamp);
    }
}
