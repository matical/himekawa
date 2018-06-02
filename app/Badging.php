<?php

namespace himekawa;

use Illuminate\Database\Eloquent\Model;

class Badging extends Model
{
    protected $fillable = [
        'raw_badging',
    ];

    public $timestamps = false;

    public function app()
    {
        return $this->belongsTo(AvailableApp::class);
    }
}
