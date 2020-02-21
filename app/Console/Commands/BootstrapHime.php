<?php

namespace himekawa\Console\Commands;

use Illuminate\Console\Command;
use yuki\Command\Installation\Config;

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
     * @var \yuki\Command\Installation\Config
     */
    protected $config;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Command\Installation\Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct();
        $this->config = $config;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
//        if (base_path(file_exists('.env'))) {
//            $this->warn('An .env file already exists! (Remove it if you wish to re-run this setup)');
//
//            return;
//        }

        $this->createDatabase();
        $this->finalizeEnvCreation();

        $this->call('migrate');

        if ($this->confirm('Import APKs from resources/apps.json now?')) {
            $this->callSilent('apk:import');
        }
    }

    public function createDatabase()
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
        $this->config->merge(10, 'DB_CONNECTION', $driver)
                     ->merge(11, 'DB_DATABASE', $this->ask('DB Name', 'himekawa_dev'))
                     ->merge(12, 'DB_HOST', $this->ask('Host', '127.0.0.1'))
                     ->merge(13, 'DB_PORT', $this->ask('Port', '5432'))
                     ->merge(14, 'DB_USERNAME', $this->ask('Username', 'root'))
                     ->merge(15, 'DB_PASSWORD', $this->secret('Password', ''));

        $this->config->changes()
                     ->except(14) // DB_PASSWORD
                     ->each(fn ($change) => $this->line($change));

        $this->exitWhenInDoubt('Does this look right?');

        return $this->config->commit()
                            ->save('.env');
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
            $this->warn('Aborted.');
            exit(1);
        }
    }
}
