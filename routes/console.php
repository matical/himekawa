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
    $this->table(['Announcement'], [$announcement->get()->toArray()]);
})->describe('List announcements');
