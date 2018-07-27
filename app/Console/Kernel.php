<?php

namespace himekawa\Console;

use Illuminate\Support\Facades\Cache;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->loadSchedulerSettings();

        if ($this->disabledByCache()) {
            return;
        }

        $schedule->command('apk:update')
                 ->everyFifteenMinutes()
                 ->timezone($this->settings['timezone'])
                 ->between($this->settings['start_time'], $this->settings['end_time']);

        $schedule->command('apk:prune-old')
                 ->daily();
    }

    /**
     * @return bool
     */
    protected function disabledByCache()
    {
        return Cache::has(config('himekawa.scheduler.cache_key'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Load scheduler config.
     */
    protected function loadSchedulerSettings()
    {
        $this->settings = [
            'timezone'   => config('himekawa.scheduler.timezone'),
            'start_time' => config('himekawa.scheduler.start_time'),
            'end_time'   => config('himekawa.scheduler.end_time'),
        ];
    }
}
