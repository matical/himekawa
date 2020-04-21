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
        $serialized = $this->driver->serialize($this->filename);

        $this->configuration = collect($serialized)->map(function ($value, $key) {
            $value['name'] = $key;

            return $value;
        });

        return $this;
    }

    public function package($name, $split = false)
    {
        if ($split) {
            return $this->onlySplits()->firstWhere('package', $name);
        }

        return $this->onlySingle()->firstWhere('package', $name);
    }

    public function all(): Collection
    {
        return $this->configuration;
    }

    public function onlySplits(): Collection
    {
        return $this->configuration->filter(fn ($app) => array_key_exists('split', $app) && $app['split'] === true);
    }

    public function onlySingle(): Collection
    {
        // Instead of using onlySplits checks, but with reject, we want to be explicit
        // If the splits field isn't provided, we assume they're singles
        return $this->configuration->filter(fn ($app) => ! array_key_exists('split', $app) || $app['split'] === false);
    }
}
