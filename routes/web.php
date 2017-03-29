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
    return view('index');
});
//)->middleware('login');

Route::get('login', 'UsersController@index');

Route::post('login','UsersController@login');

Route::post('register','UsersController@store');

Route::get('dekanReg','SuAdminController@showDekanRegister');

Route::post('dekanRegister','SuAdminController@store');

Route::get('dekanEd','SuAdminController@showDekanEdit');

Route::get('lendetRegister','UsersController@LendetRegister');