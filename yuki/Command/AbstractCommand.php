<?php

namespace yuki\Command;

use Illuminate\Console\Command;
use yuki\Repositories\WatchedAppsRepository;
use yuki\Repositories\AvailableAppsRepository;

abstract class AbstractCommand extends Command
{
    /**
     * @var \yuki\Repositories\WatchedAppsRepository
     */
    protected $watched;

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    protected $availableApps;

    /**
     * @var \yuki\Repositories\AvailableAppsRepository
     */
    private $available;

    /**
     * @param \yuki\Repositories\WatchedAppsRepository $watched
     */
    public function __construct(WatchedAppsRepository $watched, AvailableAppsRepository $available)
    {
        parent::__construct();

        $this->watched = $watched;
        $this->available = $available;
    }

    /**
     * Attempt to search the package by its slug first.
     *
     * @param string $input
     *
     * @return \himekawa\WatchedApp|string if the slug can't be found, the input will be returned
     */
    public function getPackageName($input)
    {
        $packageSlug = $this->watched->findBySlug($input);

        return $packageSlug ? $packageSlug->package_name : $input;
    }
}
