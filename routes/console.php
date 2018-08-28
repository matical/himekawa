<?php

use yuki\Announce\Announcement;
use yuki\Command\TerminalEditorBuffer;

Artisan::command('announce', function (Announcement $announcement, TerminalEditorBuffer $buffer) {
    $output = $announcement->available()
        ? $buffer->initial($announcement->get())
                 ->prompt()
                 ->getOutput()
        : $buffer->prompt()
                 ->getOutput();

    if ($output === $announcement->get()) {
        $this->info('No changes detected.');

        return;
    }

    $announcement->broadcast($output);
    $this->info('Announcement added.');
})->describe('Creates an announcement');

Artisan::command('announce:clear', function (Announcement $announcement) {
    $announcement->clear();
    $this->info('Announcements cleared.');
})->describe('Clear all announcements');
