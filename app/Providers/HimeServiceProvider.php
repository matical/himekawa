<?php

namespace himekawa\Providers;

use yuki\Foundation\Apk;
use yuki\Scheduler\LastRun;
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
            return new Apk([
                'apk_path' => config('googleplay.apk_base_path'),
            ]);
        });

        $this->app->bind('lastRun', function () {
            return new LastRun([
                'last-check-key'    => config('himekawa.cache.last-run'),
                'last-update-key'   => config('himekawa.cache.last-update'),
            ]);
        });
    }
}
