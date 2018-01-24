<?php

namespace himekawa\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;

class ApkDownloaded extends Notification
{
    use Queueable;

    protected $appsUpdated = [];

    /**
     * Create a new notification instance.
     *
     * @param array $appsUpdated
     */
    public function __construct($appsUpdated)
    {
        $this->appsUpdated = $appsUpdated;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    /**
     * @param mixed $notifiable
     * @return \NotificationChannels\Telegram\TelegramMessage
     */
    public function toTelegram($notifiable)
    {
        $payload = "The following apps have been updated:\n\n";

        /** @var \himekawa\AvailableApp $updated */
        foreach ($this->appsUpdated as $updated) {
            $url = apkPath($updated->watchedBy->package_name, $updated->version_code);
            $payload .= "[{$updated->watchedBy->name}]($url) v{$updated->version_name} (vc{$updated->version_code})\n";
        }

        return TelegramMessage::create()
                              ->content($payload);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
