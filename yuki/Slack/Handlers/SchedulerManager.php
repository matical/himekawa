<?php

namespace yuki\Slack\Handlers;

use Illuminate\Support\Facades\Cache;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;

class SchedulerManager extends SignatureHandler
{
    protected $signature = '* scheduler {state?}';

    protected $description = 'Display or change scheduler state';

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        if ($state = $this->getArgument('state')) {
            $this->changeState($state);

            return $this->respondToSlack("Scheduler is now *{$this->schedulerState()}.*");
        }

        return $this->respondToSlack("Scheduler is *{$this->schedulerState()}*.");
    }

    protected function schedulerState()
    {
        return Cache::has('scheduler-disabled') ? 'disabled' : 'enabled';
    }

    protected function changeState($state)
    {
        if ($state === 'enable') {
            Cache::forget(config('himekawa.scheduler.cache_key'));
        } elseif ($state === 'disable') {
            Cache::forever(config('himekawa.scheduler.cache_key'), true);
        }
    }
}
