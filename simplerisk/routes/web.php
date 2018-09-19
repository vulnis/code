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

Route::get('/home'              , 'HomeController@index'   )->name('home');
Route::resource('/assets'       , 'AssetController'        , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/assessments'  , 'AssessmentController'   , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/risks'        , 'RiskController'         , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/stages'       ,'StageController'         , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/frameworks'   ,'FrameworkController'     , ['only' => [ 'index', 'show', 'store' ]]);
Route::resource('/categories'   , 'CategoryController'     , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/causes'       , 'CauseController'        , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/consequences' , 'ConsequenceController'  , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
Route::resource('/sources'      , 'SourceController'       , ['only' => [ 'index', 'show', 'store', 'destroy' ]]);
