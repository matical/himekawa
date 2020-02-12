<?php

namespace yuki\Logging;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\DeduplicationHandler;
use Monolog\Handler\HandlerInterface;
use Monolog\Handler\SlackWebhookHandler;
use Monolog\Logger as Monolog;

class DeDuplicatedSlack
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug'     => Monolog::DEBUG,
        'info'      => Monolog::INFO,
        'notice'    => Monolog::NOTICE,
        'warning'   => Monolog::WARNING,
        'error'     => Monolog::ERROR,
        'critical'  => Monolog::CRITICAL,
        'alert'     => Monolog::ALERT,
        'emergency' => Monolog::EMERGENCY,
    ];

    /**
     * Create a new Log manager instance.
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Create a custom Monolog instance.
     *
     * @param array $config
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        $slackHandler = $this->prepareHandler(new SlackWebhookHandler(
            $config['url'],
            $config['channel'] ?? null,
            $config['username'] ?? 'Laravel',
            $config['attachment'] ?? true,
            $config['emoji'] ?? ':boom:',
            $config['short'] ?? false,
            $config['context'] ?? true,
            $this->level($config)
        ));

        return new Monolog($this->parseChannel($config), [
            new DeduplicationHandler($slackHandler),
        ]);
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     * @return string
     */
    protected function parseChannel(array $config)
    {
        if (! isset($config['name'])) {
            return $this->app->bound('env') ? $this->app->environment() : 'production';
        }

        return $config['name'];
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @param \Monolog\Handler\HandlerInterface $handler
     * @return \Monolog\Handler\HandlerInterface
     */
    protected function prepareHandler(HandlerInterface $handler)
    {
        return $handler->setFormatter($this->formatter());
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {
        return tap(new LineFormatter(null, null, true, true), function (LineFormatter $formatter) {
            $formatter->includeStacktraces();
        });
    }
}
