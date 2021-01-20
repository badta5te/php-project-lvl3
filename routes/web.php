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


Route::resource('domains', 'DomainController')->only([
    'index', 'store', 'show'
]);

Route::resource('domains.checks', 'DomainCheckController')->only('store');

Route::get('/', function () {
    return view('welcome');
})->name('homepage');
