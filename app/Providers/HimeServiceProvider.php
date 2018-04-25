<?php

namespace himekawa\Providers;

use yuki\Foundation\Apk;
use yuki\Scheduler\LastRun;
use Spatie\Feed\Helpers\Path;
use Spatie\Feed\Http\FeedController;
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
                'last-check-key'  => config('himekawa.cache.last-run'),
                'last-update-key' => config('himekawa.cache.last-update'),
            ]);
        });

        $this->registerFeedRoutes();
    }

    /**
     * Identical to the one declared in FeedServiceProvider, but with cache:etag defined.
     */
    protected function registerFeedRoutes()
    {
        $router = app('router');

        $router->macro('rssFeeds', function ($baseUrl = '') use ($router) {
            foreach (config('feed.feeds') as $name => $configuration) {
                $url = Path::merge($baseUrl, $configuration['url']);

                $router->get($url, '\\' . FeedController::class)
                       ->name("feeds.{$name}")
                       ->middleware('weak.cache');
            }
        });
    }
}
