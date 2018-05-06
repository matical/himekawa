<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ApkStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display simple stats about available APKs';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line(sprintf(
            'Size: <info>%s</info> | Files Available: <info>%s</info>',
            humanReadableSize($this->totalSizeOfDirectory()),
            $this->totalAmountOfFiles()
        ));
    }

    /**
     * @return int
     */
    protected function totalSizeOfDirectory(): int
    {
        $totalSize = 0;

        foreach ($this->allFiles() as $file) {
            $totalSize += Storage::size($file);
        }

        return $totalSize;
    }

    /**
     * @return int
     */
    protected function totalAmountOfFiles(): int
    {
        return $this->allFiles()->count();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function allFiles(): Collection
    {
        return collect(array_filter(Storage::allFiles(), function ($file) {
            return ! (strpos($file, '.') === 0);
        }));
    }
}
