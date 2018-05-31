<?php

namespace himekawa\Providers;

use yuki\Foundation\Apk;
use yuki\Scheduler\LastRun;
use Spatie\Feed\Helpers\Path;
use Spatie\Feed\Http\FeedController;
use Illuminate\Support\Facades\Route;
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
            return new Apk(config('googleplay'));
        });

        $this->app->bind('lastRun', function () {
            return new LastRun(config('himekawa'));
        });

        $this->registerFeedRoutes();
    }

    /**
     * Identical to the one declared in FeedServiceProvider, but with cache:etag defined.
     */
    protected function registerFeedRoutes()
    {
        Route::macro('rssFeeds', function ($baseUrl = '') {
            foreach (config('feed.feeds') as $name => $configuration) {
                $url = Path::merge($baseUrl, $configuration['url']);

                Route::get($url, '\\' . FeedController::class)
                     ->name("feeds.{$name}")
                     ->middleware('weak.cache');
            }
        });
    }
}
