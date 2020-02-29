<?php

namespace himekawa\Listeners\Scheduler;

use himekawa\Notifications\ApkDownloaded;
use himekawa\Events\Scheduler\AppsUpdated;
use Illuminate\Support\Facades\Notification;

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
        if ($this->notificationsEnabled && ! $event->noNotifications) {
            Notification::route('telegram', env('TELEGRAM_BOT_ROUTE'))
                        ->notifyNow(new ApkDownloaded($event->appsUpdated));
        }
    }
}
