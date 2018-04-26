<?php

namespace himekawa\Listeners\Scheduler;

use Illuminate\Support\Facades\Cache;
use himekawa\Events\Scheduler\AppsUpdated;

class FlushAppCache
{
    /**
     * Handle the event.
     *
     * @param  AppsUpdated $event
     * @return void
     */
    public function handle(AppsUpdated $event)
    {
        Cache::tags('apps')->flush();
    }
}
