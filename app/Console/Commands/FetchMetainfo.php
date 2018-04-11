<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Metainfo;
use yuki\Command\BaseCommand as Command;

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
     * @return mixed
     */
    public function handle()
    {
        $packageName = $this->getPackageName($this->argument('apk'));

        $output = metacache($packageName);
        dump($output);
    }
}
