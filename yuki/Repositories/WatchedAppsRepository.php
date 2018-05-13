<?php

namespace yuki\Repositories;

use himekawa\WatchedApp;

class WatchedAppsRepository
{
    use CachesAccess;

    protected $fields = [
        'app_id',
        'version_code',
        'version_name',
        'size',
        'hash',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allApps()
    {
        return $this->taggedCached('apps', 'watched-apps:all', function () {
            return WatchedApp::all($this->fields);
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allAppsWithApks()
    {
        return $this->taggedCached('apps', 'watched-apps:all-available', function () {
            return WatchedApp::with([
                'availableApps' => function ($query) {
                    $query->select($this->fields);
                },
            ])->get();
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
