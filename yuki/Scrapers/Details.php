<?php

namespace yuki\Scrapers;

use yuki\Process\Supervisor;

class Details
{
    /**
     * @var \stdClass
     */
    protected $output;

    /**
     * @var \yuki\Process\Supervisor
     */
    protected $supervisor;

    /**
     * @param $package
     * @return self
     */
    public function build($package)
    {
        $this->supervisor = new Supervisor($this->formatCommand($package));

        return $this;
    }

    /**
     * @return self
     */
    public function run()
    {
        $this->output = $this->supervisor->execute()
                                         ->getOutput();

        return $this;
    }

    /**
     * @return string
     */
    public function getDetails()
    {
        return $this->output;
    }

    /**
     * @param $package
     * @return string
     */
    protected function formatCommand($package): string
    {
        return config('himekawa.commands.gp-details') . " $package";
    }
}
