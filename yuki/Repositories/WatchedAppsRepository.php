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
        return $this->taggedCached('apps', 'watched-apps:all', fn () => WatchedApp::all());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allAppsWithApks()
    {
        return $this->taggedCached('apps', 'watched-apps:all-available', fn () => WatchedApp::with('availableApps')->get());
    }

    /**
     * @param int $id
     * @return \himekawa\WatchedApp
     */
    public function find($id)
    {
        return $this->taggedCached('apps', "watched-apps:$id", fn () => WatchedApp::findOrFail($id));
    }

    /**
     * @param string $slug
     * @return \himekawa\WatchedApp
     */
    public function findBySlug($slug)
    {
        return $this->taggedCached('apps', "watched-apps:$slug", fn () => WatchedApp::where('slug', $slug)
                                                                               ->first());
    }
}
