<?php

namespace yuki\Process;

use Closure;
use ksmz\json\Json;
use RuntimeException;
use Symfony\Component\Process\Process;

class Supervisor
{
    protected Process $process;

    /** @var mixed */
    protected $output;

    /** @var \Closure */
    protected $serializer;

    protected bool $hasOutput = true;

    protected bool $disableSerializer = false;

    protected int $retryAttempts = 0;

    protected int $retryDelay = 0;

    /**
     * @param array       $command
     * @param string|null $directory
     *
     * @throws \Symfony\Component\Process\Exception\RuntimeException
     */
    public function __construct($command, $directory = null)
    {
        $this->process = new Process($command, $directory);
    }

    /**
     * @param string|array $command
     * @param string|null  $directory
     * @return static
     */
    public static function command($command, $directory = null)
    {
        return new static((array) $command, $directory);
    }

    /**
     * Run the command and return results immediately.
     *
     * @param string|array $command
     * @param string|null  $directory
     * @return string
     *
     * @throws \Exception
     */
    public static function runNow($command, $directory = null)
    {
        return static::command($command, $directory)
                     ->dontSerialize()
                     ->execute()
                     ->getOutput();
    }

    /**
     * @return self
     *
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException
     * @throws \Exception
     */
    public function execute()
    {
        if ($this->retryAttempts > 0) {
            retry($this->retryAttempts, fn () => $this->process->mustRun(), $this->retryDelay);
        } else {
            $this->process->mustRun();
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
     * @return mixed
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
     * Disable output serialization.
     *
     * @return self
     */
    public function dontSerialize()
    {
        $this->disableSerializer = true;

        return $this;
    }

    /**
     * @param int $attempts
     * @param int $delay
     * @return self
     */
    public function retryFor($attempts = 1, $delay = 1000)
    {
        $this->retryAttempts = $attempts;
        $this->retryDelay = $delay;

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
        if ($this->disableSerializer) {
            return $output;
        }

        if ($this->serializer) {
            return ($this->serializer)($output);
        }

        return Json::decode($output);
    }
}
