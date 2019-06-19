<?php

namespace yuki\Scrapers;

use yuki\Facades\Apk;
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
     * AvailableApps Repository.
     *
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
                $this->cleanupFailedDownload($packageName, $this->buildApkFilename());
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
            $this->cleanupFailedDownload($this->packageName, $this->buildApkFilename());
        } catch (ProcessTimedOutException $exception) {
            $this->cleanupFailedDownload($this->packageName, $this->buildApkFilename());
            Log::warning("Failed to download {$this->buildApkFilename()}. Process timed out.");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function output()
    {
        return $this->buildApkFilename();
    }

    /**
     * @return \himekawa\AvailableApp
     */
    public function store()
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
    protected function buildApkFilename()
    {
        return sprintf('%s.%s.apk', $this->packageName, $this->versionCode);
    }

    /**
     * Build the apk directory.
     *
     * @return string
     */
    protected function buildApkDirectory()
    {
        return Apk::resolveApkDirectory($this->packageName);
    }

    /**
     * @return bool
     */
    protected function fileAlreadyExists()
    {
        return Storage::exists(
            sprintf('%s/%s', $this->packageName, $this->buildApkFilename())
        );
    }

    /**
     * @param $packageName
     * @param $filename
     * @param $directory
     * @return \yuki\Process\Supervisor
     */
    protected function buildSupervisor($packageName, $filename, $directory)
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
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function verifyFileIntegrity($packageName, $expectedHash)
    {
        $packagePath = apkDirectory($packageName, $this->versionCode);
        $hashOfLocalPackage = sha1_file($packagePath);

        if ($hashOfLocalPackage !== $expectedHash) {
            throw new FailedToVerifyHashException($packageName, $this->buildApkFilename(), $hashOfLocalPackage, $expectedHash);
        }

        Log::info("Verified hash for $packageName (SHA1: $expectedHash)");
    }

    /**
     * @param string $package
     * @param string $apkFilename
     */
    protected function cleanupFailedDownload($package, $apkFilename)
    {
        if (Storage::delete("$package/$apkFilename")) {
            Log::info("Deleted faulty download: $apkFilename");
        }
    }
}
