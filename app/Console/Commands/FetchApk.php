<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FetchApk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:fetch {apk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches the specified APK.';

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
     * @param \yuki\Scrapers\Download $download
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @return mixed
     */
    public function handle(Download $download, Metainfo $metainfo)
    {
        $packageName = $this->argument('apk');

        $this->info('Fetching metadata for ' . $packageName);
        $metadata = metaCache($packageName, $metainfo);

        $this->info('Downloading ' . $packageName);
        $downloadedFile = $download->build($packageName, $metadata->versionCode, $metadata->sha1)
                                   ->run()
                                   ->output();

        $this->downloadCompleted($downloadedFile);
    }

    protected function downloadCompleted($packageName)
    {
        Log::info('Downloaded ' . $packageName . '. ');
    }
}
