<?php

namespace yuki\Process;

use Closure;
use ksmz\json\Json;
use RuntimeException;
use Symfony\Component\Process\Process;

class Supervisor
{
    /** @var \Symfony\Component\Process\Process */
    protected $process;

    /** @var string */
    protected $output;

    /** @var bool */
    protected $hasOutput = true;

    /** @var \Closure */
    protected $serializer;

    /**
     * Supervisor constructor.
     *
     * @param array       $command
     * @param string|null $directory
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function __construct($command, $directory = null)
    {
        $this->process = new Process($command, $directory);
    }

    public static function command($command, $directory = null)
    {
        return new static((array) $command, $directory);
    }

    /**
     * @return \yuki\Process\Supervisor
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     */
    public function execute()
    {
        $this->process->mustRun();

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
     * @param int|float|null $timeout The timeout in seconds
     * @return self
     */
    public function setTimeout($timeout)
    {
        $this->process->setTimeout($timeout);

        return $this;
    }

    /**
     * Use a custom serializer for process output.
     *
     * @param \Closure $serializer
     * @return self
     */
    public function setSerializer(Closure $serializer)
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * Serialize the process output. Defaults to json.
     *
     * @param $output
     * @return mixed
     */
    protected function serializeOutput($output)
    {
        if ($this->serializer) {
            return ($this->serializer)($output);
        }

        return Json::decode($output);
    }
}
