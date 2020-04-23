<?php

namespace himekawa\Console\Commands;

use yuki\Facades\LastRun;
use yuki\Scrapers\Download;
use yuki\Process\Supervisor;
use Illuminate\Console\Command;
use yuki\Scrapers\UpdateManager;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use yuki\Exceptions\PackageException;
use yuki\Command\HasPrettyProgressBars;
use himekawa\Events\Scheduler\AppsUpdated;
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
     * @var \Illuminate\Support\Collection
     */
    protected $appsRequiringUpdates;

    /**
     * @var \yuki\Scrapers\Download
     */
    protected $download;

    /**
     * @var \yuki\Scrapers\UpdateManager
     */
    protected $update;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Scrapers\Download      $download
     * @param \yuki\Scrapers\UpdateManager $update
     */
    public function __construct(Download $download, UpdateManager $update)
    {
        parent::__construct();

        $this->download = $download;
        $this->update = $update;
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

        $this->appMetadata = $this->update->allSingleMetadata($this->output->isVerbose());
        // Queue up the apps that have updates pending
        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        if ($this->option('dry-run')) {
            $this->appsRequiringUpdates->dump();
            exit(1);
        }

        LastRun::markLastCheck();

        if ($this->appsRequiringUpdates->isEmpty()) {
            $this->info("There's no apps that require updates.");
            Log::info('No apps require updates');

            return 0;
        }

        $this->line(
            sprintf('Found <comment>%s</comment> app(s) available for update', $this->appsRequiringUpdates->count())
        );
        Log::info('Updates found.', $this->appsRequiringUpdates);

        $this->downloadRequiredUpdates($this->appsRequiringUpdates);
    }

    /**
     * Download required updates.
     *
     * @param \Illuminate\Support\Collection $appsRequiringUpdates
     * @throws \Exception
     */
    protected function downloadRequiredUpdates(Collection $appsRequiringUpdates)
    {
        $appsUpdated = [];
        $bar = $this->newProgressBar($appsRequiringUpdates);

        /** @var \yuki\Scrapers\Store\StoreApp $storeApp */
        foreach ($appsRequiringUpdates as $storeApp) {
            $bar->setMessage("Downloading {$storeApp->getPackageName()}");
            $bar->advance();

            try {
                $appsUpdated[] = $this->retrieveStoreApp($storeApp);
            } catch (PackageException $exception) {
                $bar->setMessage("An APK already exists for {$exception->package}.");
            } catch (ProcessFailedException $exception) {
                // No need to log
                $this->warn("Failed to download {$storeApp->getPackageName()}");
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
     * @param $storeApp
     * @return \himekawa\AvailableApp
     * @throws \Exception
     */
    protected function retrieveStoreApp($storeApp)
    {
        return $this->download->withApp($storeApp)
                              ->fetch();
    }
}
