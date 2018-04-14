<?php

namespace himekawa\Listeners\Scheduler;

use himekawa\User;
use Illuminate\Queue\InteractsWithQueue;
use himekawa\Notifications\ApkDownloaded;
use himekawa\Events\Scheduler\AppsUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyByTelegram
{
    /**
     * @var bool
     */
    protected $notificationsEnabled;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->notificationsEnabled = config('himekawa.notifications');
    }

    /**
     * Handle the event.
     *
     * @param AppsUpdated $event
     * @return void
     */
    public function handle(AppsUpdated $event)
    {
        if ($this->notificationsEnabled) {
            User::find(1)->notifyNow(new ApkDownloaded($event->appsUpdated));
        }
    }
}
