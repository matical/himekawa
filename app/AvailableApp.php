<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class AvailableApp extends Model
{
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
        return $this->belongsTo(AvailableApp::class, 'app_id');
    }
}
