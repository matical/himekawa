<?php

namespace yuki\Scrapers;

use yuki\Process\Supervisor;
use yuki\Foundation\ZipFactory;
use Illuminate\Support\Facades\Log;
use yuki\Scrapers\Store\SplitStoreApp;
use yuki\Repositories\AvailableAppsRepository;
use Symfony\Component\Process\Exception\ProcessTimedOutException;

class DownloadSplits
{
    protected ZipFactory $zipper;

    protected SplitStoreApp $storeApp;

    protected AvailableAppsRepository $availableAppsRepository;

    public function __construct(AvailableAppsRepository $availableAppsRepository, ZipFactory $zipper)
    {
        $this->availableAppsRepository = $availableAppsRepository;
        $this->zipper = $zipper;
    }

    public function withApp(SplitStoreApp $storeApp)
    {
        $this->storeApp = $storeApp;

        return $this;
    }

    public function fetch()
    {
        try {
            $this->buildSupervisor()->execute();
        } catch (ProcessTimedOutException $exception) {
            Log::warning("Failed to download {$this->storeApp->getPackageName()}. Process timed out.");
        }

        return $this->buildZip();
    }

    /**
     * @return string archive of the combined APK
     * @throws \Exception
     */
    protected function buildZip(): string
    {
        return $this->zipper->create($this->storeApp);
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
            env('COMMANDS_GPDOWNLOADSPLIT'),
            $this->storeApp->getPackageName(),
            $this->storeApp->getDownloadLocation(), // ./download-both-splits will auto append 'base_' and 'split_'
        ];
    }
}
