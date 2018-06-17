<?php

namespace yuki\Command\Apk;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Stats
{
    /**
     * @return string
     */
    public function totalSizeOfDirectory(): string
    {
        $totalSize = 0;

        foreach ($this->allFiles() as $file) {
            $totalSize += Storage::size($file);
        }

        return humanReadableSize($totalSize);
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
        // Exclude '.' and '..'
        $files = array_filter($this->disk()->allFiles(), function ($file) {
            return ! (strpos($file, '.') === 0);
        });

        return collect($files);
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function disk()
    {
        return Storage::disk('apks');
    }
}
