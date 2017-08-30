<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class AvailableApp extends Model
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function watchedBy()
    {
        return $this->belongsTo(AvailableApp::class, 'app_id');
    }
}
