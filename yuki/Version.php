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

    /**
     * @var string
     */
    protected $currentRevisionKey = 'version:current-revision';

    /**
     * @var string
     */
    protected $currentHashKey = 'version:current-hash';

    /**
     * @var string
     */
    protected $currentStateKey = 'version:current-state';

    /**
     * @return string|null
     */
    public function revision(): ?string
    {
        return $this->cached($this->currentRevisionKey, function () {
            return $this->exec('git rev-list --count HEAD');
        });
    }

    /**
     * @return string|null
     */
    public function hash(): ?string
    {
        return $this->cached($this->currentHashKey, function () {
            return $this->exec('git rev-parse --short HEAD');
        });
    }

    /**
     * @return bool
     */
    public function isClean(): bool
    {
        return $this->cached($this->currentStateKey, function () {
            $porcelain = $this->exec('git status --porcelain');

            return (bool) $porcelain;
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
