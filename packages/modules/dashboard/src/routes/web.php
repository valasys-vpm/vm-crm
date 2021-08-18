<?php

Route::group(['prefix'=> 'dashboard', 'middleware' => ['web','check.auth'], 'namespace' => 'Modules\Dashboard\controllers'], function()
{
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/get-data', 'DashboardController@getDashboardData');

    Route::get('/v1', 'DashboardController@index_v1')->name('dashboard_v1');
    Route::any('/get-data-v1', 'DashboardController@getDashboardData_v1');

    Route::get('/v2', 'DashboardController@index_v2')->name('dashboard_v1');
    Route::any('/get-data-v2', 'DashboardController@getDashboardData_v2');

    Route::get('/v3', 'DashboardController@index_v3')->name('dashboard_v3');
    Route::any('/get-data-v3', 'DashboardController@getDashboardData_v3');

});
