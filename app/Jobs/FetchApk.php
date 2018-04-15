<?php

namespace himekawa\Jobs;

use yuki\Scrapers\Download;
use Illuminate\Bus\Queueable;
use yuki\Repositories\MetainfoRepository;
use Illuminate\Foundation\Bus\Dispatchable;

class FetchApk
{
    use Dispatchable, Queueable;

    /**
     * @var string
     */
    protected $packageName;

    /**
     * Create a new job instance.
     *
     * @param string $packageName Name of the package
     */
    public function __construct(string $packageName)
    {
        $this->packageName = $packageName;
    }

    /**
     * Execute the job.
     *
     * @param \yuki\Repositories\MetainfoRepository $metainfo
     * @param \yuki\Scrapers\Download               $download
     *
     * @throws \yuki\Exceptions\FailedToVerifyHashException
     * @throws \yuki\Exceptions\PackageAlreadyExistsException
     */
    public function handle(MetainfoRepository $metainfo, Download $download)
    {
        $metadata = $metainfo->getPackageInfo($this->packageName);
        $download->build($this->packageName, $metadata->versionCode, $metadata->sha1)
                 ->run()
                 ->output();

        $download->store();
    }
}
