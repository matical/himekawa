<?php

namespace himekawa;

use Exception;
use Illuminate\Database\Eloquent\Model;

class WatchedApp extends Model
{
    protected $casts = [
        'updated_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

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
     */
    public function getImageAttribute()
    {
        try {
            return (string) mix("images/{$this->package_name}.png");
        } catch (Exception $e) {
            return '';
        }
    }
}
