<?php

use yuki\Announce\Announcement;

Artisan::command('announce', function (Announcement $announcement) {
    $announcement->broadcast($this->ask('Announcement'));
    $this->info('Announcement added.');
})->describe('Creates an announcement');

Artisan::command('announce:clear', function (Announcement $announcement) {
    $announcement->clear();
    $this->info('Announcements cleared.');
})->describe('Clear all announcements');

Artisan::command('announce:list', function (Announcement $announcement) {
    $secondsTo = Redis::ttl(config('cache.prefix') . ':' . config('himekawa.announcement.key'));
    if ($secondsTo === -2) {
        $this->info('No announcements active.');

        return;
    }

    $expiry = now()->addSeconds($secondsTo)->diffForHumans();

    $this->line("Announcement(s) will expire in <info>$expiry</info>.");
    $this->line('');

    foreach ($announcement->get() as $announce) {
        $this->line("> <info>$announce</info>");
    }
})->describe('List announcements');
