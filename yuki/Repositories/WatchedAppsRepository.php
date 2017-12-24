<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;
use Illuminate\Support\Facades\Cache;

class WatchedAppsRepository
{
    /**
     * @return \himekawa\WatchedApp
     */
    public function allApps()
    {
        return Cache::remember('watched-apps:all', config('googleplay.metainfo_cache_ttl'), function () {
            return WatchedApp::all();
        });
    }

    /**
     * @param $slug
     * @return \himekawa\WatchedApp
     */
    public function findBySlug($slug)
    {
        return WatchedApp::where('slug', $slug)
                         ->first();
    }
}
