<?php

namespace yuki\Scrapers;

use yuki\Scrapers\Store\StoreApp;
use yuki\Scrapers\Store\SplitStoreApp;
use yuki\Repositories\MetainfoRepository;

class UpdateManager
{
    /**
     * @var \yuki\Repositories\MetainfoRepository
     */
    protected $metainfo;

    /**
     * Delay in seconds.
     *
     * @var int
     */
    protected $delay;

    /**
     * @param \yuki\Repositories\MetainfoRepository $metainfo
     */
    public function __construct(MetainfoRepository $metainfo)
    {
        $this->metainfo = $metainfo;
        $this->delay = config('googleplay.delay');
    }

    public function singles($package)
    {
        $this->shouldBeDelayed();

        return StoreApp::createFromPayload(
            $this->getUpdatesForPackage($package)
        );
    }

    public function splits($package)
    {
        $this->shouldBeDelayed();

        return SplitStoreApp::createFromPayload(
            $this->getUpdatesForPackage($package)
        );
    }

    protected function getUpdatesForPackage($package)
    {
        return $this->metainfo->getPackageInfo($package);
    }

    protected function shouldBeDelayed()
    {
        sleep($this->delay);
    }
}
