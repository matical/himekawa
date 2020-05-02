<?php

namespace yuki\Scrapers;

use himekawa\AvailableApp;
use yuki\Process\Supervisor;
use yuki\Scrapers\Store\StoreApp;
use Illuminate\Support\Facades\Log;
use yuki\Exceptions\PackageException;
use Illuminate\Support\Facades\Storage;
use yuki\Repositories\AvailableAppsRepository;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class Download
{
    protected AvailableAppsRepository $availableApps;

    protected StoreApp $storeApp;

    public function __construct(AvailableAppsRepository $availableAppsRepository)
    {
        $this->availableApps = $availableAppsRepository;
    }

    /**
     * @param \yuki\Scrapers\Store\StoreApp $storeApp
     * @return self
     *
     * @throws \yuki\Exceptions\PackageException
     */
    public function withApp(StoreApp $storeApp)
    {
        $this->storeApp = $storeApp;

        // Since *something* exists at the path, we attempt to clean it up first
        if ($this->storeApp->exists()) {
            $this->cleanUpDirtyArtifacts();
        }

        $packageName = $this->storeApp->getPackageName();

        // Checks if a folder with the respective package name exists already
        if (! $this->storage()->exists($packageName)) {
            $this->storage()->makeDirectory($packageName);
        }

        return $this;
    }

    /**
     * @return \himekawa\AvailableApp
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @throws \UnexpectedValueException
     */
    public function fetch()
    {
        try {
            $this->buildSupervisor()->execute();
        } catch (ProcessTimedOutException $exception) {
            Log::warning("Failed to download {$this->storeApp->getPackageName()}. Process timed out.");
            $this->storeApp->deleteDownload();
            // Just bail immediately since cleanup is already done
            throw $exception;
        }

        if (! $this->storeApp->verifyHash()) {
            $this->storeApp->deleteDownload();
            Log::warning("Deleted {$this->storeApp->filename()}. Failed to verify hash.");
        }

        Log::info("Verified hash for {$this->storeApp->getPackageName()} (SHA1: {$this->storeApp->expectedHash()})");

        return $this->store();
    }

    /**
     * @return \himekawa\AvailableApp
     */
    public function store(): AvailableApp
    {
        return tap($this->availableApps->create($this->storeApp), function (AvailableApp $availableApp) {
            Log::info(sprintf(
                'Finished download of %s (r%s-v%s)',
                $this->storeApp->getPackageName(),
                $availableApp->version_code,
                $availableApp->version_name
            ));
        });
    }

    /**
     * @return bool
     * @throws \yuki\Exceptions\PackageException
     * @throws \yuki\Exceptions\PackageException
     */
    protected function cleanUpDirtyArtifacts()
    {
        // If there's an empty file from a previous failed download
        if ($this->storeApp->verifyHash()) {
            throw PackageException::AlreadyExists($this->storeApp);
        }

        // Probably a faulty download
        return $this->storeApp->deleteDownload();
    }

    /**
     * Build and configure the supervisor instance.
     *
     * @return \yuki\Process\Supervisor
     */
    protected function buildSupervisor(): Supervisor
    {
        return tap(Supervisor::command($this->getCommand()), function (Supervisor $supervisor) {
            $supervisor->setTimeout(config('googleplay.download_timeout'));
            $supervisor->setOutputAvailability(false);
        });
    }

    protected function getCommand(): array
    {
        return [
            config('himekawa.commands.gp-download'),
            $this->storeApp->getPackageName(),
            $this->storeApp->fullPath(),
        ];
    }

    /**
     * @return \Illuminate\Contracts\Filesystem\Filesystem|\Illuminate\Filesystem\FilesystemAdapter
     */
    protected function storage()
    {
        return Storage::disk('apks');
    }
}
