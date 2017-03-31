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

Route::get('dekanet','DekanController@index');

Route::post('register','DekanController@store');

Route::post('dekanet','DekanController@show');

Route::post('login','UsersController@login');

Route::post('register','UsersController@store');

Route::get('dekanReg','SuAdminController@showDekanRegister');

Route::post('dekanRegister','SuAdminController@store');

Route::get('dekanEd','SuAdminController@showDekanEdit');

Route::get('lendetRegister','UsersController@LendetRegister');


Route::get('FacultyPanel','FacultyController@index');

Route::get('LendetPanel','LendetController@index');

Route::get('FacultyPanel','FacultyController@index'); 

Route::resource('hall', 'HallsController');

