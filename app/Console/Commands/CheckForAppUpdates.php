<?php

namespace himekawa\Console\Commands;

use Exception;
use himekawa\WatchedApp;
use yuki\Facades\LastRun;
use yuki\Scrapers\Download;
use yuki\Process\Supervisor;
use Illuminate\Console\Command;
use yuki\Scrapers\UpdateManager;
use yuki\Scrapers\Store\StoreApp;
use yuki\Scrapers\DownloadSplits;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use yuki\Exceptions\PackageException;
use yuki\Scrapers\Store\SplitStoreApp;
use yuki\Command\HasPrettyProgressBars;
use himekawa\Events\Scheduler\AppsUpdated;
use Symfony\Component\Console\Output\OutputInterface;

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

        $this->fetchAndSetToken();

        // Queue up the apps that have updates pending
        [$singleUpdates, $splitUpdates] = $this->getAvailableUpdates();

        if ($this->option('dry-run')) {
            $singleUpdates->dump();
            $splitUpdates->dump();

            return 0;
        }

        LastRun::markLastCheck();

        // TODO: count
        if ($singleUpdates->isEmpty() && $splitUpdates->isEmpty()) {
            $this->info("There's no apps that require updates.");
            Log::info('No apps require updates');

            return 0;
        }

        $this->line(
            sprintf('Found <comment>%s</comment> app(s) available for update', $appsRequiringUpdates->count())
        );
        Log::info('Updates found.', $appsRequiringUpdates);

        $this->downloadRequiredUpdates($appsRequiringUpdates);
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
            } catch (Exception $exception) {
                $this->warn("Failed to download {$storeApp->getPackageName()}");
                report($exception);
            }
        }

        $bar->finish();

        if (! empty($appsUpdated)) {
            event(new AppsUpdated($appsUpdated, $this->option('no-notifications')));
        }
    }

    /**
     * @throws \Exception
     */
    protected function fetchAndSetToken()
    {
        $token = Supervisor::command('./gp-cli/bin/get-token')
                           ->setSerializer(fn ($output) => trim($output))
                           ->retryFor(3, 3000)
                           ->execute()
                           ->getOutput();

        $this->line("Setting Token: <info>$token</info>", null, OutputInterface::VERBOSITY_VERBOSE);
        putenv("GOOGLE_AUTHTOKEN=$token");
    }

    /**
     * @param $storeApp
     * @return \himekawa\AvailableApp|string
     * @throws \yuki\Exceptions\PackageException
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     */
    protected function retrieveStoreApp($storeApp)
    {
        if ($storeApp instanceof StoreApp) {
            return $this->download->withApp($storeApp)->fetch();
        } elseif ($storeApp instanceof SplitStoreApp) {
            return app(DownloadSplits::class)->withApp($storeApp)->fetch();
        }
    }

    protected function getAvailableUpdates(): array
    {
        $single = $this->getsingles()
                       ->map(fn ($package) => $this->update->singles($package))
                       ->filter(fn ($app) => $app->canbeupdated());

        $split = $this->getsplits()
                      ->map(fn ($package) => $this->update->splits($package))
                      ->filter(fn ($app) => $app->canbeupdated());

        return [$single, $split];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getSingles(): Collection
    {
        return WatchedApp::single()->pluck('package_name');
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function getSplits(): Collection
    {
        return WatchedApp::split()->pluck('package_name');
    }
}
