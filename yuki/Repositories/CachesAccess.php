<?php

namespace yuki\Repositories;

use Closure;
use Illuminate\Support\Facades\Cache;

trait CachesAccess
{
    /**
     * @param          $key
     * @param \Closure $callback
     * @return mixed
     */
    protected function cached(string $key, Closure $callback)
    {
        return Cache::remember($key, config('googleplay.metainfo_cache_ttl'), function () use ($callback) {
            return $callback();
        });
    }

    /**
     * @param  array|mixed $tags
     * @param string       $key
     * @param \Closure     $callback
     * @return mixed
     */
    protected function taggedCached($tags, string $key, Closure $callback)
    {
        return Cache::tags($tags)->remember($key, config('googleplay.metainfo_cache_ttl'), function () use ($callback) {
            return $callback();
        });
    }
}
