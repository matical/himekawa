<?php

namespace yuki\Slack\Handlers;

use Illuminate\Support\Facades\Cache;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class SchedulerManager extends SignatureHandler
{
    /** @var string */
    protected $signature = '* scheduler {state?}';

    /** @var string */
    protected $description = 'Display or change scheduler state';

    /** @var string */
    protected $cacheKey;

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $this->cacheKey = config('himekawa.scheduler.disabled_cache_key');

        if ($state = $this->getArgument('state')) {
            $this->changeState($state);

            return $this->respondToSlack("Scheduler is now *{$this->schedulerState()}.*");
        }

        return $this->respondToSlack("Scheduler is *{$this->schedulerState()}*.");
    }

    /**
     * Return the scheduler's current state.
     *
     * @return string
     */
    protected function schedulerState(): string
    {
        return Cache::has('scheduler-disabled') ? 'disabled' : 'enabled';
    }

    /**
     * Change the scheduler's current state.
     *
     * @param string $state
     */
    protected function changeState(string $state): void
    {
        switch ($state) {
            case 'enable':
                Cache::forget(config('himekawa.scheduler.disabled_cache_key'));
                break;
            case 'disable':
                Cache::forever(config('himekawa.scheduler.disabled_cache_key'), true);
                break;
        }
    }
}
