<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Metainfo;
use yuki\Command\AbstractCommand as Command;

class FetchMetainfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:metainfo {apk}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for any available app updates.';

    /**
     * Execute the console command.
     *
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @return mixed
     */
    public function handle(Metainfo $metainfo)
    {
        $packageName = $this->getPackageName($this->argument('apk'));

        $output = metaCache($packageName, $metainfo);
        dump($output);
    }
}
