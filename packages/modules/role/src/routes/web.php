<?php

Route::group(['prefix' => 'role', 'middleware' => ['web', 'check.auth'], 'namespace' => 'Modules\Role\Controllers'], function()
{
    Route::get('/list', 'RoleController@index')->name('role')->middleware('check.permission');
    Route::get('/create', 'RoleController@create')->name('role.create')->middleware('check.permission');
    Route::post('/store', 'RoleController@store')->name('role.store')->middleware('check.permission:role.create');
    Route::get('/edit/{id}', 'RoleController@edit')->name('role.edit')->middleware('check.permission');
    Route::post('/update/{id}', 'RoleController@update')->name('role.update')->middleware('check.permission:role.edit');
    Route::any('/destroy/{id}', 'RoleController@destroy')->name('role.destroy')->middleware('check.permission');

    Route::get('/role-validate-name', 'RoleController@validateName')->name('role.validate.name');

    Route::get('/manage-permissions/{id}', 'RoleController@managePermission')->name('role.manage_permission')->middleware('check.permission');
    Route::post('/manage-permissions-store/{id}', 'RoleController@managePermissionStore')->name('role.manage_permission.store')->middleware('check.permission:role.manage_permission');
});
