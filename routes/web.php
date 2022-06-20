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
    Route::resource('users',\App\Http\Controllers\Users\UserController::class);
    Route::get('/all-users',[\App\Http\Controllers\Users\UserController::class,'all_users'])->name('all-users');

    Route::resource('prayer-requests',\App\Http\Controllers\Prayer\PrayerRequestController::class);
    Route::get('/my-personal-prayer-request',[\App\Http\Controllers\Prayer\PrayerRequestController::class,'personalPrayer'])->name('my.personal.prayer');
    Route::get('/personal-prayer-request',[\App\Http\Controllers\Prayer\PrayerRequestController::class,'getPersonalPrayer'])->name('get.personal.prayer');
    Route::get('/all-prayer-request',[\App\Http\Controllers\Prayer\PrayerRequestController::class,'allPrayerRequest'])->name('all.prayer');
    Route::get('/prayer-request-details/{id}',[\App\Http\Controllers\Prayer\PrayerRequestController::class,'prayer_request_details'])->name('prayer.request.details');

    Route::resource('roles',\App\Http\Controllers\RolesPermission\RolesController::class);
    Route::get('/all-roles',[\App\Http\Controllers\RolesPermission\RolesController::class,'role_lists'])->name('all-roles');

    Route::resource('permissions',\App\Http\Controllers\RolesPermission\PermissionController::class);
    Route::get('/all-permissions',[\App\Http\Controllers\RolesPermission\PermissionController::class,'allPermissions'])->name('all-permissions');

    Route::resource('churches',\App\Http\Controllers\Church\ChurchController::class);
    Route::get('/all-churches',[\App\Http\Controllers\Church\ChurchController::class,'allChurches'])->name('all-churches');
});
