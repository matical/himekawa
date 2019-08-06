<?php

namespace himekawa\Console\Commands;

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
    protected $description = 'Command description';

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
        $this->createEnvIfNeeded();

        $this->task('Migrate DB', function () {
            return 0 === $this->callSilent('migrate');
        });

        $this->task('Import APK watchlist', function () {
            $this->callSilent('apk:import');
        });


    }

    public function createEnvIfNeeded()
    {
        if (file_exists('.env')) {
            return;
        }

        copy('.env.example', '.env');
        $this->callSilent('key:generate');
        $this->line('New <info>.env</info> file created.');
    }

    /**
     * @param string        $title
     * @param \Closure|null $task
     * @param string        $loadingText
     * @return bool
     * @throws \Exception
     */
    protected function task(string $title, $task = null, $loadingText = 'loading...')
    {
        $this->output->write("$title: <comment>{$loadingText}</comment>");

        if ($task === null) {
            $result = true;
        } else {
            try {
                $result = $task() === false ? false : true;
            } catch (\Exception $taskException) {
                $result = false;
            }
        }

        if ($this->output->isDecorated()) { // Determines if we can use escape sequences
            $this->output->write("\x0D");
            $this->output->write("\x1B[2K");
        } else {
            $this->output->writeln(''); // Make sure we first close the previous line
        }

        $this->output->writeln(
            "$title: " . ($result ? '<info>✔</info>' : '<error>failed</error>')
        );

        if (isset($taskException)) {
            throw $taskException;
        }

        return $result;
    }
}
