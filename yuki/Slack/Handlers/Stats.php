<?php

namespace yuki\Slack\Handlers;

use Spatie\SlashCommand\Attachment;
use Spatie\SlashCommand\AttachmentField;
use Spatie\SlashCommand\Handlers\SignatureHandler;
use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use yuki\Command\Apk\Stats as StatsRepository;

class Stats extends SignatureHandler
{
    protected $signature = '* stats';

    protected $description = 'Lists APK related stats';

    /**
     * Handle the given request.
     *
     * @param \Spatie\SlashCommand\Request $request
     * @return \Spatie\SlashCommand\Response
     */
    public function handle(Request $request): Response
    {
        return tap($this->respondToSlack($this->formatPretext()), function (Response $response) {
            foreach ($this->stats()->summary() as $packageName => $app) {
                $attachment = Attachment::create();

                $attachment->setTitle($app['name'])
                           ->setColor('#f8858d')
                           ->setThumbUrl("https://apk.ksmz.moe/images/{$packageName}.png");

                $attachment->addFields([
                    $this->createField('Version', $app['version_name']),
                    $this->createField('Downloaded On', carbon($app['created_at'])->format('d M, H:i')),
                ]);

                $response->withAttachment($attachment);
            }
        });
    }

    /**
     * @return \yuki\Command\Apk\Stats
     */
    protected function stats()
    {
        return app(StatsRepository::class);
    }

    protected function formatPretext()
    {
        return "There are *{$this->stats()->totalAmountOfFiles()}* APKs for a total of *{$this->stats()->totalSizeOfDirectory()}*.";
    }

    protected function createField($title, $value, $short = true)
    {
        $field = AttachmentField::create($title, $value);

        return $short ? $field->displaySideBySide() : $field->doNotDisplaySideBySide();
    }
}
