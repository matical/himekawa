<?php

namespace yuki\Scheduler;

use Cake\Chronos\Chronos;
use Illuminate\Support\Facades\Cache;

class LastRun
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        $this->mark($this->config['last-check-key']);
    }

    /**
     * @return void
     */
    public function markLastUpdate()
    {
        $this->mark($this->config['last-update-key']);
    }

    /**
     * @return \Cake\Chronos\Chronos
     */
    public function lastCheck()
    {
        if ($lastRun = Cache::get($this->config['last-check-key'])) {
            return $this->createFromTimestamp($lastRun);
        }
    }

    /**
     * @return \Cake\Chronos\Chronos
     */
    public function lastUpdate()
    {
        if ($lastUpdate = Cache::get($this->config['last-update-key'])) {
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
