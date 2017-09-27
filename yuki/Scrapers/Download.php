<?php

namespace yuki\Scrapers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use yuki\Exceptions\PackageExistsException;
use yuki\Repositories\AvailableAppsRepository;
use yuki\Exceptions\FailedToVerifyHashException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Download extends Scraper
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
     * AvailableApps Repository
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
     * @return $this
     * @throws \yuki\Exceptions\PackageExistsException
     */
    public function build($packageName, $versionCode, $hash)
    {
        $this->packageName = $packageName;
        $this->versionCode = $versionCode;
        $this->hash = $hash;

        if ($this->checkIfFileExists()) {
            throw new PackageExistsException('File ' . $this->buildApkFilename() . ' already exists.');
        }

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

        $this->verifyFileIntegrity($this->packageName, $this->hash);

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
     *
     */
    public function store()
    {
        $this->availableApps->create($this->packageName);
        Log::info('Finished download of ' . $this->packageName);
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
        return config('googleplay.apk_path') . DIRECTORY_SEPARATOR . $this->packageName;
    }

    /**
     * @return bool
     */
    protected function checkIfFileExists()
    {
        return Storage::exists($this->buildApkFilename());
    }

    /**
     * @param $packageName
     * @param $reportedHash
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function verifyFileIntegrity($packageName, $reportedHash)
    {
        $packagePath = apkDirectory($packageName) . DIRECTORY_SEPARATOR . $this->buildApkFilename();
        $hashOfLocalPackage = sha1_file($packagePath);

        if ($hashOfLocalPackage !== $reportedHash) {
            throw new FailedToVerifyHashException(
                "Failed to verify hash for $packageName.",
                0,
                null,
                $packageName,
                $hashOfLocalPackage,
                $reportedHash
            );
        }

        Log::info("Downloaded $packageName. Verified hash SHA1: $reportedHash");
    }

    protected function buildProcess($packageName, $filename, $directory)
    {
        $command = sprintf('gp-download %s > %s', $packageName, $filename);

        return new Process($command, $directory);
    }
}
