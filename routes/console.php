<?php

use yuki\Foundation\Announcer;
use yuki\Command\TerminalEditorBuffer;

Artisan::command('announce', function (Announcer $announcement, TerminalEditorBuffer $buffer) {
    if ($announcement->available()) {
        $buffer->initial($announcement->get());
    }
    $output = $buffer->prompt()->getOutput();

    if ($output === $announcement->get()) {
        $this->info('No changes detected.');

        return;
    }

    $announcement->broadcast($output);
    $this->info('Announcement added.');
})->describe('Creates an announcement');

Artisan::command('announce:clear', function (Announcer $announcement) {
    $announcement->clear();
    $this->info('Announcements cleared.');
})->describe('Clear all announcements');
