<?php

namespace himekawa\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerBladeDirectives();

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
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

        if ($this->app->environment('production')) {
            // Only use http2 push in prod since it breaks `yarn run hot`
            $this->app->router->pushMiddlewareToGroup('web', \JacobBennett\Http2ServerPush\Middleware\AddHttp2ServerPush::class);
        }
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('rand', function ($randomValues) {
            return "<?php echo e(array_random({$randomValues})) ?>";
        });
    }
}
