<?php

namespace himekawa\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use himekawa\Http\Middleware\HttpTwoPushMiddleware;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // Only use http2 push in prod since it breaks `yarn run hot`
            $this->app['router']->pushMiddlewareToGroup('web', HttpTwoPushMiddleware::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal()) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }
}
