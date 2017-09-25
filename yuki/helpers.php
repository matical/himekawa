<?php

use yuki\Scrapers\Metainfo;

if (! function_exists('metaCache')) {
    /**
     * @param                         $package
     * @param \yuki\Scrapers\Metainfo $fetchMetadata
     * @return mixed
     */
    function metaCache($package, Metainfo $fetchMetadata)
    {
        return Cache::remember('apk-metainfo:' . $package, 15, function () use ($package, $fetchMetadata) {
            return $fetchMetadata->build($package)
                                 ->run()
                                 ->output();
        });
    }
}
