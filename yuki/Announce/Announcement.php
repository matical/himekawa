<?php

namespace yuki\Announce;

use Parsedown;
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

    /**
     * @return mixed
     */
    public function announcedOn()
    {
        return $this->cache()->get('announced-on');
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->cache()
                    ->get($this->cacheKey, '');
    }

    /**
     * @param string $announcement
     * @return bool
     */
    public function store(string $announcement)
    {
        return $this->cache()
                    ->forever($this->cacheKey, $announcement);
    }

    /**
     * @return mixed
     */
    public function rendered()
    {
        return $this->cache()->remember(
            $this->cacheKey . '-rendered',
            $this->expiry,
            fn () => (new Parsedown())->text($this->get())
        );
    }

    /**
     * @return void
     */
    public function clear()
    {
        $this->cache()->flush();
    }

    /**
     * @return \Illuminate\Cache\TaggedCache
     */
    protected function cache()
    {
        return $this->cache->tags('announcement');
    }

    /**
     * @return bool
     */
    protected function announced()
    {
        return $this->cache()->forever('announced-on', now());
    }
}
