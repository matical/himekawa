<?php

return [
    'max_apps'  => env('MAX_APPS', 5),
    'scheduler' => [
        'timezone'   => env('HIMEKAWA_SCHEDULER_TIMEZONE', 'Asia/Tokyo'),
        'start_time' => env('HIMEKAWA_SCHEDULER_START', '8:00'),
        'end_time'   => env('HIMEKAWA_SCHEDULER_END', '19:35'),
    ],
];
