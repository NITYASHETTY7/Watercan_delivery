<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\SupportTicketsController;
use App\Http\Controllers\User\SubscriptionController;
use App\Http\Controllers\User\ReportController;
use App\Http\Controllers\User\UserAddressController;
use App\Http\Controllers\Common\DOMpdfController;

Route::group(
    ['middleware' => ['auth', '2fa', 'role:user', 'blacklist'], 'prefix' => 'user', 'as' => 'panel.user.'],
    function () {
        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
            }
        );
        Route::group(
            ['prefix' => 'cart', 'as' => 'cart.', 'controller' => CartController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::post('/update-address', 'updateAddress')->name('updateAddress');
                Route::post('/update-quantity', 'updateQuantity')->name('updateQuantity');
                Route::post('store', 'store')->name('store');
            }
        );

        Route::group(
            ['prefix' => 'checkout', 'as' => 'checkout.', 'controller' => CheckoutController::class],
            function () {
                Route::post('/', 'store')->name('store');
                Route::get('/page', 'page')->name('page'); 
                Route::get('/thankyou/{id}', 'thankyou')->name('thankyou');
                Route::post('/calculateTotal', 'calculateTotal')->name('calculateTotal');
            }
        );

        Route::group(
            ['prefix' => 'orders', 'as' => 'order.', 'controller' => OrderController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('invoice/{id}','invoice')->name('invoice');
                Route::post('store', 'store')->name('store');
                Route::post('payment', 'payment')->name('payment');
                Route::get('update-status/{id}', 'updateStatus')->name('update-status');

                Route::get('retry-payment/{id}', 'retryPayment')->name('retry-payment');
                Route::get('new-cart', 'newCart')->name('new-cart');
                Route::get('/{id}', 'show')->name('show');
            }
        );

        Route::group(
            ['prefix' => 'support-tickets', 'as' => 'support-tickets.', 'controller' => SupportTicketsController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
            }
        );

        Route::group(
            ['prefix' => 'subscriptions', 'as' => 'subscriptions.', 'controller' => SubscriptionController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('update-status/{id}', 'updateStatus')->name('update-status');
                Route::get('/show/{id}', 'show')->name('show');
            }
        );

        Route::group(
            ['prefix' => 'report', 'as' => 'report.', 'controller' => ReportController::class],
            function () {
                Route::get('/delivery', 'delivery')->name('delivery');
                Route::get('/revenue', 'revenue')->name('revenue');
            }
        );

        Route::group(
            ['prefix' => 'address', 'as' => 'address.', 'controller' => UserAddressController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('store', 'store')->name('store');
                 Route::get('edit/{id}', 'edit')->name('edit');
                 Route::get('destroy/{id}', 'destroy')->name('destroy');
                 Route::post('update/{id}', 'update')->name('update');
                Route::post('/check-pincode', 'checkPincode')->name('checkPincode');
            }
        );

        Route::group(
            ['prefix' => 'pdf', 'as' => 'pdf.', 'controller' => DOMpdfController::class],
            function () {
                Route::get('/invoice/{orderId}/pdf', 'index')->name('invoice.dom.pdf');
            }
        );







    }
);
