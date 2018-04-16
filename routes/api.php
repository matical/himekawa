<?php

Route::get('apps/list', 'Api\AvailableAppsController@list');

Route::resource('apps', 'Api\AvailableAppsController')->only([
    'index',
    'show',
]);
