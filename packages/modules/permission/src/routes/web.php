<?php



Route::group(['prefix'=> 'module', 'middleware' => ['web','check.auth'], 'namespace' => 'Modules\Permission\controllers'], function()
{
    Route::get('/', 'PermissionController@index')->name('permission');
    Route::get('/create', 'PermissionController@create')->name('permission.create');
    Route::post('/store', 'PermissionController@store')->name('permission.store');
    Route::get('/edit/{id}', 'PermissionController@edit')->name('permission.edit');
    Route::post('/update/{id}', 'PermissionController@update')->name('permission.update');
    Route::any('/destroy/{id}', 'PermissionController@destroy')->name('permission.destroy');
});
