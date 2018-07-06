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
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

//        \DB::listen(function($query)
//        {
//            info("{$query->sql}");
//        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    protected function registerBladeDirectives()
    {
        Blade::directive('rand', function ($randomValues) {
            return "<?php echo e(array_random({$randomValues})) ?>";
        });
    }
}
