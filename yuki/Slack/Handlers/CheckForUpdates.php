<?php

namespace yuki\Slack\Handlers;

use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use yuki\Slack\Jobs\ApkUpdate;

class CheckForUpdates extends SignatureHandler
{
    protected $signature = '* update';

    protected $description = 'Check for APK updates';

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     *
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        $this->dispatch(new ApkUpdate());

        return $this->respondToSlack('Running `artisan apk:update`');
    }
}
