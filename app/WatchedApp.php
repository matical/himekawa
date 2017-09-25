<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class WatchedApp extends Model
{
    /**
     * Apps available.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function availableApps()
    {
        return $this->hasMany(AvailableApp::class, 'app_id');
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
}
