<?php

use Illuminate\Support\Facades\Route;

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
    if (Auth::check()){
        return redirect()->route('home');
    }
    return view('welcome');
})->name('index');

Route::get('/server/{server}', 'ServerController@index')->name('server');
Route::post('/server/{server}/currentLoad', 'ServerController@currentLoad')->name('currentLoad');
Route::post('/server/{server}/currentSsh', 'ServerController@currentSsh')->name('currentSsh');

Route::get('/test', function () {
    return view('test');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
