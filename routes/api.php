<?php

Route::get('apps/list', 'Api\AvailableAppController@list');

Route::resource('apps', 'Api\AvailableAppController')->only([
    'index',
    'show',
]);
