<?php

namespace himekawa;

use yuki\Facades\Apk;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class AvailableApp extends Model implements Feedable
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d\TH:i:sP',
    ];

    protected $touches = [
        'watchedBy',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'version_code',
        'version_name',
        'size',
        'hash',
        'raw_badging',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function watchedBy()
    {
        return $this->belongsTo(WatchedApp::class, 'app_id')
                    ->withDefault();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function badging()
    {
        return $this->hasOne(Badging::class, 'available_app_id');
    }

    /**
     * @return string
     */
    public function getFilenameAttribute()
    {
        return Apk::resolveApkFilename($this->watchedBy->package_name, $this->version_code);
    }

    /**
     * @return string
     */
    public function getHumanBytesAttribute()
    {
        return humanReadableSize($this->size);
    }

    /**
     * Generate the url to download the APK.
     *
     * @return string
     */
    public function url()
    {
        return Apk::resolveApkUrl($this->watchedBy->package_name, $this->version_code);
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return Apk::resolveApkUrl($this->watchedBy->package_name, $this->version_code);
    }

    /**
     * @return array|\Spatie\Feed\FeedItem
     */
    public function toFeedItem()
    {
        $summary = sprintf(
            '<p>SHA1: %s | Size: %s | <a href="%s">Download</a> </p>',
            $this->hash,
            humanReadableSize($this->size),
            $this->url
        );

        return FeedItem::create()
                       ->id($this->url)
                       ->title("{$this->watchedBy->name} [{$this->watchedBy->original_title}] v{$this->version_name}")
                       ->summary($summary)
                       ->updated($this->created_at)
                       ->link($this->url)
                       ->author('himekawa');
    }

    /**
     * @return mixed
     */
    public static function getFeedItems()
    {
        return Cache::tags('apps')
                    ->remember('available-apps:all-watched', config('googleplay.metainfo_cache_ttl'), function () {
                        return AvailableApp::with('watchedBy')
                                           ->latest()
                                           ->limit(20)
                                           ->get();
                    });
    }
}
