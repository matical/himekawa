<?php

namespace himekawa\Console\Commands;

use yuki\Scrapers\Sha1;
use Illuminate\Console\Command;

class FetchSha1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:sha1 {appName?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch app SHA1';

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
     * @param \yuki\Scrapers\Sha1 $sha1
     * @return mixed
     */
    public function handle(Sha1 $sha1)
    {
        $output = $sha1->build($this->argument('appName'))
                       ->run()
                       ->output();
        dump($output);
    }
}
