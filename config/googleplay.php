<?php

return [
    'apk_base_path'      => env('APK_PATH', storage_path('apks')),
    'metainfo_cache_ttl' => env('METAINFO_TTL', 15 * 60),
    'download_timeout'   => env('SCRAPER_TIMEOUT', 300),
];
