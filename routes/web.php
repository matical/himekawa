<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::get('notice', 'AvailableAppController@notices')
     ->name('index.cd');
Route::get('faq', 'AvailableAppController@faq')
     ->name('index.faq');
Route::rssFeeds();

Route::redirect('l', 'short-links');
Route::redirect('s', 'short-links');

Route::get('short-links', 'ShortLinkController@index')
     ->name('links.index');
Route::get('{shortCode}', 'ShortLinkController@show')
     ->name('links.show');

