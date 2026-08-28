<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\MFAController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\WebsitePageController;
use App\Http\Controllers\Site\NotFoundPageController;
use App\Http\Controllers\WorldController;
use App\Http\Controllers\Common\DOMpdfController;

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

Route::get('/test-pdf', function () {

    return view('test');

    $users  = App\Models\User::whereRoleIs('User')->get();
    foreach ($users as $user) {
        $user->avatar = null;
        $user->save();
    }


    $order = App\Models\Order::find(34);
    return dd(calculateExpectedDeliveryDate($order->id));
    return latLongAddress(22.1108971, 79.5418379);
});


Route::get('/send-message', function () {
    $deviceToken = 'your-token-here';
    $title = 'Hello';
    $message = 'This is a test message.';
    $data = (object) []; // or some key-value: ['foo' => 'bar']

    return sendNotificationToUser($deviceToken, $title, $message, $data);
});

// Auth
Route::group(['middleware' => ['blacklist']], function () {
    Route::get('4jSmdmdDoLCQR/jx3azBhhpuRclGem', [LoginController::class, 'adminForm'])->name('admin.login');
    Route::get('{role}/login', [LoginController::class, 'loginForm'])->name('login');
    Route::post('{role}/login', [LoginController::class, 'login'])->name('login.store');
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');
    Route::post('/login-validate', [LoginController::class, 'validateLoginByNumber'])->name('login-validate');
    Route::get('/otp', [LoginController::class, 'otp'])->name('otp-index');
    Route::get('/auth-signup', [LoginController::class, 'signup'])->name('signup');
    Route::post('/signup-validate', [LoginController::class, 'validateSignup'])->name('signup-validate');
    Route::post('/auth-otp-validate', [LoginController::class, 'validateOTP'])->name('otp-validate');
    Route::post('{role}/register', [RegisterController::class, 'register'])->name('store.register');
    Route::get('login/google', [LoginController::class, 'redirectToGoogle']);
    Route::get('login/google/callback', [LoginController::class, 'handleGoogleCallback']);

    Route::get('login/linkedin', [LoginController::class, 'redirectToLinkedIn']);
    Route::get('login/linkedin/callback', [LoginController::class, 'handleLinkedInCallback']);

    // Password
    Route::get(
        'password/forget',
        function () {
            return view('auth.passwords.forgot');
        }
    )->name('password.forget');

    // Block List Page
    Route::get(
        'not-allowed',
        function () {
            return view('black_listed.index');
        }
    )->name('black-listed');

    Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

    // Country State
    Route::get('get-states', [WorldController::class, 'getStates'])->name('world.get-states');
    Route::get('get-cities', [WorldController::class, 'getCities'])->name('world.get-cities');

    // Site
    Route::get('/', [HomeController::class, 'index'])->name('index');

    // Page
    Route::get('/page/{slug}', [WebsitePageController::class, 'page'])->name('page.slug');

    // Not Found Page
    Route::get('not-found', [NotFoundPageController::class, 'index'])->name('not-found.index');

    // MFA
    Route::get('/mfa-checkpoint', [MFAController::class, 'index'])->name('mfa-index');
    Route::post('/mfa-checkpoint', [MFAController::class, 'store'])->name('mfa-store');

    Route::post('/2fa', function () {
        $route = getAuthDashboardRoute();
        return redirect($route);
    })->name('2fa.store')->middleware('2fa');

    Route::get('2fa', function () {
        $route = getAuthDashboardRoute();
        return redirect($route);
    })->name('2fa')->middleware('2fa');

    Route::get('/mfa-reset-form', [MFAController::class, 'resetForm'])->name('mfa-reset-form');
    Route::post('/mfa-reset', [MFAController::class, 'mfaReset'])->name('mfa-reset');
    Route::get('/mfa-enabled', [MFAController::class, 'mfaEnabled'])->name('mfa-enabled');

    Route::get('/otp/send', [MFAController::class, 'sendOtpToEmail'])->name('otp-form-index');
    Route::Post('/verify/otp', [MFAController::class, 'verifyOtp'])->name('verify-otp');
    Route::post('/otp/resend', [MFAController::class, 'resendEmailOTP'])->name('resend.otp');

    // Register
    Route::get(
        '/change-locale',
        function () {
            // Sets the user's preferred language in the session using the 'lang' query parameter from the request.
            session(["locale" => request()->lang]);
            return back();
        }
    )->name('changeLocale');

    Route::group(
        ['prefix' => 'common/pdf', 'as' => 'common.pdf.', 'controller' => DOMpdfController::class],
        function () {
            Route::get('/invoice/{orderId}/pdf', 'index')->name('invoice.dom.pdf');
        }
    );


    // Routes
    Route::group(
        [],
        function () {
            include_once __DIR__ . '/admin.php';
            include_once __DIR__ . '/user.php';
            include_once __DIR__ . '/driver.php';
            include_once __DIR__ . '/cron.php';
        }
    );
});
