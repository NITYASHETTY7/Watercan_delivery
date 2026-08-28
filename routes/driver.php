<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\DashboardController;
use App\Http\Controllers\Driver\CartController;
use App\Http\Controllers\Driver\CheckoutController;
use App\Http\Controllers\Driver\OrderController;
use App\Http\Controllers\Driver\SupportTicketsController;
use App\Http\Controllers\Driver\SubscriptionController;
use App\Http\Controllers\Driver\ReportController;
use App\Http\Controllers\Driver\UserAddressController;

Route::group(
    ['middleware' => ['auth', '2fa', 'role:driver', 'blacklist'], 'prefix' => 'driver', 'as' => 'panel.driver.'],
    function () {
        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
            }
        );


        Route::group(
            ['prefix' => 'orders', 'as' => 'order.', 'controller' => OrderController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/show/{id}', 'show')->name('show');
                Route::post('update-status/{id}', 'updateStatus')->name('update-status');
                Route::post('delivery-challan-update/{id}', 'updateDeliveryStatus')->name('update-delivery-challan');
            }
        );

        Route::group(
            ['prefix' => 'support-tickets', 'as' => 'support-tickets.', 'controller' => SupportTicketsController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/store', 'store')->name('store');
            }
        );

        Route::group(
            ['prefix' => 'delivery-report', 'as' => 'report.', 'controller' => ReportController::class],
            function () {
                Route::get('/', 'delivery')->name('delivery');
            }
        );

        Route::group(
            ['prefix' => 'address', 'as' => 'address.', 'controller' => UserAddressController::class],
            function () {
                Route::post('store', 'store')->name('store');
            }
        );
    }
);
