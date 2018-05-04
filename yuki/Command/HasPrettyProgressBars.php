<?php

namespace yuki\Command;

use Symfony\Component\Console\Helper\ProgressBar;

trait HasPrettyProgressBars
{
    /**
     * Create a new prettified progress bar.
     *
     * @param int|mixed $count
     * @return ProgressBar
     */
    protected function newProgressBar($count): ProgressBar
    {
        if (! is_int($count)) {
            $count = count($count);
        }

        return tap($this->output->createProgressBar($count), function (ProgressBar $bar) {
            $bar->setBarCharacter('<fg=green>=</>');
            $bar->setEmptyBarCharacter('<fg=red>=</>');
            $bar->setProgressCharacter('<fg=green>></>');
            $bar->setFormat("<fg=white;bg=cyan> %message:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n🏁  %estimated:-20s%  %memory:20s%");
        });
    }
}
