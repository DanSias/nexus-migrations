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

Route::get('/home', 'HomeController@index');

// Programs
Route::resource('programs', 'ProgramController');

// Budget
Route::resource('budget', 'BudgetController');

// Notes
Route::resource('notes', 'NoteController');

// Tasks (Action Items)
Route::resource('tasks', 'TaskController');

/**
 * API Routes
 * 
 * @param filter object
 * 
 * @return JSON response
 */
Route::get('/metrics/data', 'MetricController@data');
