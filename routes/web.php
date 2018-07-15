<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::get('notice', 'AvailableAppController@notices')
     ->name('index.cd');
Route::get('faq', 'AvailableAppController@faq')
     ->name('index.faq');
Route::redirect('l', 's');
Route::prefix('s')->group(function () {
    Route::get('/', 'ShortLinkController@index')
         ->name('links.index');
    Route::get('{shortCode}', 'ShortLinkController@show')
         ->name('links.show');
});

Route::get('{shortCode}', 'ShortLinkController@show')
     ->name('links.show');

Route::rssFeeds();
