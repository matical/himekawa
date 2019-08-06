<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use yuki\Facades\LastRun;
use Illuminate\Support\Str;
use yuki\Scrapers\Download;
use Illuminate\Console\Command;
use yuki\Exceptions\PackageException;
use Symfony\Component\Process\Process;
use yuki\Command\HasPrettyProgressBars;
use himekawa\Events\Scheduler\AppsUpdated;
use yuki\Repositories\AvailableAppsRepository;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CheckForAppUpdates extends Command
{
    use HasPrettyProgressBars;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "apk:update
                            {--N|no-notifications : Ensure no notifications will be dispatched }
                            {--dry-run : Don't download anything, only list available updates }";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for available updates.';

    /**
     * @var array An array containing all app metadata, indexed by the package name
     */
    protected $appMetadata = [];

    /**
     * Array of apps with available updates.
     *
     * @var array
     */
    protected $appsRequiringUpdates = [];

    /**
     * @var \yuki\Scrapers\Download
     */
    protected $download;

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;

    /**
     * @var \yuki\Update
     */
    protected $update;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\Download                    $download
     * @param \yuki\Update                               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableApps
     */
    public function __construct(Download $download, Update $update, AvailableAppsRepository $availableApps)
    {
        parent::__construct();

        $this->download = $download;
        $this->update = $update;
        $this->availableApps = $availableApps;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $this->line('Checking for updates...');
        info('Running APK scheduler');

        retry(3, function () {
            $this->fetchAndSetToken();
        }, 5000);

        retry(2, function () {
            $this->appMetadata = $this->update->allApkMetadata();
        }, 10000);

        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        if ($this->option('dry-run')) {
            dump($this->appsRequiringUpdates);
            exit(1);
        }

        LastRun::markLastCheck();

        if (empty($this->appsRequiringUpdates)) {
            $this->info("There's no apps that require updates.");
            info('No apps require updates');

            return;
        }

        $this->line(
            sprintf('Found <comment>%s</comment> app(s) available for update', count($this->appsRequiringUpdates))
        );
        info('Updates found.', $this->appsRequiringUpdates);

        $this->downloadRequiredUpdates($this->appsRequiringUpdates);
    }

    /**
     * Download required updates.
     *
     * @param $appsRequiringUpdates
     */
    protected function downloadRequiredUpdates(array $appsRequiringUpdates)
    {
        $appsUpdated = [];
        $bar = $this->newProgressBar($appsRequiringUpdates);

        foreach ($appsRequiringUpdates as $app) {
            $bar->setMessage("Downloading {$app->packageName}");
            $bar->advance();

            try {
                $appsUpdated[] = $this->getAvailableApp($app);
            } catch (PackageException $exception) {
                $bar->setMessage("An APK already exists for {$exception->package}.");
            } catch (ProcessFailedException $exception) {
                if (Str::contains($exception->getMessage(), 'DF-DFERH-01')) {
                    $this->info('Refreshing token...');
                    $this->fetchAndSetToken();

                    $appsUpdated[] = $this->getAvailableApp($app);
                }
            }
        }

        $bar->finish();

        if (! empty($appsUpdated)) {
            event(new AppsUpdated($appsUpdated, $this->option('no-notifications')));
        }
    }

    protected function fetchAndSetToken()
    {
        $process = new Process(['./gp-cli/bin/get-token']);
        $process->mustRun();
        $token = trim($process->getOutput());
        $this->line("Setting Token: <info>$token</info>", null, OutputInterface::VERBOSITY_VERBOSE);
        putenv("GOOGLE_AUTHTOKEN=$token");
    }

    /**
     * @param $app
     * @return \himekawa\AvailableApp
     */
    protected function getAvailableApp($app)
    {
        return $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                              ->run()
                              ->store();
    }
}
