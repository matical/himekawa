<?php

namespace himekawa\Console\Commands;

use himekawa\AvailableApp;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ListAvailableApks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available APKs';

    protected $fields = [
        'app_id',
        'version_code',
        'version_name',
        'size',
        'hash',
        'created_at',
        'updated_at',
    ];

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
        $apps = AvailableApp::all($this->fields);
        $grouped = $apps->groupBy('app_id');

        $grouped->each(function (Collection $group) {
            $this->line($group->first()->watchedBy->name);

            // Unload relations
            $fields = $group->map(function (AvailableApp $app) {
                return $app->only($this->fields);
            });

            $this->table($this->fields, $fields->toArray());
        });
    }
}
