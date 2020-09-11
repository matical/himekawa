<?php

namespace himekawa\Console\Commands;

use himekawa\WatchedApp;
use PhpSchool\CliMenu\CliMenu;
use Illuminate\Console\Command;
use NunoMaduro\LaravelConsoleMenu\Menu;
use PhpSchool\CliMenu\Style\CheckboxStyle;
use PhpSchool\CliMenu\MenuItem\CheckboxItem;

/**
 * @method Menu menu(string $string)
 */
class DisableWatchedApk extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apk:disable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $checked = [];

    protected Menu $menuInstance;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->menuInstance = $this->menu()->setTitle('Disable these apps?');
        $this->menuInstance->modifyCheckboxStyle(function (CheckboxStyle $style) {
            $style->setUncheckedMarker('[ ] ')->setCheckedMarker('[X] ');
        });

        $this->menuInstance->addStaticItem('Split');
        $this->addChoices(WatchedApp::split()->pluck('package_name'), 'split');

        $this->menuInstance->addLineBreak();

        $this->menuInstance->addStaticItem('Single');
        $this->addChoices(WatchedApp::single()->pluck('package_name'), 'single');

        $this->menuInstance->addLineBreak();
        $this->menuInstance->addItem('Delete', function (CliMenu $menu) {
            $menu->close();
            dump(array_filter($this->checked['single']));
            if ($this->confirm('Are you sure?')) {
                dump('deleting', $this->checked);
            }
        });

        $this->menuInstance->open();
    }

    protected function addChoices($choices, $group = null)
    {
        foreach ($choices as $choice) {
            $this->addChoice($choice, $group);
        }
    }

    protected function addChoice($text, $group = null)
    {
        $check = function (CliMenu $cliMenu) use ($group) {
            foreach ($cliMenu->getItems() as $item) {
                if (! $item instanceof CheckboxItem) {
                    continue;
                }

                if (! $group) {
                    $this->checked[$item->getText()] = $item->getChecked();
                } else {
                    $this->checked[$group][$item->getText()] = $item->getChecked();
                }
            }
        };

        $this->menuInstance
            ->addCheckboxItem($text, $check);
    }
}
