<?php

namespace yuki\Scrapers;

use ksmz\json\Json;
use Symfony\Component\Process\Exception\ProcessFailedException;

abstract class Scraper
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * JSON result returned from google-play-cli.
     *
     * @var \StdClass
     */
    protected $jsonOutput;

    /**
     * @var bool
     */
    protected $outputAsArray = false;

    /**
     * @return self
     */
    public function run()
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $this->decodeResult($this->process->getOutput());

        return $this;
    }

    /**
     * @return mixed
     */
    public function output()
    {
        return $this->jsonOutput;
    }

    /**
     * @param bool $shouldBeArray
     * @return self
     */
    public function asArray(bool $shouldBeArray)
    {
        $this->outputAsArray = $shouldBeArray;

        return $this;
    }

    /**
     * @param string $output
     */
    protected function decodeResult(string $output): void
    {
        $this->jsonOutput = Json::decode($output, $this->outputAsArray);
    }
}
