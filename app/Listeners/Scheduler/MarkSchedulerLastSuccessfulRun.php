<?php

namespace himekawa\Listeners\Scheduler;

use Illuminate\Support\Facades\Cache;
use himekawa\Events\Scheduler\AppsUpdated;

class MarkSchedulerLastsuccessfulRun
{
    /**
     * Handle the event.
     *
     * @param AppsUpdated $event
     * @return void
     */
    public function handle(AppsUpdated $event)
    {
        Cache::forever('scheduler:last-update', now()->timestamp);
    }
}
