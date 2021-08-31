<?php

Route::group(['prefix' => 'user', 'middleware' => ['web', 'check.auth'], 'namespace' => 'Modules\User\controllers'], function()
{
    Route::get('/list', 'UserController@index')->name('user')->middleware('check.permission');
    Route::get('/view-details/{id}', 'UserController@show')->name('user.show')->middleware('check.permission');
    Route::get('/create', 'UserController@create')->name('user.create')->middleware('check.permission');
    Route::post('/store', 'UserController@store')->name('user.store')->middleware('check.permission:user.create');
    Route::get('/edit/{id}', 'UserController@edit')->name('user.edit')->middleware('check.permission');
    Route::post('/update/{id}', 'UserController@update')->name('user.update')->middleware('check.permission:user.edit');
    Route::post('/update-profile', 'UserController@updateProfile')->name('user.profile.update');
    Route::post('/changePassword', 'UserController@changePassword')->name('user.change-password');
    Route::any('/destroy/{id}', 'UserController@destroy')->name('user.destroy')->middleware('check.permission');

    Route::any('/reset-password', 'UserController@resetPassword')->name('user.reset_password');

    Route::any('/user-validate-email', 'UserController@validateEmail')->name('user.validate.email');
    Route::any('/user-validate-employee-code', 'UserController@validateEmployeeCode')->name('user.validate.emp_code');

    Route::any('/user-logout-force/{id}', 'UserController@userLogoutForce')->name('user.logout.force');
});

Route::group(['middleware' => ['web', 'check.auth'], 'namespace' => 'Modules\User\controllers'], function()
{
    Route::get('/my-profile', 'UserController@profile')->name('user.profile');
});

