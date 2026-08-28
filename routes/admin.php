<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SupportTicketController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\FeatureActivationController;
use App\Http\Controllers\Admin\WebsitePageController;
use App\Http\Controllers\Admin\GeneralController;
use App\Http\Controllers\Admin\TroubleshootController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\UserAddressController;
use App\Http\Controllers\Admin\ThemeController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\UserKycController;
use App\Http\Controllers\Common\DOMpdfController;

Route::fallback(function () {
    abort('404');
});

Route::group(
    ['middleware' => ['auth', '2fa', 'role:admin', 'blacklist'], 'prefix' => 'admin', 'as' => 'panel.admin.'],
    function () {

        Route::group(
            ['prefix' => 'media', 'as' => 'media.', 'controller' => MediaController::class],
            function () {
                Route::get('/destroy', 'destroy')->name('destroy');
                Route::get('/destroy/{id}', 'destroyById')->name('single-destroy');
                Route::post('ckeditor/upload', 'uploadEditorImage')->name('ckeditor.upload');
            }
        );
        Route::group(
            ['prefix' => 'dashboard', 'as' => 'dashboard.', 'controller' => DashboardController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::get('/logout-as', 'logoutAs')->name('logout-as');
            }
        );

        // Products Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/products', 'as' => 'products.'],
            function () {
                Route::get('', ['uses' => 'ProductController@index', 'as' => 'index'])->middleware('permission:item_view_rp');
                Route::get('create', ['uses' => 'ProductController@create', 'as' => 'create'])->middleware('permission:item_create_rp');
                Route::post('store', ['uses' => 'ProductController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'ProductController@edit', 'as' => 'edit'])->middleware('permission:item_edit_rp');
                Route::post('update/{id}', ['uses' => 'ProductController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ProductController@destroy', 'as' => 'destroy'])->middleware('permission:item_delete_rp');
                Route::post('bulk-action', ['uses' => 'ProductController@bulkAction', 'as' => 'bulk-action'])->middleware('permission:item_bulk_rp');
            }
        );

        // Branches Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/branches', 'as' => 'branches.'],
            function () {
                Route::get('', ['uses' => 'BranchController@index', 'as' => 'index']);
                Route::get('create', ['uses' => 'BranchController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'BranchController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'BranchController@edit', 'as' => 'edit']);
                Route::post('update/{id}', ['uses' => 'BranchController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'BranchController@destroy', 'as' => 'destroy']);
                Route::post('bulk-action', ['uses' => 'BranchController@bulkAction', 'as' => 'bulk-action']);
            }
        );

        // Zones Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/zones', 'as' => 'zones.'],
            function () {
                Route::get('', ['uses' => 'ZoneController@index', 'as' => 'index']);
                Route::get('create', ['uses' => 'ZoneController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'ZoneController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'ZoneController@edit', 'as' => 'edit']);
                Route::post('update/{id}', ['uses' => 'ZoneController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ZoneController@destroy', 'as' => 'destroy']);
                Route::post('bulk-action', ['uses' => 'ZoneController@bulkAction', 'as' => 'bulk-action']);
                Route::post('zones/map-section', ['uses' => 'ZoneController@mapSection', 'as' => 'map-section']);
            }
        );

        // Zone Pincode Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/zone-pincodes', 'as' => 'zone-pincodes.'],
            function () {
                Route::get('', ['uses' => 'ZonePincodeController@index', 'as' => 'index']);
                Route::get('create', ['uses' => 'ZonePincodeController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'ZonePincodeController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'ZonePincodeController@edit', 'as' => 'edit']);
                Route::post('update/{id}', ['uses' => 'ZonePincodeController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ZonePincodeController@destroy', 'as' => 'destroy']);
                Route::post('bulk-action', ['uses' => 'ZonePincodeController@bulkAction', 'as' => 'bulk-action']);
            }
        );


        // Zone Pincode User Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/zone-pincode-users', 'as' => 'zone-pincode-users.'],
            function () {
                Route::get('', ['uses' => 'ZonePincodeUserController@index', 'as' => 'index']);
                Route::get('create', ['uses' => 'ZonePincodeUserController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'ZonePincodeUserController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'ZonePincodeUserController@edit', 'as' => 'edit']);
                Route::post('update/{id}', ['uses' => 'ZonePincodeUserController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'ZonePincodeUserController@destroy', 'as' => 'destroy']);
                Route::post('bulk-action', ['uses' => 'ZonePincodeUserController@bulkAction', 'as' => 'bulk-action']);
            }
        );

        // Order Route
        Route::group(
            ['namespace' => 'App\Http\Controllers\Admin', 'prefix' => '/orders', 'as' => 'orders.'],
            function () {
                Route::get('', ['uses' => 'OrderController@index', 'as' => 'index']);
                Route::get('create', ['uses' => 'OrderController@create', 'as' => 'create']);
                Route::post('store', ['uses' => 'OrderController@store', 'as' => 'store']);
                Route::get('edit/{id}', ['uses' => 'OrderController@edit', 'as' => 'edit']);
                Route::get('show/{id}', ['uses' => 'OrderController@show', 'as' => 'show']);
                Route::post('update/{id}', ['uses' => 'OrderController@update', 'as' => 'update']);
                Route::get('delete/{id}', ['uses' => 'OrderController@destroy', 'as' => 'destroy']);
                Route::get('invoice/{id}', ['uses' => 'OrderController@invoice', 'as' => 'invoice']);
                Route::post('bulk-action', ['uses' => 'OrderController@bulkAction', 'as' => 'bulk-action']);
                Route::post('update/status/{id}', ['uses' => 'OrderController@updateStatus', 'as' => 'status-update']);
                Route::get('update/payment-status/{id}', ['uses' => 'OrderController@updatePaymentStatus', 'as' => 'payment-status-update']);
                Route::get('update/assign-to/{id}', ['uses' => 'OrderController@updateAssignTo', 'as' => 'assign-driver']);
            }
        );

        Route::group(
            ['prefix' => 'user-kyc', 'as' => 'user-kyc.', 'controller' => UserKycController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:user_delete_rp');
                Route::get('show/{id}', 'show')->name('show')->middleware('permission:kyc_delete_rp');
                Route::post('update/{id}', 'update')->name('update')->middleware('permission:kyc_edit_rp');
                Route::get('destroy/{id}', 'destroy')->name('destroy')->middleware('permission:kyc_delete_rp');
                Route::post('update-status/{id}', 'updateStatus')->name('update-status');
            }
        );


        Route::group(
            ['prefix' => 'reports', 'as' => 'reports.',  'controller' => ReportController::class],
            function () {
                Route::get('/', 'index')->name('index');
            }
        );

        Route::group(['prefix' => 'personalization', 'as' => 'personalization.', 'controller' =>  ThemeController::class], function () {
            Route::get('/index', 'index')->name('index');
            Route::get('/store', 'store')->name('store');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
        });

        Route::group(
            ['prefix' => 'profile', 'as' => 'profile.', 'controller' => ProfileController::class],
            function () {
                Route::get('/', 'index')->name('index');
                Route::post('update/{id}', 'update')->name('update');
                Route::post('update/password/{id}', 'updatePassword')->name('update.password');
                Route::post('update/profile-img/{id}', 'updateProfileImg')->name('update.profile-img');
                Route::get('remove/profile-img/{id}', 'removeProfileImg')->name('remove.profile-img');
            }
        );

        Route::group(
            ['prefix' => 'users', 'as' => 'users.', 'controller' => UserController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:user_view_rp');
                Route::any('print', 'print')->name('print');
                Route::get('create', 'create')->name('create')->middleware('permission:user_create_rp');
                Route::post('store', 'store')->name('store')->middleware('permission:user_create_rp');
                Route::get('edit/{id}', 'edit')->name('edit')
                    ->middleware('permission:user_edit_rp');
                Route::get('show/{id}', 'show')->name('show')->middleware('permission:user_show_rp');
                Route::get('destroy/{id}', 'destroy')->name('destroy')->middleware('permission:user_delete_rp');
                Route::post('update/{id}', 'update')->name('update')->middleware('permission:user_edit_rp');
                Route::get('update/status/{id}/{s}', 'updateStatus')->name('update-status');
                Route::get('login-as/{id}/', 'loginAs')->name('login-as');
                Route::get('{id}/sessions', 'session')->name('sessions');
                Route::get('sessionDelete/{id}/', 'sessionDelete')->name('sessionDelete');
                Route::post('bulk-action', 'bulkAction')->name('bulk-action')->middleware('permission:user_bulk_rp');
                Route::post('session/bulk-action', 'sessionBulkAction')->name('session.bulk-action');
                Route::post('/kyc-status', 'updateKycStatus')->name('update-kyc-status');
                Route::get('/verified-status/{id}', 'verifiedStatus')->name('verified-status');
                Route::post('/user/update-password/{id}', 'updateUserPassword')->name('update-user-password');
                Route::post('get/users', 'getUsers')->name('get-users');
                Route::get('/user-delete', 'userDelete')->name('userDelete');
                Route::post('/export', 'export')->name('export');
                Route::post('/bulk', 'export')->name('bulk');
                Route::get('/get-permissions', 'getPermission')->name('get.permission');

                Route::get('/verification/show/{id}', 'verificationShow')->name('verification.show');
            }
        );
        Route::group(
            ['prefix' => 'addresses', 'as' => 'addresses.', 'controller' => UserAddressController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:address_view_rp');
                Route::post('store', 'store')->name('store')->middleware('permission:address_create_rp');
                Route::post('update', 'update')->name('update')->middleware('permission:address_edit_rp');
                Route::get('destroy/{id}', 'destroy')->name('destroy')->middleware('permission:address_delete_rp');
            }
        );

        Route::group(
            ['prefix' => 'roles', 'as' => 'roles.', 'controller' => RoleController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:role_view_rp');
                Route::post('store', 'store')->name('store')->middleware('permission:role_create_rp');
                Route::get('edit/{role}', 'edit')->name('edit')->middleware('permission:role_edit_rp');
                Route::post('update/{id}', 'update')->name('update')->middleware('permission:role_edit_rp');
                Route::get('destroy/{role}', 'destroy')->name('destroy')->middleware('permission:role_delete_rp');
            }
        );
        Route::group(
            ['prefix' => 'permissions', 'as' => 'permissions.', 'controller' => PermissionController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:permission_view_rp');
                Route::post('store', 'store')->name('store')->middleware('permission:permission_create_rp');
                Route::get('destroy/{id}', 'destroy')->name('destroy')->middleware('permission:permission_delete_rp');
            }
        );

        Route::group(
            ['prefix' => 'website-pages', 'as' => 'website-pages.', 'controller' => WebsitePageController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:page_view_rp');
                Route::get('/create', 'create')->name('create')->middleware('permission:page_create_rp');
                Route::post('/store', 'store')->name('store')->middleware('permission:page_create_rp');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:page_edit_rp');
                Route::get('/show/{id}', 'show')->name('show');
                Route::post('/update/{id}', 'update')->name('update')->middleware('permission:page_edit_rp');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:page_delete_rp');
                Route::get('/delete-media/{id}', 'destroyMedia')->name('destroy-media');
                Route::post('/bulk-action', 'bulkAction')->name('bulk-action')->middleware('permission:page_bulk_rp');
            }
        );

        Route::group(
            ['prefix' => 'support-tickets', 'as' => 'support-tickets.', 'controller' => SupportTicketController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:ticket_view_rp');
                Route::get('/create', 'create')->name('create')->middleware('permission:ticket_create_rp');
                Route::post('/store', 'store')->name('store')->middleware('permission:ticket_create_rp');
                Route::post('/bulk-delete', 'bulkAction')->name('bulk-delete')->middleware('permission:ticket_bulk_rp');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:ticket_edit_rp');
                Route::get('/show/{id}', 'show')->name('show')->middleware('permission:ticket_show_rp');
                Route::post('/add-attachment/{id}', 'addAttachment')->name('add-attachment');
                Route::get('/start-meet/{id}', 'startMeet')->name('start-meet')->middleware('permission:ticket_meet_rp');;
                Route::post('/update-status', 'status')->name('status');
                Route::post('/update/{id}', 'update')->name('update')->middleware('permission:ticket_edit_rp');
                Route::get('/reply', 'reply')->name('reply');
                Route::any('/print', 'print')->name('print');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:ticket_delete_rp');
                Route::post('/export', 'export')->name('export');
                Route::post('/bulk', 'export')->name('bulk');
            }
        );

        Route::group(
            ['prefix' => 'setting', 'as' => 'setting.', 'controller' => SettingController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:control_basic_detail_view_rp');
                Route::post('/store', 'store')->name('store');
            }
        );

        Route::group(
            ['prefix' => 'setting', 'as' => 'setting.', 'controller' => FeatureActivationController::class],
            function () {
                Route::get('/features-activation', 'index')->name('features-activation')->middleware('permission:features_activation_view_rp');
                Route::get('/features-activation/PassCode', 'index')->name('features-activation.PassCode');
                Route::post('/features-activation/store', 'store')->name('features-activation.store');
            }
        );

        Route::group(
            ['prefix' => 'website-pages', 'as' => 'website-pages.', 'controller' => WebsitePageController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:page_view_rp');
                Route::get('/create', 'create')->name('create')->middleware('permission:page_create_rp');
                Route::post('/store', 'store')->name('store');
                Route::get('/edit/{id}', 'edit')->name('edit')->middleware('permission:page_edit_rp');
                Route::get('/show/{id}', 'show')->name('show');
                Route::post('/update/{id}', 'update')->name('update');
                Route::get('/destroy/{id}', 'destroy')->name('destroy')->middleware('permission:page_delete_rp');
                Route::get('/delete-media/{id}', 'destroyMedia')->name('destroy-media');
                Route::get('/appearance', 'appearance')->name('appearance');
                Route::get('/social-login', 'socialLogin')->name('social-login')->middleware('permission:control_social_logins_detail_view_rp');
                Route::post('/bulk-action', 'bulkAction')->name('bulk-action')->middleware('permission:page_bulk_rp');
            }
        );

        Route::group(
            ['prefix' => 'general', 'as' => 'general.', 'controller' => GeneralController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:general_setting_view_rp');
                Route::get('/storage-link', 'storageLink')->name('storage-link');
                Route::get('/optimize-clear', 'optimizeClear')->name('optimize-clear');
                Route::get('/session-clear', 'sessionClear')->name('session-clear');
                Route::get('/content-group', 'contentGroup')->name('content-group');
                Route::post('/export', 'export')->name('export');
                Route::post('/bulk', 'export')->name('bulk');
            }
        );
        Route::group(
            ['prefix' => 'troubleshoot', 'as' => 'troubleshoot.', 'controller' => TroubleshootController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:troubleshoot_setting_view_rp');
            }
        );

        Route::group(
            ['prefix' => 'notifications', 'as' => 'notifications.', 'controller' => NotificationController::class],
            function () {
                Route::get('/', 'index')->name('index')->middleware('permission:notification_view_rp');
                Route::any('print', 'print')->name('print');
                Route::get('/update/{id}', 'update')->name('update')->middleware('permission:notification_edit_rp');
                Route::post('/mark-as-read', 'markAsRead')->name('mark-as-read');
                Route::post('/delete-all', 'deleteAll')->name('delete-all');
            }
        );

        Route::group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'debug-jobs', 'as' => 'debug-jobs.'], function () {
            Route::get('/', ['uses' => 'DebugJobController@index', 'as' => 'index'])->middleware('permission:debug_jobs_view_rp');
            Route::get('/destroy', ['uses' => 'DebugJobController@destroy', 'as' => 'destroy'])->middleware('permission:debug_jobs_delete_rp');
        });
        Route::group(['namespace' => 'App\Http\Controllers\Admin', 'prefix' => 'logs', 'as' => 'logs.'], function () {
            Route::get('/', ['uses' => 'LogController@getLogFiles', 'as' => 'list']);
            Route::get('/view/{file}', ['uses' => 'LogController@viewLog', 'as' => 'view']);
        });

        Route::group(
            ['prefix' => 'pdf', 'as' => 'pdf.', 'controller' => DOMpdfController::class],
            function () {
                Route::get('/invoice/{orderId}/pdf', 'index')->name('invoice.dom.pdf');
            }
        );
    }
);
