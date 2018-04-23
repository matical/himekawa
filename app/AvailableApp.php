<?php

namespace himekawa;

use yuki\Facades\Apk;
use Illuminate\Database\Eloquent\Model;

class AvailableApp extends Model
{
    protected $appends = [
        'url',
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
     * Generate the url to download the APK.
     *
     * @return string
     */
    public function url()
    {
        return Apk::resolveApkUrl($this->watchedBy->package_name, $this->version_code);
    }

    public function getUrlAttribute()
    {
        return Apk::resolveApkUrl($this->watchedBy->package_name, $this->version_code);
    }
}
