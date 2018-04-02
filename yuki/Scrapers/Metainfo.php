<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Process;

class Metainfo extends Scraper
{
    protected $process;

    public function make()
    {
        return new static();
    }

    public function build($appName)
    {
        $command = sprintf('%s %s', config('himekawa.commands.gp-download-meta'), $appName);
        $this->process = new Process($command);

        return $this;
    }
}
