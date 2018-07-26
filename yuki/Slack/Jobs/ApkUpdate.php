<?php

namespace yuki\Slack\Jobs;

use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Spatie\SlashCommand\Jobs\SlashCommandResponseJob;

class ApkUpdate extends SlashCommandResponseJob
{
    public function handle()
    {
        $buffer = new BufferedOutput();
        Artisan::call('apk:update', [], $buffer);

        $this->respondToSlack('```' . $buffer->fetch() . '```')
             ->send();
    }
}
