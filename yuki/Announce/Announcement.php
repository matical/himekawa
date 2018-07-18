<?php

namespace yuki\Announce;

use Parsedown;
use Illuminate\Support\Facades\Cache;

class Announcement
{
    /**
     * @var \Illuminate\Cache\CacheManager
     */
    protected $cache;

    /**
     * @var int
     */
    protected $expiry;

    /**
     * @var string
     */
    protected $cacheKey;

    /**
     * Announcement constructor.
     */
    public function __construct()
    {
        $this->cache = app('cache');
        $this->cacheKey = config('himekawa.announcement.key');
        $this->expiry = config('himekawa.announcement.ttl');
    }

    /**
     * @param $message
     */
    public function broadcast($message)
    {
        $this->store($message);
    }

    /**
     * @return bool
     */
    public function available()
    {
        return Cache::has($this->cacheKey);
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return Cache::get($this->cacheKey, '');
    }

    /**
     * @param string $announcements
     */
    public function store(string $announcements)
    {
        Cache::put($this->cacheKey, $announcements, $this->expiry);
    }

    public function rendered()
    {
        return Cache::remember($this->cacheKey . '-rendered', $this->expiry, function () {
            return (new Parsedown())->text($this->get());
        });
    }

    /**
     * @return void
     */
    public function clear()
    {
        Cache::forget($this->cacheKey);
    }

    public function clearRendered()
    {
        Cache::forget($this->cacheKey . '-rendered');
    }
}
