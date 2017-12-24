<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Process;

class Metainfo extends AbstractScraper
{
    protected $process;

    public function make()
    {
        return new static;
    }

    public function build($appName)
    {
        $this->process = new Process("gp-download-meta $appName");

        return $this;
    }
}
