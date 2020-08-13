<?php

namespace yuki\Foundation;

use yuki\Scrapers\Store\SplitStoreApp;
use Illuminate\Support\Facades\Storage;

class ZipFactory
{
    protected Zipper $zipper;

    public function __construct(Zipper $zip)
    {
        $this->zipper = $zip;
    }

    /**
     * @param \yuki\Scrapers\Store\SplitStoreApp $splitApp
     * @return string Path to created archive
     * @throws \Exception
     */
    public function create(SplitStoreApp $splitApp)
    {
        $createdArchivePath = Storage::path($splitApp->getArchiveName());
        [$base, $split] = $splitApp->getPathToSplits();

        return $this->zipper->create($createdArchivePath)
                            ->add($this->base(), $base)
                            ->add($this->split(), $split)
                            ->build();
    }

    public function base(): string
    {
        return 'base.apk';
    }

    public function split(): string
    {
        return 'split_config.armeabi_v7a.apk';
    }
}
