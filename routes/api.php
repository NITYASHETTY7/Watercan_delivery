<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\LocationController;
use App\Http\Controllers\Api\User\AddressController;
use App\Http\Controllers\Api\User\UserKycController;
use App\Http\Controllers\Api\User\HomeController;

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

Route::post('login', [AuthController::class,'login'])->name('login');
Route::post('login-with-phone', [AuthController::class, 'loginWithPhone'])->name('login-with-phone');

Route::post('register-otp', [AuthController::class, 'registerOtp'])->name('register-with-otp');

Route::post('forget-password', [AuthController::class, 'forgetPassword'])->name('forget-password');
Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->name('verify-otp');
Route::post('get-states', [AuthController::class,'getStates'])->name('get-states');
Route::post('get-cities', [AuthController::class,'getCities'])->name('get-cities');
Route::post('reset-password', [AuthController::class,'resetPassword'])->name('reset-password');
Route::post('reset-otp', [AuthController::class,'resetOTP'])->name('reset-otp');
Route::post('register', [AuthController::class,'register'])->name('register');

Route::group(
['middleware' => 'auth:api', 'blacklist'], function () {
    Route::group(
        ['controller' => AuthController::class], function () {
            Route::post('update-device-token', 'updateDeviceToken');
            Route::post('change-password', 'changePassword');
            Route::get('logout', 'logout');
            Route::get('/profile', 'profile');
            Route::post('/update-profile', 'updateProfile');
            Route::post('/update-avatar', 'updateAvatar');
        }
    );

    // DRIVER ROUTES
    Route::group(
    ['prefix' => 'driver'],
    function () {
        Route::group(
            ['controller' => LocationController::class], function () {
                Route::post('/update-location', 'updateLocation');
            }
        );
        Route::group(
            ['controller' => UserKycController::class], function () {
                Route::post('/update-kyc', 'updateKyc');
            }
        );
        Route::group(
            ['controller' => HomeController::class], function () {
                Route::get('/home', 'driverHome');
            }
        );
    });


    // CUSTOMER ROUTES
    Route::group(
    ['prefix' => 'user'],
    function () {
        Route::group(
            ['controller' => AddressController::class], function () {
                Route::post('/store-address', 'storeAddress');
                Route::post('/update-address/{id}', 'updateAddress');
                Route::get('/delete-address/{id}', 'deleteAddress');
            }
        );
        Route::group(
            ['controller' => HomeController::class], function () {
                Route::get('/home', 'customerHome');
            }
        );
    });
});
