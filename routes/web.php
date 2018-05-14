<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::view('faq', 'frontend.faq')
     ->name('index.faq');
Route::redirect('l', 's');
Route::prefix('s')->group(function () {
    Route::get('/', 'ShortLinkController@index')
         ->name('links.index');
    Route::get('{shortCode}', 'ShortLinkController@show')
         ->name('links.show');
});

Route::rssFeeds();
