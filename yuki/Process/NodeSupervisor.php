<?php

namespace yuki\Process;

use yuki\Exceptions\NodeException;
use Symfony\Component\Process\Exception\ProcessFailedException;

class NodeSupervisor extends Supervisor
{
    /**
     * @return void|\yuki\Process\Supervisor
     * @throws \yuki\Exceptions\NodeException
     */
    public function execute()
    {
        try {
            $this->process->mustRun();
        } catch (ProcessFailedException $exception) {
            throw NodeException::FailedToExecute($exception);
        }

        if ($this->hasOutput) {
            $this->output = $this->serializeOutput($this->process->getOutput());
        }

        return $this;
    }
}
