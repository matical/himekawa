<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class WatchedApp extends Model
{
    protected $appends = [
        'image',
    ];

    /**
     * Apps available.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableApps()
    {
        return $this->hasMany(AvailableApp::class, 'app_id')
                    ->orderByDesc('version_code');
    }

    /**
     * Fetch the latest App.
     *
     * @return \himekawa\AvailableApp|null
     */
    public function latestApp()
    {
        return $this->availableApps()
                    ->orderByDesc('version_code')
                    ->first();
    }

    /**
     * @return \Illuminate\Support\HtmlString|string
     * @throws \Exception
     */
    public function getImageAttribute()
    {
        return mix("images/{$this->package_name}.png") ?? '';
    }
}
