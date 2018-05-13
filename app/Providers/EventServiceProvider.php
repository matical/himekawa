<?php

namespace himekawa\Providers;

use Illuminate\Support\Facades\Event;
use himekawa\Events\Scheduler\AppsUpdated;
use himekawa\Listeners\Scheduler\FlushAppCache;
use himekawa\Listeners\Scheduler\NotifyByTelegram;
use himekawa\Listeners\Scheduler\MarkSchedulerLastSuccessfulRun;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        AppsUpdated::class => [
            FlushAppCache::class,
            MarkSchedulerLastSuccessfulRun::class,
            NotifyByTelegram::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
