<?php

namespace yuki\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class ExceptionRepository
{
    /**
     * @var string
     */
    public $cacheKey = 'exceptions-past-week';

    public function increment()
    {
        $this->initializeIfRequired();

        Cache::increment($this->cacheKey);
    }

    public function numberOfExceptions()
    {
        return Cache::get($this->cacheKey, 0);
    }

    protected function initializeIfRequired()
    {
        if (! Cache::has($this->cacheKey)) {
            Cache::put($this->cacheKey, 0, $this->endOfThisWeek());
        }
    }

    /**
     * @return \Carbon\Carbon
     */
    protected function endOfThisWeek()
    {
        return new Carbon('this sunday');
    }
}
