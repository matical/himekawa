<?php

return [
    'announcement'  => [
        'ttl' => env('ANNOUNCEMENT_TTL', 60 * 24 * 3), // Minutes
        'key' => env('ANNOUNCEMENT_KEY', 'announcement'), // Minutes
    ],
    'notifications' => env('NOTIFICATIONS_ENABLED', false),
    'cache'         => [
        'last-check'    => 'scheduler:last-check',
        'last-update'   => 'scheduler:last-update',
    ],
    'commands'      => [
        'gp-download'      => env('COMMANDS_GPDOWNLOAD', 'npx gp-download'),
        'gp-download-meta' => env('COMMANDS_GPDOWNLOADMETA', 'npx gp-download-meta'),
    ],
    'max_apps'      => env('MAX_APPS', 5),
    'scheduler'     => [
        'timezone'   => env('HIMEKAWA_SCHEDULER_TIMEZONE', 'Asia/Tokyo'),
        'start_time' => env('HIMEKAWA_SCHEDULER_START', '8:00'),
        'end_time'   => env('HIMEKAWA_SCHEDULER_END', '19:35'),
    ],
];
