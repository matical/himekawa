<?php

namespace yuki\Scrapers;

use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use yuki\Exceptions\PackageExistsException;
use yuki\Exceptions\FailedToVerifyHashException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Download extends Scraper
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * @var int
     */
    protected $versionCode;

    /**
     * @var string
     */
    protected $hash;

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

        $this->process = new Process(
            sprintf('gp-download %s > %s', $this->packageName, $this->buildApkFilename()),
            config('googleplay.apk_path')
        );

        return $this;
    }

    /**
     * @param bool $asArray
     * @return $this
     */
    public function run($asArray = false)
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $this->verifyFileIntegrity($this->buildApkFilename(), $this->hash);

        return $this;
    }

    public function output()
    {
        return $this->buildApkFilename();
    }

    /**
     * @return string
     */
    protected function buildApkFilename()
    {
        return sprintf('%s.%s.apk', $this->packageName, $this->versionCode);
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
     * @param $hash
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     */
    protected function verifyFileIntegrity($packageName, $hash)
    {
        $packagePath = config('googleplay.apk_path') . DIRECTORY_SEPARATOR . $packageName;
        $hashOfLocalPackage = sha1_file($packagePath);

        if ($hashOfLocalPackage !== $hash) {
            throw new FailedToVerifyHashException('Hash of downloaded package does not match!');
        }

        Log::info('Verified hash for ' . $packageName . '. SHA1: ' . $hash);
    }
}
