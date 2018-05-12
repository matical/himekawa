<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::get('/faq', 'HomeController@faq')
     ->name('index.faq');
Route::prefix('s')->group(function () {
    Route::get('/', 'ShortLinkController@index')
         ->name('links.index');
    Route::get('{shortCode}', 'ShortLinkController@show')
         ->name('links.show');
});

Route::rssFeeds();
