<?php

namespace himekawa\Listeners\Scheduler;

use himekawa\Events\Scheduler\AppsUpdated;
use Illuminate\Support\Facades\Cache;

class FlushAppCache
{
    /**
     * Handle the event.
     *
     * @param AppsUpdated $event
     * @return void
     */
    public function handle(AppsUpdated $event)
    {
        Cache::tags('apps')->flush();
    }
}
