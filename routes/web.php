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
    return redirect(\route('home'));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']],function (){
    Route::resource('prayer-requests',\App\Http\Controllers\Prayer\PrayerRequestController::class);
    Route::resource('roles',\App\Http\Controllers\RolesPermission\RolesController::class);
    Route::get('all-roles',[\App\Http\Controllers\RolesPermission\RolesController::class,'role_lists'])->name('all-roles');
});
