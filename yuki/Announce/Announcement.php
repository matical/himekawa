<?php

namespace yuki\Announce;

use Parsedown;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Cache\Repository;

class Announcement
{
    /**
     * @var \Illuminate\Cache\Repository
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
     *
     * @param \Illuminate\Contracts\Cache\Repository $cacheRepository
     */
    public function __construct(Repository $cacheRepository)
    {
        $this->cache = $cacheRepository;
        $this->cacheKey = config('himekawa.announcement.key');
        $this->expiry = config('himekawa.announcement.ttl');
    }

    /**
     * @param $message
     */
    public function broadcast($message)
    {
        $this->clear();
        $this->store($message);
        $this->announced();
    }

    /**
     * @return bool
     */
    public function available()
    {
        return $this->cache()->has($this->cacheKey);
    }

    public function announcedOn()
    {
        return $this->cache()->get('announced-on');
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->cache()->get($this->cacheKey, '');
    }

    /**
     * @param string $announcement
     */
    public function store(string $announcement)
    {
        $this->cache()->forever($this->cacheKey, $announcement);
    }

    public function rendered()
    {
        return $this->cache()->remember($this->cacheKey . '-rendered', $this->expiry, fn () => (new Parsedown())->text($this->get()));
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->cache()->flush();
    }

    protected function cache()
    {
        return $this->cache->tags('announcement');
    }

    protected function announced()
    {
        $this->cache()->forever('announced-on', now());
    }
}
