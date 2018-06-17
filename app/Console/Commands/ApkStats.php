<?php

namespace himekawa\Console\Commands;

use yuki\Command\Apk\Stats;
use Illuminate\Console\Command;

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
     * @param \yuki\Command\Apk\Stats $stats
     */
    public function handle(Stats $stats)
    {
        $output = sprintf(
            'Size: <info>%s</info> | Files Available: <info>%s</info>',
            $stats->totalSizeOfDirectory(),
            $stats->totalAmountOfFiles()
        );

        $this->line($output);
    }
}
