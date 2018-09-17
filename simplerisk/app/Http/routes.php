<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use App\Risk;
use Illuminate\Http\Request;

Route::auth();
Route::get('/', 'HomeController@index');

Route::resource('/assessments', 'AssessmentController');
Route::resource('/hazards', 'HazardController');
Route::resource('/stages','StageController');
Route::resource('/categories', 'CategoryController');
Route::resource('/causes', 'CauseController');
Route::resource('/consequences', 'ConsequenceController');
Route::resource('/sources', 'SourceController');