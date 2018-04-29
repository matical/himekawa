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
     * @var string
     */
    protected $lastUpdateKey;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->lastCheckKey = array_get($config, 'cache.last-check');
        $this->lastUpdateKey = array_get($config, 'cache.last-update');
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
     * @return void
     */
    public function markLastUpdate()
    {
        $this->mark($this->lastUpdateKey);
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
     * @return \Cake\Chronos\Chronos
     */
    public function lastUpdate()
    {
        if ($lastUpdate = Cache::get($this->lastUpdateKey)) {
            return $this->createFromTimestamp($lastUpdate);
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
