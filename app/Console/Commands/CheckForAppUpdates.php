<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use yuki\Scrapers\Versioning;
use Illuminate\Console\Command;
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
     */
    public function handle()
    {
        $this->line('Checking for updates...');
        info('Running APK scheduler');

        $this->appMetadata = $this->update->allApkMetadata();
        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        if (! $this->appsRequiringUpdates) {
            $this->info("There's no apps that require updates.");
            info('No apps require updates');

            return;
        }

        info('Updates found.', [
            'apps' => (array) $this->appsRequiringUpdates,
        ]);

        $this->downloadRequiredUpdates();
    }

    /**
     * Download required updates.
     */
    protected function downloadRequiredUpdates()
    {
        foreach ($this->appsRequiringUpdates as $app) {
            $this->line("Downloading {$app->packageName}");

            try {
                $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                               ->run()
                               ->store();
            } catch (PackageAlreadyExistsException $exception) {
                $this->warn("APK already exists for {$exception->package}.");
            }
        }
    }
}
