<?php

namespace yuki\Scrapers;

use ksmz\json\Json;
use Symfony\Component\Process\Process;

class Metainfo
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * @param string $packageName
     * @return $this
     */
    public function package(string $packageName)
    {
        $command = sprintf('%s %s', config('himekawa.commands.gp-download-meta'), $packageName);
        $this->process = Process::fromShellCommandline($command);

        return $this;
    }

    /**
     * @return \StdClass
     */
    public function fetch()
    {
        $this->process->mustRun();

        return $this->decodeResult($this->process->getOutput());
    }

    /**
     * @param string $output
     * @return \StdClass
     */
    protected function decodeResult(string $output)
    {
        return Json::decode($output);
    }
}
