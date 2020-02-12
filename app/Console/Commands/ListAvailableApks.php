<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use himekawa\AvailableApp;
use yuki\Command\Apk\Stats;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ListAvailableApks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:list {--all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available APKs';

    /**
     * @var \yuki\Command\Apk\Stats
     */
    protected $stats;

    /**
     * Create a new command instance.
     *
     * @param \yuki\Command\Apk\Stats $stats
     */
    public function __construct(Stats $stats)
    {
        parent::__construct();
        $this->stats = $stats;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apps = AvailableApp::latest()
                            ->get();

        $this->option('all') ? $this->detailed($apps) : $this->summarized();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection $apps
     */
    protected function detailed($apps)
    {
        $grouped = $apps->groupBy('app_id');

        $grouped->each(function (Collection $group) {
            $this->line($this->formatTitle($group->first()->app_id));

            $this->table(['VC', 'Version', 'Size', 'Hash (SHA1)', 'Downloaded On'], $this->filter($group));

            $this->output->newLine();
        });
    }

    protected function summarized()
    {
        $this->briefStats();

        $firstOfEachApp = collect();
        foreach (WatchedApp::all() as $watched) {
            if ($watched->latestApp() === null) {
                continue;
            }

            $firstOfEachApp[$watched->package_name] = $watched->latestApp();
        }

        $this->line('DB');
        $this->line('--');

        $firstOfEachApp->each(function (AvailableApp $latestApp, $packageName) {
            $output = sprintf(
                '<fg=magenta>%s</>: %s vc%s (%s)',
                $packageName,
                $latestApp->version_name,
                $latestApp->version_code,
                $latestApp->created_at->diffForHumans()
            );

            $this->line($output);
        });
    }

    protected function briefStats()
    {
        $this->line('Local Directory');
        $this->line('---------------');

        $output = sprintf(
            'Size: <info>%s</info> | Files Available: <info>%s</info>',
            $this->stats->totalSizeOfDirectory(),
            $this->stats->totalAmountOfFiles()
        );

        $this->line($output);
        $this->output->newLine();
    }

    /**
     * @param $appId
     * @return string
     */
    protected function formatTitle($appId)
    {
        $format = '%s [%s]';
        $watchedApp = WatchedApp::find($appId);

        return sprintf($format, $watchedApp->name, $watchedApp->package_name);
    }

    /**
     * @param \Illuminate\Support\Collection $apps
     * @return \Illuminate\Support\Collection
     */
    protected function filter(Collection $apps)
    {
        return $apps->map(fn ($apk) => $this->only($apk));
    }

    /**
     * @param \himekawa\AvailableApp $apk
     * @return array
     */
    protected function only(AvailableApp $apk)
    {
        return $apk->only(['version_code', 'version_name', 'human_bytes', 'hash', 'created_at']);
    }
}
