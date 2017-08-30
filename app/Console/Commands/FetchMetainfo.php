<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Metainfo;
use Illuminate\Console\Command;

class FetchMetainfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:metainfo {appName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for any available app updates.';

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
     * @param \yuki\Scrapers\Metainfo $metainfo
     * @return mixed
     */
    public function handle(Metainfo $metainfo)
    {
        $output = $metainfo->build($this->argument('appName'))
                           ->run()
                           ->output();
        dump($output);
    }
}
