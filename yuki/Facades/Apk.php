<?php

namespace yuki\Facades;

use Illuminate\Support\Facades\Facade;

class Apk extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'apk';
    }
}
