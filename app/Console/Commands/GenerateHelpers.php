<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GenerateHelpers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:helpers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate IDE helpers.';

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
        Artisan::call('ide-helper:generate', ['--no-interaction' => true]);
        Artisan::call('ide-helper:meta', ['--no-interaction' => true]);
        Artisan::call('ide-helper:models', ['--no-interaction' => true, '--nowrite' => true]);
    }
}
