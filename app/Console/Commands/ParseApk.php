<?php

namespace himekawa\Console\Commands;

use yuki\Badging\Badging;
use Illuminate\Console\Command;

class ParseApk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:parse {appName} {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parses an APK.';

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
     * @param \yuki\Badging\Badging $badging
     * @return mixed
     */
    public function handle(Badging $badging)
    {
        $packageName = $this->argument('appName');
        $package = $this->argument('file');

        $output = $badging->package($packageName, $package)
                          ->getPackage();

        dump($output);
    }
}
