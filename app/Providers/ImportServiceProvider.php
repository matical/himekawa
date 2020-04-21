<?php

namespace himekawa\Providers;

use yuki\Import\Ini;
use yuki\Import\Json;
use yuki\Import\ImportManager;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class ImportServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ImportManager::class, function () {
            $drivers = [
                'ini'  => Ini::class,
                'json' => Json::class,
            ];

            $resolve = $drivers[config('himekawa.import.format')];

            return new ImportManager($this->app->make($resolve));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [ImportManager::class];
    }
}
