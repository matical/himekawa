<?php

namespace himekawa\Listeners\Scheduler;

use himekawa\Notifications\ApkDownloaded;
use himekawa\Events\Scheduler\AppsUpdated;
use Illuminate\Support\Facades\Notification;

class NotifyByTelegram
{
    /** @var bool */
    protected $notificationsEnabled;

    /** @var string */
    protected $toRoute;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->toRoute = config('himekawa.notifications.to');
        $this->notificationsEnabled = config('himekawa.notifications.enabled');
    }

    /**
     * Handle the event.
     *
     * @param AppsUpdated $event
     * @return void
     */
    public function handle(AppsUpdated $event)
    {
        if ($this->notificationsEnabled && ! $event->noNotifications) {
            $this->dispatchNotification($event->appsUpdated);
        }
    }

    /**
     * @param array $appsUpdated
     */
    protected function dispatchNotification(array $appsUpdated): void
    {
        Notification::route('telegram', $this->toRoute)
                    ->notifyNow(new ApkDownloaded($appsUpdated));
    }
}
