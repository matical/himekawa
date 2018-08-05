<?php

namespace yuki\Command\Apk;

use himekawa\WatchedApp;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Stats
{
    /**
     * @return string
     */
    public function totalSizeOfDirectory(): string
    {
        return humanReadableSize($this->allFiles()->reduce(function ($total, $current) {
            return $total + Storage::size($current);
        }, 0));
    }

    /**
     * @return int
     */
    public function totalAmountOfFiles(): int
    {
        return $this->allFiles()->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function allFiles(): Collection
    {
        return collect($this->disk()->allFiles())->filter(function ($file) {
            return ! (strpos($file, '.') === 0);
        });
    }

    public function summary()
    {
        return WatchedApp::latest('updated_at')
                         ->take(5)
                         ->get()
                         ->mapWithKeys(function (WatchedApp $watched) {
                             $payload = array_merge($watched->latestApp()->toArray(), ['name' => $watched->name]);

                             return [$watched->package_name => $payload];
                         });
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function disk()
    {
        return Storage::disk('apks');
    }
}
