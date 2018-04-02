<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use himekawa\User;
use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use yuki\Scrapers\Versioning;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use himekawa\Notifications\ApkDownloaded;
use yuki\Repositories\AvailableAppsRepository;
use yuki\Exceptions\PackageAlreadyExistsException;

class CheckForAppUpdates extends Command
{
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
     * @var \yuki\Scrapers\Metainfo
     */
    protected $metainfo;

    /**
     * @var \yuki\Scrapers\Versioning
     */
    protected $versioning;

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
     * @param \yuki\Scrapers\Metainfo                    $metainfo
     * @param \yuki\Scrapers\Versioning                  $versioning
     * @param \yuki\Scrapers\Download                    $download
     * @param \yuki\Update                               $update
     * @param \yuki\Repositories\AvailableAppsRepository $availableApps
     */
    public function __construct(
        Metainfo $metainfo,
        Versioning $versioning,
        Download $download,
        Update $update,
        AvailableAppsRepository $availableApps
    ) {
        parent::__construct();

        $this->metainfo = $metainfo;
        $this->versioning = $versioning;
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
        $this->markScheduler();

        retry(2, function () {
            $this->appMetadata = $this->update->allApkMetadata();
        }, 500);

        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        if (! $this->appsRequiringUpdates) {
            $this->info("There's no apps that require updates.");
            info('No apps require updates');

            return;
        }

        info('Updates found.', $this->appsRequiringUpdates);

        $this->downloadRequiredUpdates();

        if ($this->appsUpdated) {
            User::find(1)->notifyNow(new ApkDownloaded($this->appsUpdated));
        }
    }

    /**
     * Download required updates.
     *
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function downloadRequiredUpdates()
    {
        foreach ($this->appsRequiringUpdates as $app) {
            $this->line("Downloading {$app->packageName}");

            try {
                $availableApp = $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                                               ->run()
                                               ->store();

                $this->appsUpdated[] = $availableApp;
            } catch (PackageAlreadyExistsException $exception) {
                $this->warn("APK already exists for {$exception->package}.");
            }
        }
    }

    protected function markScheduler()
    {
        Cache::forever('scheduler:last-run', now()->timestamp);
    }
}
