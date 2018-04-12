<?php

namespace himekawa\Providers;

use yuki\Foundation\Apk;
use Illuminate\Support\ServiceProvider;

class HimeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('apk', function () {
            return new Apk();
        });
    }
}
