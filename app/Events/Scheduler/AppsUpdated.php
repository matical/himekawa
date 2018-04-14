<?php

namespace himekawa\Events\Scheduler;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class AppsUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $appsUpdated;

    /**
     * Create a new event instance.
     *
     * @param array $appsUpdated
     */
    public function __construct(array $appsUpdated)
    {
        $this->appsUpdated = $appsUpdated;
    }
}
