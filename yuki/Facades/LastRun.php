<?php

namespace yuki\Facades;

use Illuminate\Support\Facades\Facade;

class LastRun extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'lastRun';
    }
}
