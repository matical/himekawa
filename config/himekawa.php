<?php

return [
    'announcement'  => [
        'ttl' => env('ANNOUNCEMENT_TTL', 60 * 24 * 3), // Minutes
        'key' => env('ANNOUNCEMENT_KEY', 'announcement'), // Minutes
    ],
    'notifications' => [
        'enabled' => env('NOTIFICATIONS_ENABLED', false),
        'to'      => env('TELEGRAM_BOT_ROUTE'),
    ],

    'cache'    => [
        'last-check' => 'scheduler:last-check',
    ],
    'commands' => [
        'gp-download'      => env('COMMANDS_GPDOWNLOAD', 'gp-download'),
        'gp-download-meta' => env('COMMANDS_GPDOWNLOADMETA', 'gp-download-meta'),
        'gp-details'       => env('COMMANDS_GPDETAILS', 'gp-details'),
    ],

    // Max number of apps to keep when the pruner is running
    'max_apps' => env('MAX_APPS', 5),

    'paths' => [
        'apps_to_import' => resource_path('apps.json'),
    ],

    'import'    => [
        'format' => 'ini',
    ],

    // Main APK downloader parameters
    'scheduler' => [
        'disabled_cache_key' => 'scheduler-disabled',
        'timezone'           => env('HIMEKAWA_SCHEDULER_TIMEZONE', 'Asia/Tokyo'),
        'start_time'         => env('HIMEKAWA_SCHEDULER_START', '8:00'),
        'end_time'           => env('HIMEKAWA_SCHEDULER_END', '19:35'),
    ],
];
