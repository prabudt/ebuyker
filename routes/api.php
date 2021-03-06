<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\Master\UserTypeController;
use App\Http\Controllers\API\Master\VehicleTypeController;
use App\Http\Controllers\API\VehiclesController;
use App\Http\Controllers\API\LoadController;
use App\Http\Controllers\API\TruckController;
use App\Http\Controllers\API\BookingController;
use App\Http\Controllers\API\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
Route::get('user-type', [UserTypeController::class, 'index']);
Route::post('submit-login-otp', [ApiController::class, 'submitLoginOTP']);
Route::post('re-sent-otp', [ApiController::class, 'authenticate']);
Route::post('user-approve', [ApiController::class, 'approveUser']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::post('change-password', [ApiController::class, 'changePassword']);

    Route::post('update-user', [ApiController::class, 'updateUser']);    
    Route::get('get-user', [ApiController::class, 'getUser']);

    Route::post('feedback/store', [ApiController::class, 'feedbackStore']);
    Route::get('feedback', [ApiController::class, 'feedbackGet']);


    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('create', [ProductController::class, 'store']);
    Route::put('update/{product}',  [ProductController::class, 'update']);
    Route::delete('delete/{product}',  [ProductController::class, 'destroy']);

    Route::get('vehicle-type', [VehicleTypeController::class, 'index']);
    Route::get('vehicles', [VehiclesController::class, 'index']);
    Route::group(['prefix'=>'customer'],function() {
        Route::resource('load', LoadController::class);
        Route::get('location-based-load', [LoadController::class, 'locationBasedLoad']);
    });
    Route::group(['prefix'=>'driver-transporter'],function() {
        Route::resource('truck', TruckController::class);
    });

    Route::any('file-store', [TruckController::class, 'fileStore']);
    Route::group(['prefix'=>'load'],function() {
        Route::resource('booking', BookingController::class);
    });

    Route::group(['prefix'=>'booking'],function() {
        Route::resource('chat', ChatController::class);
        Route::post('chat-approve',  [ChatController::class, 'ApproveLoad']);
        Route::post('customer-chat-approve',  [ChatController::class, 'CustomerApproveLoad']);
    });
});
