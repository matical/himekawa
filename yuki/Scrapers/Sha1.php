<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Process;

class Sha1 extends Scraper
{
    protected $process;

    public function build($appName)
    {
        $this->process = new Process("gp-get-sha1 $appName");

        return $this;
    }
}
