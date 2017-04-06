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

Route::post('logout','UsersController@logout');

Route::get('dekanet','DekanController@index');

Route::post('register-dekan','DekanController@store');

Route::delete('dekan-delete/{id}','DekanController@destroy');

Route::patch('dekan-edit/{id}/{photo}','DekanController@edit');

Route::match(['post','get'],'dekanet','DekanController@show');

Route::post('login','UsersController@login');

Route::post('register','UsersController@store');

Route::get('dekanReg','SuAdminController@showDekanRegister');

Route::post('dekanRegister','SuAdminController@store');

Route::get('dekanEd','SuAdminController@showDekanEdit');

Route::get('lendetRegister','UsersController@LendetRegister');

Route::get('FacultyPanel','FacultyController@index');

Route::get('FacultyPanel','FacultyController@index'); 

Route::post('LendetReg','LendetController@store');

Route::match(['post','get'],'LendetPanel','LendetController@search');

<<<<<<< HEAD
Route::match(['post','get'],'proflende','ProfLendeController@index');

Route::delete('delete-prosub/{id}','ProfLendeController@destroy');

Route::patch('prolende-edit/{id}','ProfLendeController@edit');

=======
<<<<<<< HEAD
>>>>>>> origin/master
//Route::get('LendetSearch','LendetController@search');
//
//Route::get('LendetPanel','LendetController@index');

Route::post('salla-register','HallsController@store');

Route::delete('salla-delete/{id}','HallsController@destroy');

Route::patch('salla-edit/{id}','HallsController@edit');

Route::match(['post','get'],'sallat','HallsController@show');
=======
Route::patch('lendet-edit/{id}','LendetController@edit');

Route::delete('lendet-delete/{id}','LendetController@destroy');
>>>>>>> origin/master
