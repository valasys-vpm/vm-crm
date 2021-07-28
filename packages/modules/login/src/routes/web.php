<?php

Route::group(['middleware' => ['web'], 'namespace' => 'Modules\Login\controllers'], function()
{
    Route::get('/login', 'LoginController@show')->name('login');
    Route::post('/login', 'LoginController@login')->name('login.validate');
    Route::any('/logout', 'LoginController@logout')->name('logout');
});
