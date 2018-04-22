<?php

Route::prefix('apps')->group(function () {
    Route::get('/', 'AvailableAppController@index')
         ->name('api.apps.index');
    Route::get('list', 'AvailableAppController@list')
         ->name('api.apps.list');
    Route::get('{slug}', 'AvailableAppController@show')
         ->name('api.apps.show');
});
