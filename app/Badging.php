<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class Badging extends Model
{
    protected $fillable = [
        'raw_badging',
    ];

    protected $primaryKey = 'available_app_id';

    public $timestamps = false;

    public function app()
    {
        return $this->belongsTo(AvailableApp::class);
    }
}
