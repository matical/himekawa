<?php

namespace himekawa\Console\Commands;

use yuki\Facades\LastRun;
use yuki\Scrapers\Download;
use yuki\Process\Supervisor;
use Illuminate\Console\Command;
use yuki\Scrapers\UpdateManager;
use Illuminate\Support\Facades\Log;
use yuki\Exceptions\PackageException;
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
     * @var \yuki\Scrapers\UpdateManager
     */
    protected $update;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\Download                    $download
     * @param \yuki\Scrapers\UpdateManager               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableApps
     */
    public function __construct(Download $download, UpdateManager $update, AvailableAppsRepository $availableApps)
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
        Log::info('Running APK scheduler');

        retry(3, function () {
            $this->fetchAndSetToken();
        }, 5000);

        $this->appMetadata = $this->update->allApkMetadata($this->output->isVerbose());
        // Queue up the apps that have updates pending
        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        if ($this->option('dry-run')) {
            dump($this->appsRequiringUpdates);
            exit(1);
        }

        LastRun::markLastCheck();

        if (empty($this->appsRequiringUpdates)) {
            $this->info("There's no apps that require updates.");
            Log::info('No apps require updates');

            return;
        }

        $this->line(
            sprintf('Found <comment>%s</comment> app(s) available for update', count($this->appsRequiringUpdates))
        );
        Log:::info('Updates found.', $this->appsRequiringUpdates);

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
                $appsUpdated[] = $this->downloadApp($app);
            } catch (PackageException $exception) {
                $bar->setMessage("An APK already exists for {$exception->package}.");
            } catch (ProcessFailedException $exception) {
                // No need to log
                $this->warn("Failed to download {$app->packageName}");
            }
        }

        $bar->finish();

        if (! empty($appsUpdated)) {
            event(new AppsUpdated($appsUpdated, $this->option('no-notifications')));
        }
    }

    protected function fetchAndSetToken()
    {
        $token = Supervisor::command('./gp-cli/bin/get-token')
                           ->setSerializer(fn ($output) => trim($output))
                           ->execute()
                           ->getOutput();

        $this->line("Setting Token: <info>$token</info>", null, OutputInterface::VERBOSITY_VERBOSE);
        putenv("GOOGLE_AUTHTOKEN=$token");
    }

    /**
     * @param $app
     * @return \himekawa\AvailableApp
     * @throws \yuki\Exceptions\PackageException
     */
    protected function downloadApp($app)
    {
        return $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                              ->run()
                              ->store();
    }
}
