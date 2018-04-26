<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;

class WatchedAppsRepository
{
    use CachesAccess;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allApps()
    {
        return $this->taggedCached('apps', 'watched-apps:all', function () {
            return WatchedApp::all();
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allAppsWithApks()
    {
        return $this->taggedCached('apps', 'watched-apps:all-available', function () {
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
        return $this->taggedCached('apps', "watched-apps:$id", function () use ($id) {
            return WatchedApp::findOrFail($id);
        });
    }

    /**
     * @param $slug
     * @return \himekawa\WatchedApp
     */
    public function findBySlug($slug)
    {
        return $this->taggedCached('apps', "watched-apps:$slug", function () use ($slug) {
            return WatchedApp::where('slug', $slug)
                             ->first();
        });
    }
}
