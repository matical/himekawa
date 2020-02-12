<?php

namespace himekawa\Providers;

use himekawa\Events\Scheduler\AppsUpdated;
use himekawa\Listeners\Scheduler\FlushAppCache;
use himekawa\Listeners\Scheduler\MarkSchedulerLastSuccessfulRun;
use himekawa\Listeners\Scheduler\NotifyByTelegram;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
            NotifyByTelegram::class,
            // MarkSchedulerLastSuccessfulRun::class,
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
