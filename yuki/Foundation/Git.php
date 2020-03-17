<?php

namespace yuki\Foundation;

use Closure;
use Illuminate\Support\Facades\Cache;

class Git
{
    /** @var int */
    protected $minutes = 60;

    /** @var string */
    protected $currentVersionKey = 'version:describe';

    public function prettyVersion(): ?string
    {
        return $this->cached($this->currentVersionKey, fn () => $this->exec('git describe --tags'));
    }

    /**
     * @param $command
     * @return string
     */
    protected function exec($command): ?string
    {
        return trim(shell_exec($command));
    }

    /**
     * @param string   $key
     * @param \Closure $callback
     * @return mixed
     */
    protected function cached(string $key, Closure $callback)
    {
        return Cache::tags('metadata')
                    ->remember($key, config('googleplay.metainfo_cache_ttl'), fn () => $callback());
    }
}
