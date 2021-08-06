<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('dashboard');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/access-denied', 'HomeController@accessDenied')->name('access.denied');

Route::get('/test-email', 'HomeController@testEmail');

include('extra/modal_routes.php');
