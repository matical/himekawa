<?php

namespace yuki\Badging;

use yuki\Process\Supervisor;
use yuki\Scrapers\Store\StoreApp;

class Badging
{
    /**
     * @var array
     */
    protected $parsed = [];

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
     * @param \yuki\Scrapers\Store\StoreApp $storeApp
     * @return self
     */
    public function package(StoreApp $storeApp)
    {
        $this->dumpOutput = Supervisor::runNow(['aapt', 'dump', 'badging', $storeApp->fullPath()]);
        $this->parsed = $this->parser->parse($this->dumpOutput);

        return $this;
    }

    /**
     * Return package info.
     *
     * @return array
     */
    public function parsed()
    {
        return $this->parsed;
    }

    /**
     * @return string
     */
    public function getRawBadging()
    {
        return $this->dumpOutput;
    }
}
