<?php

namespace himekawa\Console\Commands;

use yuki\Update;
use yuki\Scrapers\Download;
use yuki\Scrapers\Metainfo;
use yuki\Scrapers\Versioning;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use yuki\Exceptions\MissingCommandsException;
use yuki\Repositories\AvailableAppsRepository;

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
     *
     * @throws \yuki\Exceptions\PackageAlreadyExistsException
     * @throws \yuki\Exceptions\MissingCommandsException
     */
    public function handle()
    {
        $this->checkIfCommandsExist();

        $this->info('Check for updates...');
        $this->appMetadata = $this->update->allApkMetadata();
        $this->appsRequiringUpdates = $this->update->checkForUpdates($this->appMetadata);

        // If there are no apps that require updates, exit
        if (empty($this->appsRequiringUpdates)) {
            $this->info('No apps require updates.');

            return;
        }

        foreach ($this->appsRequiringUpdates as $app) {
            $this->info('Downloading ' . $app->packageName);
            $this->download->build($app->packageName, $app->versionCode, $app->sha1)
                           ->run()
                           ->store();
        }
    }

    /**
     * @throws \yuki\Exceptions\MissingCommandsException
     */
    protected function checkIfCommandsExist()
    {
        $commands = ['aapt', 'gp-download'];

        foreach ($commands as $command) {
            $process = new Process($command);
            $exitCode = $process->run();

            if ($exitCode !== 0) {
                throw new MissingCommandsException("$command cannot be found in your path. Make sure you have it installed.");
            }
        }
    }
}
