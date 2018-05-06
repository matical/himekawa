<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use yuki\Scrapers\Download;
use Illuminate\Console\Command;
use yuki\Exceptions\PackageException;
use yuki\Command\HasPrettyProgressBars;
use himekawa\Events\Scheduler\AppsUpdated;
use yuki\Repositories\AvailableAppsRepository;

class CheckForAppUpdates extends Command
{
    use HasPrettyProgressBars;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:update';

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
     * @var array
     */
    protected $appsUpdated = [];

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

        retry(2, function () {
            $this->appMetadata = $this->update->allApkMetadata();
        }, 500);

        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);
        $this->markSchedulerLastCheck();

        if (empty($this->appsRequiringUpdates)) {
            $this->info("There's no apps that require updates.");
            info('No apps require updates');

            return;
        }

        info('Updates found.', $this->appsRequiringUpdates);

        $this->appsUpdated = $this->downloadRequiredUpdates($this->appsRequiringUpdates);
    }

    /**
     * Download required updates.
     *
     * @param $appsRequiringUpdates
     * @return array
     *
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function downloadRequiredUpdates(array $appsRequiringUpdates): array
    {
        $appsUpdated = [];
        $bar = $this->newProgressBar($appsRequiringUpdates);

        foreach ($appsRequiringUpdates as $app) {
            $bar->setMessage("Downloading {$app->packageName}");

            try {
                $appsUpdated[] = $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                                                ->run()
                                                ->store();
            } catch (PackageException $exception) {
                $bar->setMessage("An APK already exists for {$exception->package}.");
            }

            $bar->advance();
        }

        event(new AppsUpdated($appsUpdated));
        $bar->finish();

        return $appsUpdated;
    }

    /**
     * @return void
     */
    protected function markSchedulerLastCheck()
    {
        lastRun()->markLastCheck();
    }
}
