<?php

namespace yuki\Parsers;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Badging
{
    /**
     * @var array
     */
    protected $package = [];

    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * @var string
     */
    protected $dumpOutput;

    /**
     * @var \yuki\Parsers\Parser
     */
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $packageName
     * @param $package
     * @return $this
     */
    public function package($packageName, $package)
    {
        $this->process = $this->createNewProcess($packageName, $package);
        $this->run();
        $this->package = $this->parser->parse($this->dumpOutput);

        return $this;
    }

    /**
     * Return package info.
     *
     * @return array
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * @return string
     */
    public function getRawBadging()
    {
        return $this->dumpOutput;
    }

    /**
     * @param $packageName
     * @param $package
     * @return \Symfony\Component\Process\Process
     */
    protected function createNewProcess($packageName, $package)
    {
        return new Process("aapt dump badging $package", apkDirectory($packageName));
    }

    /**
     * @return $this
     */
    protected function run()
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        $this->dumpOutput = $this->process->getOutput();

        return $this;
    }
}
