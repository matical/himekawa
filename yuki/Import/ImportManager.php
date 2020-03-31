<?php

namespace yuki\Import;

use Illuminate\Support\Collection;

class ImportManager
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected Collection $configuration;

    /**
     * @var \yuki\Import\Configurable
     */
    protected Configurable $driver;

    /**
     * @var string
     */
    protected string $filename;

    /**
     * @param \yuki\Import\Configurable $driver
     */
    public function __construct(Configurable $driver)
    {
        $this->driver = $driver;
        $this->filename = $this->driver->getFilePath();
    }

    /**
     * @return $this
     */
    public function parse(): self
    {
        $this->configuration = collect($this->driver->serialize($this->filename));

        return $this;
    }

    public function all(): Collection
    {
        return $this->configuration;
    }

    public function onlySplits(): Collection
    {
        return $this->configuration->filter(fn ($app) => array_key_exists('split', $app));
    }

    public function onlySingle(): Collection
    {
        return $this->configuration->reject(fn ($app) => array_key_exists('split', $app));
    }
}
