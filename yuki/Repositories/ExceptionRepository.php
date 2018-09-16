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
        // If "this" instead of "next" is used, if the current date is a Sunday, this will return the same date.
        // "next" will always return the following Sunday. That is, if the current date is a Sunday, it'll return
        // next week's Sunday (as expected).
        return new Carbon('next sunday');
    }
}
