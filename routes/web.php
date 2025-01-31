
<?php
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeEntryController;
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
Route::get('/projects', 'ProjectController@index')->name('projects.index');
Route::get('/tasks', 'TaskController@index')->name('tasks.index');
Route::get('/time-entries', 'TimeEntryController@index')->name('time-entry.index');
Route::get('/get-tasks/{id}', 'TimeEntryController@fetchTask');
Route::post('/time-entries/add', 'TimeEntryController@store')->name('time-entries.add');
Route::get('/time-entries/report', 'ProjectController@showReport')->name('time-entries.report');
Route::get('/report/filter/{id}', 'ProjectController@filter')->name('time-entries.filter');

