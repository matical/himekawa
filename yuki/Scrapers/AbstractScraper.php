<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class AbstractScraper
{
    protected $process;

    protected $jsonOutput;

    public function run($asArray = false)
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $this->jsonOutput = json_decode($this->process->getOutput(), $asArray);

        return $this;
    }

    public function output()
    {
        return $this->jsonOutput;
    }
}
