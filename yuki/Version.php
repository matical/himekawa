<?php

namespace yuki;

use Closure;
use Illuminate\Support\Facades\Cache;

class Version
{
    /**
     * @var int
     */
    protected $minutes = 60;

    /** @var string */
    protected $currentVersionKey = 'version:describe';

    public function prettyVersion(): ?string
    {
        return $this->cached($this->currentVersionKey, function () {
            return $this->exec('git describe --tags');
        });
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
                    ->remember($key, config('googleplay.metainfo_cache_ttl'), function () use ($callback) {
                        return $callback();
                    });
    }
}
