<?php

namespace yuki;

use Illuminate\Support\Facades\Cache;

class Version
{
    public function revision()
    {
        return Cache::remember('version:current-revision', 60, function () {
            return trim(shell_exec('git rev-list --count HEAD'));
        });
    }

    public function hash()
    {
        return Cache::remember('version:current-hash', 60, function () {
            return trim(shell_exec('git rev-parse --short HEAD'));
        });
    }

    public function isClean()
    {
        return Cache::remember('version:current-state', 60, function () {
            $porcelain = shell_exec('git status --porcelain');

            if ($porcelain) {
                return false;
            }

            return true;
        });
    }
}
