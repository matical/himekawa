<?php

namespace yuki\Command;

use InvalidArgumentException;
use Symfony\Component\Console\Helper\ProgressBar;

trait HasPrettyProgressBars
{
    /**
     * Create a new prettified progress bar.
     *
     * @param int|mixed $items Either an int or something countable can be passed
     * @return ProgressBar
     */
    protected function newProgressBar($items): ProgressBar
    {
        $items = $this->normalizeCount($items);

        return tap($this->output->createProgressBar($items), function (ProgressBar $bar) {
            $bar->setBarCharacter('<fg=green>=</>');
            $bar->setEmptyBarCharacter('<fg=red>=</>');
            $bar->setProgressCharacter('<fg=green>></>');
            $bar->setFormat("<fg=white;bg=cyan> %message:-45s%</>\n%current%/%max% [%bar%] %percent:3s%%\n %estimated:-20s%  %memory:20s%");
        });
    }

    /**
     * @param $items
     * @return int
     */
    protected function normalizeCount($items)
    {
        if (is_int($items)) {
            return $items;
        }

        if (is_countable($items)) {
            return count($items);
        }

        throw new InvalidArgumentException('Expected int or countable');
    }
}
