<?php

return [
    'default' => env('NANA_FAUCET', 'default'),

    'faucets' => [

        /*
        |--------------------------------------------------------------------------
        | Guzzle Options
        |--------------------------------------------------------------------------
        |
        | Here you may configure as many faucets as you wish. <guzzle_config> is
        | passed directly to Guzzle's Request Options.
        |
        | See http://docs.guzzlephp.org/en/stable/request-options.html for more info.
        */
        'default' => [

            'default_disk'  => 'default',
            'guzzle_config' => [
                'http_errors' => false,
                'headers'     => [
                    'User-Agent' => 'nana/1.0',
                    'Accept'     => 'application/json',
                ],
            ],

        ],

        'image' => [
            'default_disk'  => 'images',
            'guzzle_config' => [
                'http_errors' => true,
                'headers'     => [
                    'User-Agent' => 'AndroidDownloadManager/6.0.1 (Linux; U; Android 6.0.1; Nexus 7 Build/MOB30X)',
                ],
            ],
        ],
    ],

];
