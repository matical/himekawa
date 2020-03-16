<?php

namespace yuki\Scrapers;

use yuki\Facades\Apk;
use himekawa\AvailableApp;
use yuki\Process\Supervisor;
use Illuminate\Support\Facades\Log;
use yuki\Exceptions\PackageException;
use Illuminate\Support\Facades\Storage;
use yuki\Repositories\AvailableAppsRepository;
use yuki\Exceptions\FailedToVerifyHashException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class Download
{
    /**
     * @var \yuki\Process\Supervisor
     */
    protected $supervisor;

    /**
     * Package identifier of the app to be downloaded.
     *
     * @var string
     */
    protected $packageName;

    /**
     * Version code of the app to be downloaded.
     *
     * @var int
     */
    protected $versionCode;

    /**
     * SHA1 hash of the app to be downloaded.
     *
     * @var string
     */
    protected $hash;

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;

    /**
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(AvailableAppsRepository $availableAppsRepository)
    {
        $this->availableApps = $availableAppsRepository;
    }

    /**
     * @param string $packageName
     * @param int    $versionCode
     * @param string $hash
     * @return self
     *
     * @throws \yuki\Exceptions\PackageException
     */
    public function build($packageName, $versionCode, $hash)
    {
        $this->packageName = $packageName;
        $this->versionCode = $versionCode;
        $this->hash = $hash;

        if ($this->fileAlreadyExists()) {
            try {
                // If there's an empty file from a previous failed download
                $this->verifyFileIntegrity($this->packageName, $this->hash);
            } catch (FailedToVerifyHashException $exception) {
                // TODO: Separate this so download can continue on the same apk:update run.
                $this->deleteDownload($packageName, $this->buildApkFilename());
            } finally {
                throw PackageException::AlreadyExists($this->packageName, $this->versionCode);
            }
        }

        // Checks if a folder with the respective package name exists already
        if (! Storage::exists($packageName)) {
            Storage::makeDirectory($packageName);
        }

        $this->supervisor = $this->buildSupervisor(
            $this->packageName,
            $this->buildApkFilename(),
            $this->buildApkDirectory()
        );

        return $this;
    }

    /**
     * @return self
     */
    public function run()
    {
        try {
            $this->supervisor->execute();

            $this->verifyFileIntegrity($this->packageName, $this->hash);
        } catch (FailedToVerifyHashException $exception) {
            $this->deleteDownload($this->packageName, $this->buildApkFilename());
        } catch (ProcessTimedOutException $exception) {
            $this->deleteDownload($this->packageName, $this->buildApkFilename());
            Log::warning("Failed to download {$this->buildApkFilename()}. Process timed out.");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function output(): string
    {
        return $this->buildApkFilename();
    }

    /**
     * @return \himekawa\AvailableApp
     */
    public function store(): AvailableApp
    {
        return tap($this->availableApps->create($this->packageName), function ($availableApp) {
            Log::info("Finished download of {$this->packageName} (r{$availableApp->version_code}-v{$availableApp->version_name})");
        });
    }

    /**
     * Build the "target" apk file.
     *
     * @return string
     */
    protected function buildApkFilename(): string
    {
        return Apk::resolveApkFilename($this->packageName, $this->versionCode);
    }

    /**
     * Build the apk directory.
     *
     * @return string
     */
    protected function buildApkDirectory(): string
    {
        return Apk::resolveApkDirectory($this->packageName);
    }

    /**
     * Check if an APK already exists at that location.
     *
     * @return bool
     */
    protected function fileAlreadyExists(): bool
    {
        return Storage::exists(
            sprintf('%s/%s', $this->packageName, $this->buildApkFilename())
        );
    }

    /**
     * Build and configure the supervisor instance.
     *
     * @param string $packageName
     * @param string $filename
     * @param string $directory
     * @return \yuki\Process\Supervisor
     */
    protected function buildSupervisor($packageName, $filename, $directory): Supervisor
    {
        $command = sprintf('%s %s > %s', config('himekawa.commands.gp-download'), $packageName, $filename);

        return tap(new Supervisor($command, $directory), function (Supervisor $supervisor) {
            $supervisor->setTimeout(config('googleplay.download_timeout'));
            $supervisor->setOutputAvailability(false);
        });
    }

    /**
     * @param string $packageName
     * @param string $expectedHash
     *
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function verifyFileIntegrity($packageName, $expectedHash): void
    {
        $packagePath = apkDirectory($packageName, $this->versionCode);
        $hashOfLocalPackage = sha1_file($packagePath);

        if ($hashOfLocalPackage !== $expectedHash) {
            throw new FailedToVerifyHashException($packageName, $this->buildApkFilename(), $hashOfLocalPackage, $expectedHash);
        }

        Log::info("Verified hash for $packageName (SHA1: $expectedHash)");
    }

    /**
     * @param string $packageName
     * @param string $apkFilename
     */
    protected function deleteDownload($packageName, $apkFilename): void
    {
        if (Storage::delete("$packageName/$apkFilename")) {
            Log::info("Deleted faulty download: $apkFilename");
        }
    }
}
