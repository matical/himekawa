<?php

namespace yuki\Announce;

use Illuminate\Support\Collection;
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
        $announcements = $this->get();
        $announcements->prepend($message);
        $this->store($announcements);
    }

    /**
     * @return bool
     */
    public function available()
    {
        return Cache::has($this->cacheKey);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function get(): Collection
    {
        return collect(Cache::get($this->cacheKey, []));
    }

    /**
     * @param \Illuminate\Support\Collection $announcements
     */
    protected function store(Collection $announcements)
    {
        Cache::put($this->cacheKey, $announcements, $this->expiry);
    }

    /**
     * @return void
     */
    public function clear()
    {
        Cache::forget($this->cacheKey);
    }
}
