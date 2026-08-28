<?php

use App\Http\Controllers\CronController;
use Illuminate\Support\Facades\Route;

// CRON
Route::group(['prefix' => 'cron'], function () {
    // Convert Subscription Order to Express Order
    Route::get('create-express/orders/CEORD6534', [CronController::class,'createExpressOrders']);
});
    