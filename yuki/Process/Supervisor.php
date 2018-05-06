<?php

namespace yuki\Process;

use RuntimeException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class Supervisor
{
    /**
     * @var \Symfony\Component\Process\Process
     */
    protected $process;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var bool
     */
    protected $hasOutput = true;

    /**
     * Supervisor constructor.
     *
     * @param string      $command
     * @param string|null $directory
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function __construct($command, $directory = null)
    {
        $this->process = new Process($command, $directory);
    }

    /**
     * @return \yuki\Process\Supervisor
     */
    public function execute()
    {
        $this->process->run();

        if (! $this->process->isSuccessful()) {
            throw new ProcessFailedException($this->process);
        }

        if ($this->hasOutput) {
            $this->output = $this->serializeOutput($this->process->getOutput());
        }

        return $this;
    }

    /**
     * @param bool $hasOutput
     * @return self
     */
    public function setOutputAvailability(bool $hasOutput)
    {
        $this->hasOutput = $hasOutput;

        return $this;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getOutput()
    {
        if ($this->hasOutput) {
            return $this->output;
        }

        throw new RuntimeException('No output is available for this command.');
    }

    /**
     * @param $output
     * @return mixed
     */
    protected function serializeOutput($output)
    {
        return json_decode($output);
    }
}
