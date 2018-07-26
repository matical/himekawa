<?php

namespace yuki\Slack\Handlers;

use yuki\Slack\Jobs\ApkUpdate;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Handlers\SignatureHandler;

class CheckForUpdates extends SignatureHandler
{
    protected $signature = '* update';

    protected $description = 'Lists APK related stats';

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
