<?php

namespace yuki\Scrapers;

use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Scraper
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * JSON result returned from google-play-cli
     *
     * @var \StdClass
     */
    protected $jsonOutput;

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

        $this->jsonOutput = json_decode($this->process->getOutput(), $asArray);

        return $this;
    }

    /**
     * @return \StdClass
     */
    public function output()
    {
        return $this->jsonOutput;
    }
}
