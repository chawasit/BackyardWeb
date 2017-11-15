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

Route::get('/', 'MonitorController@dashboard');
Route::get('/log', 'MonitorController@logLatest');
Route::get('/log/{from}/{to}', 'MonitorController@log')->where([
    'from' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
    , 'to' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
]);

Route::get('/history', 'MonitorController@history');
Route::post('/history', 'MonitorController@historyRedirect');
Route::get('/history/{from}/{to}', 'MonitorController@historyView')->where([
    'from' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
    , 'to' => '[0-9]{4}-[0-9]{2}-[0-9]{2}'
]);

Route::get('/notification', 'MonitorController@notification');
Route::get('/notification', 'MonitorController@notification');
Route::post('/value', 'LogController@createValue');
Route::post('/notification', 'LogController@createNotification');