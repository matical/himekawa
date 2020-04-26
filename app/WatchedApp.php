<?php

namespace himekawa;

use Exception;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Builder;

class WatchedApp extends Model
{
    protected $casts = [
        'updated_at'           => 'datetime:Y-m-d\TH:i:sP',
        'use_split'            => 'boolean',
        'use_additional_files' => 'boolean',
    ];

    protected $appends = [
        'image',
    ];

    protected static function booted()
    {
        static::addGlobalScope('disabled', fn (Builder $query) => $query->whereNull('disabled'));
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSingle(Builder $query)
    {
        return $query->where('use_split', false);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSplit(Builder $query)
    {
        return $query->where('use_split', true);
    }

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
        return $this->availableApps()->first();
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
