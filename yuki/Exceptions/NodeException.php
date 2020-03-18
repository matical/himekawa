<?php

namespace yuki\Exceptions;

use Exception;
use Symfony\Component\Process\Exception\ProcessFailedException;

class NodeException extends Exception implements ExceptionInterface
{
    /**
     * @param \Symfony\Component\Process\Exception\ProcessFailedException $previous
     * @return static
     */
    public static function FailedToExecute(ProcessFailedException $previous)
    {
        $process = $previous->getProcess();

        return new static(
            "Failed to execute {$process->getCommandLine()}\n\nError: {$process->getErrorOutput()}",
            0,
            $previous
        );
    }
}
