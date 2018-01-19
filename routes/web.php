<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::get('/about', 'HomeController@about')
     ->name('index.about');
Route::get('/faq', 'HomeController@faq')
     ->name('index.faq');
Route::prefix('l')->group(function () {
    Route::get('/', 'ShortLinkController@index')
         ->name('links.index');
    Route::get('{shortCode}', 'ShortLinkController@show');
});
