<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Process;

class Metainfo extends Scraper
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
