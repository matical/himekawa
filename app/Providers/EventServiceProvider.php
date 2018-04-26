<?php

namespace himekawa\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'himekawa\Events\Scheduler\AppsUpdated' => [
            'himekawa\Listeners\Scheduler\FlushAppCache',
            'himekawa\Listeners\Scheduler\MarkSchedulerLastSuccessfulRun',
            'himekawa\Listeners\Scheduler\NotifyByTelegram',
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
