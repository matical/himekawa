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
     * @var bool
     */
    public $noNotifications;

    /**
     * Create a new event instance.
     *
     * @param array $appsUpdated
     * @param bool  $noNotifications Should notifications be dispatched
     */
    public function __construct(array $appsUpdated, bool $noNotifications)
    {
        $this->appsUpdated = $appsUpdated;
        $this->noNotifications = $noNotifications;
    }
}
