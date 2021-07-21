<?php

Route::group(['prefix'=> 'dashboard', 'middleware' => ['web','check.auth'], 'namespace' => 'Modules\Dashboard\Controllers'], function()
{
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/get-data', 'DashboardController@getDashboardData');

});
