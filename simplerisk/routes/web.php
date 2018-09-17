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

Route::resource('/assessments', 'AssessmentController');
Route::resource('/hazards', 'HazardController');
Route::resource('/stages','StageController');
Route::resource('/categories', 'CategoryController');
Route::resource('/causes', 'CauseController');
Route::resource('/consequences', 'ConsequenceController');
Route::resource('/sources', 'SourceController');
Route::get('/report', 'ReportController@index');
