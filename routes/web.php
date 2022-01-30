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
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route::group(['prefix'=>'users'],function() {
    Route::resource('users', App\Http\Controllers\UserController::class);
    Route::get('drivers-owners', [App\Http\Controllers\DriversController::class, 'index']);
    Route::get('drivers-owners/{id}', [App\Http\Controllers\DriversController::class, 'show']);
    Route::get('drivers-owners/vechile/{id}', [App\Http\Controllers\DriversController::class, 'vechileData']);
    Route::get('drivers-owners/load-history/{id}', [App\Http\Controllers\DriversController::class, 'loadHistory']);
    Route::get('transporter', [App\Http\Controllers\TransporterController::class, 'index']);
    Route::get('transporter/{id}', [App\Http\Controllers\TransporterController::class, 'show']);
    Route::get('transporter/vechile/{id}', [App\Http\Controllers\TransporterController::class, 'vechileData']);
    Route::get('transporter/vechile-view/{id}', [App\Http\Controllers\TransporterController::class, 'vechileView']);
    Route::get('transporter/load-history/{id}', [App\Http\Controllers\TransporterController::class, 'loadHistory']);
    Route::get('customer', [App\Http\Controllers\CustomerController::class, 'index']);
    Route::get('customer/{id}', [App\Http\Controllers\CustomerController::class, 'show']);
    Route::get('customer/load-history/{id}', [App\Http\Controllers\CustomerController::class, 'loadHistory']);
    Route::get('load-history', [App\Http\Controllers\UserController::class, 'loadHistory']);
// });
