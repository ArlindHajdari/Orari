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
Route::get('login', 'UsersController@index');

Route::post('login','UsersController@login');

Route::group(['middleware'=>'login'], function(){
    Route::get('/',function(){
        return view('index');
    });

    Route::post('logout','UsersController@logout');

    Route::get('dekanet','DekanController@index');

    Route::post('register-dekan','DekanController@store');

    Route::delete('dekan-delete/{id}','DekanController@destroy');

    Route::patch('dekan-edit/{id}/{photo}','DekanController@edit');

    Route::match(['post','get'],'dekanet','DekanController@show');

    Route::post('register','UsersController@store');

    Route::get('dekanReg','SuAdminController@showDekanRegister');

    Route::post('dekanRegister','SuAdminController@store');

    Route::get('dekanEd','SuAdminController@showDekanEdit');

    Route::get('lendetRegister','UsersController@LendetRegister');

    Route::match(['post','get'],'FacultyPanel','FacultyController@show');

    Route::post('facultyRegister','FacultyController@store');

    Route::delete('facultyDelete/{id}','FacultyController@destroy');

    Route::patch('facultyEdit/{id}','FacultyController@edit');
    
    Route::match(['post','get'],'departamentPanel','DepartmentController@show');

    Route::post('departmentRegister','DepartmentController@store');

    Route::delete('departmentDelete/{id}','DepartmentController@destroy');

    Route::patch('departmentEdit/{id}','DepartmentController@edit');

    Route::post('LendetReg','LendetController@store');

    Route::match(['post','get'],'LendetPanel','LendetController@search');

    Route::match(['post','get'],'proflende','ProfLendeController@index');

    Route::delete('delete-prosub/{id}','ProfLendeController@destroy');

    Route::patch('prolende-edit/{id}/{asis_id1}/{asis_id2}/{asis_id3}/{asis_id4}/{asis_id5}','ProfLendeController@edit');

    Route::post('salla-register','HallsController@store');

    Route::delete('salla-delete/{id}','HallsController@destroy');

    Route::patch('salla-edit/{id}','HallsController@edit');

    Route::match(['post','get'],'sallat','HallsController@show');

    Route::patch('lendet-edit/{id}','LendetController@edit');

    Route::delete('lendet-delete/{id}','LendetController@destroy');

    Route::get('OrariPanel','OrariController@index');

    Route::get('scheduler','ScheduleController@index');

    Route::get('disponueshmeria','AvailabilityController@index');

    Route::post('disponueshmeria','AvailabilityController@store');

    Route::delete('delete-availability/{id}','AvailabilityController@destroy');

    Route::patch('edit-availability/{id}','AvailabilityController@edit');

    Route::get('OrariPanel','OrariController@index');

    Route::post('register-mesimdhenesi','MesimdhenesitController@store');

    Route::delete('mesimdhenesi-delete/{id}','MesimdhenesitController@destroy');

    Route::patch('mesimdhenesi-edit/{id}/{photo}','MesimdhenesitController@edit');

    Route::match(['post','get'],'mesimdhenesit','MesimdhenesitController@show');
});