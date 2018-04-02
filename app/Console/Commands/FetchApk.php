<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use yuki\Command\BaseCommand as Command;

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
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metadata;

    /**
     * Execute the console command.
     *
     * @param \yuki\Scrapers\Download $download
     * @param \yuki\Scrapers\Metainfo $metainfo
     *
     * @return mixed
     *
     * @throws \yuki\Exceptions\PackageAlreadyExistsException
     */
    public function handle(Download $download, Metainfo $metainfo)
    {
        $packageName = $this->getPackageName($this->argument('apk'));

        $this->info('Fetching metadata for ' . $packageName);
        $this->metadata = metaCache($packageName, $metainfo);

        $this->info('Downloading ' . $packageName);
        $downloadedFilename = $download->build($packageName, $this->metadata->versionCode, $this->metadata->sha1)
                                       ->run()
                                       ->output();

        $download->store();

        $this->info("Downloaded $downloadedFilename");
    }
}
