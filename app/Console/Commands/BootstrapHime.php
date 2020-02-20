<?php

namespace himekawa\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class BootstrapHime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bootstrap';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'First time setup';

    /**
     * Mapping of supported DB drivers.
     *
     * @var array
     */
    protected $availableDatabases = [
        'MariaDB/MySQL' => 'mysql',
        'Postgres'      => 'pgsql',
        'Sqlite'        => 'sqlite',
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
     * @throws \Exception
     */
    public function handle()
    {
        if (base_path(file_exists('.env'))) {
            $this->warn('An .env file already exists! (Remove it if you wish to re-run this setup)');

            return;
        }

        $this->createStandardEnv();
        $this->finalizeEnvCreation();

        $this->call('migrate');

        if ($this->confirm('Import APKs from resources/apps.json now?')) {
            $this->callSilent('apk:import');
        }
    }

    public function createStandardEnv()
    {
        // Default to sqlite
        $driver = $this->choice('Which DB would you like to use?', $this->getAvailableDatabaseDrivers(), 2);
        $this->line("Using <info>$driver</info> as DB.");
        $database = $this->availableDatabases[$driver];

        $database === 'sqlite' ? $this->initSqlite() : $this->initTraditionalDatabase($database);
    }

    protected function finalizeEnvCreation()
    {
        $this->call('key:generate');
        $this->line('New <info>.env</info> file created.');
    }

    protected function initSqlite()
    {
        $dbPath = database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            exec("touch $dbPath");
            $this->line("Created a new sqlite DB at $dbPath");
        }
    }

    /**
     * @param string $driver Name of the driver in config/database.php
     * @return false|int
     */
    protected function initTraditionalDatabase(string $driver)
    {
        $userConfig = [];

        // Start at line 10, replacing 'DB_CONNECTION' first.
        $userConfig[9] = "DB_CONNECTION={$driver}";
        $userConfig[10] = "DB_DATABASE={$this->ask('DB Name', 'himekawa_dev')}";
        $userConfig[11] = "DB_HOST={$this->ask('Host', '127.0.0.1')}";
        $userConfig[12] = "DB_PORT={$this->ask('Port', '5432')}";
        $userConfig[13] = "DB_USERNAME={$this->ask('Username', 'root')}";
        $userConfig[14] = "DB_PASSWORD={$this->secret('Password', '')}";

        foreach ($userConfig as $config) {
            // Redact password
            if (Str::contains($config, 'DB_PASSWORD')) {
                continue;
            }
            $this->line($config);
        }

        $this->exitWhenInDoubt('Does this look right?');

        $updatedConfig = array_replace($this->getConfigFile(), $userConfig);

        return file_put_contents('.env', implode("\n", $updatedConfig));
    }

    protected function getConfigFile(string $file = '.env.example')
    {
        return explode("\n", file_get_contents($file));
    }

    /**
     * @return array
     */
    protected function getAvailableDatabaseDrivers()
    {
        return array_keys($this->availableDatabases);
    }

    protected function exitWhenInDoubt(string $question)
    {
        if (! $this->confirm($question)) {
            exit(1);
        }
    }
}
