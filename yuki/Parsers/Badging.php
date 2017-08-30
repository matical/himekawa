<?php

namespace yuki\Parsers;

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
     * @param $packageName
     * @return $this
     */
    public function package($packageName)
    {
        $this->process = $this->createNewProcess($packageName);
        $this->run();
        $this->parsePackage();

        return $this;
    }

    /**
     * @return $this
     */
    public function parsePackage()
    {
        $splittedLines = explode("\n", $this->dumpOutput);
        // Fetch first line
        $delimited = explode(' ', $splittedLines[0]);
        // Get rid of 'package:'
        $sliced = array_slice($delimited, 1);

        foreach ($sliced as $slice) {
            [
                $key,
                $value,
            ] = explode('=', $slice);
            $stripped = $this->stripQuotes($value);

            $this->package[$key] = is_numeric($stripped) ? (int)$stripped : $stripped;
        }

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
     * Strip out unnecessary quotes.
     *
     * @param $quotedString
     * @return mixed
     */
    protected function stripQuotes($quotedString)
    {
        return str_replace('\'', '', $quotedString);
    }

    /**
     * @param $packageName
     * @return \Symfony\Component\Process\Process
     */
    protected function createNewProcess($packageName)
    {
        return new Process("aapt dump badging $packageName", config('googleplay.apk_path'));
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
