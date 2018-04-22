<?php

namespace yuki\Repositories;

use Closure;
use himekawa\WatchedApp;
use Illuminate\Support\Facades\Cache;

class WatchedAppsRepository
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function allApps()
    {
        return $this->cached('watched-apps:all', function () {
            return WatchedApp::all();
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allAppsWithApks()
    {
        return $this->cached('watched-apps:all-available', function () {
            return WatchedApp::with('availableApps')
                             ->get();
        });
    }

    /**
     * @param $id
     * @return \himekawa\WatchedApp
     */
    public function find($id)
    {
        return $this->cached("watched-apps:$id", function () use ($id) {
            return WatchedApp::findOrFail($id);
        });
    }

    /**
     * @param $slug
     * @return \himekawa\WatchedApp
     */
    public function findBySlug($slug)
    {
        return $this->cached("watched-apps:$slug", function () use ($slug) {
            return WatchedApp::where('slug', $slug)
                             ->first();
        });
    }

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
}
