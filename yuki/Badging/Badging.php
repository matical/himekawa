<?php

namespace yuki\Badging;

use Symfony\Component\Process\Process;

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
     * @var \yuki\Badging\Parser
     */
    protected $parser;

    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @param $packageName
     * @param $package
     * @return self
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
     * @return self
     */
    protected function run()
    {
        $this->process->mustRun();

        $this->dumpOutput = $this->process->getOutput();

        return $this;
    }
}
