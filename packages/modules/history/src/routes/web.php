<?php

Route::group(['prefix' => 'history', 'middleware' => ['web', 'check.auth'], 'namespace' => 'Modules\History\Controllers'], function()
{
    Route::get('/list', 'HistoryController@index')->name('history');
    Route::any('/getHistories', 'HistoryController@getHistories')->name('history.get_histories');
});
