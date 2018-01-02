<?php

namespace yuki\Scrapers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use yuki\Repositories\AvailableAppsRepository;
use yuki\Exceptions\FailedToVerifyHashException;
use yuki\Exceptions\PackageAlreadyExistsException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class Download extends AbstractScraper
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

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
     * Download constructor.
     *
     * @param \yuki\Repositories\AvailableAppsRepository $availableAppsRepository
     */
    public function __construct(AvailableAppsRepository $availableAppsRepository)
    {
        $this->availableApps = $availableAppsRepository;
    }

    /**
     * @param $packageName
     * @param $versionCode
     * @param $hash
     *
     * @return $this
     *
     * @throws \yuki\Exceptions\PackageAlreadyExistsException
     */
    public function build($packageName, $versionCode, $hash)
    {
        $this->packageName = $packageName;
        $this->versionCode = $versionCode;
        $this->hash = $hash;

        if ($this->checkIfFileExists()) {
            $this->verifyFileIntegrity($this->packageName, $this->hash);

            throw new PackageAlreadyExistsException(
                'File ' . $this->buildApkFilename() . ' already exists.',
                0,
                null,
                $this->packageName,
                $this->versionCode
            );
        }

        // Checks if a folder with the respective package name exists already
        if (! Storage::exists($packageName)) {
            Storage::makeDirectory($packageName);
        }

        $this->process = $this->buildProcess(
            $this->packageName,
            $this->buildApkFilename(),
            $this->buildApkDirectory()
        );

        return $this;
    }

    /**
     * @param bool $asArray
     * @return $this
     */
    public function run($asArray = false)
    {
        $this->process->setTimeout(150);
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        try {
            $this->verifyFileIntegrity($this->packageName, $this->hash);
        } catch (FailedToVerifyHashException $exception) {
            $this->cleanupFailedDownload($this->packageName, $this->buildApkFilename());
            Log::warning($exception->packageFilename . ' has been discarded.');
        } catch (ProcessTimedOutException $exception) {
            $this->cleanupFailedDownload($this->packageName, $this->buildApkFilename());
            Log::warning('Failed to download ' . $this->buildApkFilename() . '. Process timed out.');
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
        return apkDirectory($this->packageName);
    }

    /**
     * @return bool
     */
    protected function checkIfFileExists()
    {
        return Storage::exists(
            sprintf('%s/%s', $this->packageName, $this->buildApkFilename())
        );
    }

    /**
     * @param $packageName
     * @param $filename
     * @param $directory
     * @return \Symfony\Component\Process\Process
     */
    protected function buildProcess($packageName, $filename, $directory)
    {
        $command = sprintf('%s %s > %s', config('himekawa.commands.gp-download'), $packageName, $filename);

        return new Process($command, $directory);
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
            throw new FailedToVerifyHashException(
                "Failed to verify hash for $packageName.",
                0,
                null,
                $packageName,
                $this->buildApkFilename(),
                $hashOfLocalPackage,
                $expectedHash
            );
        }

        Log::info("Verified hash for $packageName (SHA1: $expectedHash)");
    }

    /**
     * @param $package
     * @param $apkFilename
     */
    protected function cleanupFailedDownload($package, $apkFilename)
    {
        if (Storage::delete("$package/$apkFilename")) {
            Log::info("Deleted faulty download: $apkFilename");
        }
    }
}
