<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use yuki\Parsers\Badging;

class ParseApk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:parse {appName}';

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
     * @param \yuki\Parsers\Badging $badging
     * @return mixed
     */
    public function handle(Badging $badging)
    {
        $packageName = $this->argument('appName');

        $output = $badging->package($packageName)
                          ->getPackage();
        dd($output);
    }
}
