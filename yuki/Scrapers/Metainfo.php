<?php

namespace yuki\Scrapers;

use yuki\Process\Supervisor;

class Metainfo
{
    /**
     * @param string $packageName
     * @return \stdClass
     * @throws \Exception
     */
    public function fetch(string $packageName)
    {
        return Supervisor::command($this->getCommand($packageName))
                         ->execute()
                         ->getOutput();
    }

    protected function getCommand(string $packageName): array
    {
        return [
            config('himekawa.commands.gp-download-meta'),
            $packageName,
        ];
    }
}
