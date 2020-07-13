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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/message/{id}','MessageController@getMessage')->name('message');
Route::post('/message','MessageController@sendMessage');


Route::match(['get','post'],'/create_warning','WarningAllController@add_warning')->name('add_warning');

Route::get('/view_notification', function () {
    return view('warning.showNotification');
});