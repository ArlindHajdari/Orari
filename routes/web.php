<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
*/

//Users
Route::get('login', 'UsersController@index');
Route::post('login','UsersController@login');
Route::get('lock','UsersController@lock');
Route::post('unlock','UsersController@postlock');
//EndUsers

//ResetPassword
Route::get('recover','ResetPasswordController@index');
Route::post('recover','ResetPasswordController@resetPassword');
Route::get('reset/{email}/{code}','ResetPasswordController@recover');
Route::post('reset/{email}/{code}','ResetPasswordController@postRecover');
//EndResetPassword

Route::group(['middleware'=>'login'], function(){
    Route::get('/','DashboardController@index');

    //Users
    Route::post('logout','UsersController@logout');
    Route::post('register','UsersController@store');
    Route::get('kontakti','UsersController@getKontakti');
    Route::post('kontakti','UsersController@postKontakti');
    //EndUsers

    //SuAdmin
    Route::get('dekanReg','SuAdminController@showDekanRegister');
    Route::post('dekanRegister','SuAdminController@store');
    //EndSuAdmin

    //Deans
    Route::post('register-dekan','DekanController@store');
    Route::delete('dekan-delete/{id}','DekanController@destroy');
    Route::patch('dekan-edit/{id}/{photo}','DekanController@edit');
    Route::match(['post','get'],'dekanet','DekanController@show');
    //EndDeans

    //Faculties
    Route::match(['post','get'],'FacultyPanel','FacultyController@show');
    Route::post('facultyRegister','FacultyController@store');
    Route::delete('facultyDelete/{id}','FacultyController@destroy');
    Route::patch('facultyEdit/{id}','FacultyController@edit');
    //EndFaculties

    //Departments
    Route::match(['post','get'],'departamentPanel','DepartmentController@show');
    Route::post('departmentRegister','DepartmentController@store');
    Route::delete('departmentDelete/{id}','DepartmentController@destroy');
    Route::patch('departmentEdit/{id}','DepartmentController@edit');
    //EndDepartments

    //Status
    Route::match(['post','get'],'statusPanel','StatusController@show');
    Route::post('statusRegister','StatusController@store');
    Route::delete('statusDelete/{academic_title_id}/{status_id}','StatusController@destroy');
    Route::patch('statusEdit/{academic_title_id}/{status_id}','StatusController@edit');
    //EndStatus

    //ProfLende
    Route::match(['post','get'],'proflende','ProfLendeController@index');
    Route::post('register','ProfLendeController@store');
    Route::delete('delete-prosub/{id}','ProfLendeController@destroy');
    Route::patch('prolende-edit/{id}/{asis_id1}/{asis_id2}/{asis_id3}/{asis_id4}/{asis_id5}','ProfLendeController@edit');
    //EndProfLende

    //Halls
    Route::post('salla-register','HallsController@store');
    Route::delete('salla-delete/{id}','HallsController@destroy');
    Route::match(['post','get'],'sallat','HallsController@show');
    Route::patch('salla-edit/{id}','HallsController@edit');
    //EndHalls

    //SecFacultyHall
    Route::match(['post','get'],'secFaculty','hallSecFacController@show');
    Route::patch('secfaculty-register','hallSecFacController@store');
    Route::patch('secfaculty-delete/{id}','hallSecFacController@destroy');
    Route::patch('secfaculty-edit/{id}','hallSecFacController@edit');
    //EndSecFacultyHall

    //Subjects
    Route::match(['post','get'],'LendetPanel','LendetController@search');
    Route::post('LendetReg','LendetController@store');
    Route::patch('lendet-edit/{id}','LendetController@edit');
    Route::delete('lendet-delete/{id}','LendetController@destroy');
    //EndSubjects

    //Status
    Route::match(['post','get'],'statusAdministration','StatusAdministrationController@show');
    Route::post('status-register','StatusAdministrationController@store');
    Route::patch('status-edit/{id}','StatusAdministrationController@edit');
    Route::delete('status-delete/{id}','StatusAdministrationController@destroy');
    //EndStatus

    Route::get('getProfByLUSHandSubject','ScheduleController@getProfByLUSHandSubject');


    //Scheduler
    Route::get('getGroupByLende','ScheduleController@getGroupByLende');
    Route::get('getlushByLende','ScheduleController@getlushByLende');
    Route::post('scheduler','ScheduleController@show');
    Route::get('scheduler','ScheduleController@index');
    Route::post('store-schedule','ScheduleController@store');
    Route::post('getMaxHourPerDay','ScheduleController@getMaxHourPerDay');
    Route::patch('edit-schedule/{id}','ScheduleController@update');
    Route::delete('delete-schedule','ScheduleController@destroy');
    Route::get('getProfByLende','ScheduleController@getProfByLende');
    Route::get('getLendetFromSemester','ScheduleController@getLendetFromSemester');
    Route::post('generateScheduler','ScheduleController@generateScheduler');
    Route::get('hallsSchedule','HallsScheduleController@index');
    Route::get('getHallsSchedule','HallsScheduleController@show');
    Route::delete('deleteSchedulers','ScheduleController@destroyAllSchedule');
    //EndScheduler


    //Availability
    Route::get('disponueshmeria','AvailabilityController@index');
    Route::post('disponueshmeria','AvailabilityController@store');
    Route::patch('edit-availability/{id}','AvailabilityController@edit');
    Route::post('day-availability','AvailabilityController@store_allday');
    Route::delete('delete-availability/{id}','AvailabilityController@destroy');
    //EndAvailability

    //Teachers
    Route::post('register-mesimdhenesi','MesimdhenesitController@store');
    Route::delete('mesimdhenesi-delete/{id}','MesimdhenesitController@destroy');
    Route::patch('mesimdhenesi-edit/{id}/{photo}','MesimdhenesitController@edit');
    Route::match(['post','get'],'mesimdhenesit','MesimdhenesitController@show');
    //EndTeachers


    //Settings
    Route::get('settings','SettingsController@index');
    Route::patch('settings/{id}','SettingsController@update');
    //EndSettings

    //AcademicTitles
    Route::match(['post','get'],'academicTitlePanel','AcademicTitleController@show');
    Route::post('academicTitleRegister','AcademicTitleController@store');
    Route::delete('academicTitleDelete/{id}','AcademicTitleController@destroy');
    Route::patch('academicTitleEdit/{id}','AcademicTitleController@edit');
    //EndAcademicTitles


    //CPA
    Route::match(['post','get'],'cpaPanel','CpaController@show');
    Route::post('cpaRegister','CpaController@store');
    Route::delete('cpaDelete/{id}','CpaController@destroy');
    Route::patch('cpaEdit/{id}','CpaController@edit');
    //EndCPA


    //CPAlush
    Route::match(['post','get'],'cpalushPanel','CpaLushController@show');
    Route::post('cpalushRegister','CpaLushController@store');
    Route::delete('cpalushDelete/{cpa_id}/{lush_id}','CpaLushController@destroy');
    Route::patch('cpalushEdit/{cpa_id}/{lush_id}','CpaLushController@edit');
    //EndCPAlush


    //ShowScheduler
    Route::get('getSemesters','ShowScheduleController@getSemesters');
    Route::get('showschedule','ShowScheduleController@index');
    Route::get('getDepartmentByFaculty','ShowScheduleController@getDepartmentByFaculty');
    Route::get('getSemesterByDepartment','ShowScheduleController@getSemesterByDepartment');
    Route::get('showScheduleByData','ShowScheduleController@show');
    //EndShowScheduler

    //GroupsLushSubjects
    Route::match(['post','get'],'groups-lush-subjects-panel','GroupsLushSubjectsController@show');
    Route::post('groupLushSubject-register','GroupsLushSubjectsController@store');
    Route::get('getLushFromSubject','GroupsLushSubjectsController@getLushFromSubject')->name('glfs');
    Route::delete('groupLushSubject-delete/{group_id}/{subject_lush_id}','GroupsLushSubjectsController@destroy');
    Route::patch('groupLushSubject-edit/{group_id}/{subject_lush_id}','GroupsLushSubjectsController@update');
    //EndGroupsLushSubjects
});
