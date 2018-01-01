<?php

Route::get('/', 'AvailableAppController@index')
     ->name('index');
Route::get('/about', 'HomeController@about')
     ->name('index.about');
Route::get('/faq', 'HomeController@faq')
     ->name('index.faq');
