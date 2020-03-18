<?php

namespace yuki\Scrapers;

use yuki\Process\NodeSupervisor;

class Details
{
    /**
     * @param string $package
     * @return string
     * @throws \yuki\Exceptions\NodeException
     */
    public function run(string $package)
    {
        return NodeSupervisor::command($this->getCommand($package))
                         ->execute()
                         ->getOutput();
    }

    /**
     * Name of the command.
     *
     * @return string
     */
    protected function prefix()
    {
        return config('himekawa.commands.gp-details');
    }

    /**
     * Final command to be executed to fetch details.
     *
     * @param string $package
     * @return string
     */
    protected function getCommand(string $package): string
    {
        return "{$this->prefix()} $package";
    }
}
