<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MailSmsTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Socialite;

class LoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login.
     *
     * @param  \App\Models\Role $Role
     * @return \Illuminate\Http\Response
     */
    public function loginForm($role)
    {
        if ($role === 'admin') {
            abort(404);
        }

        if (!Role::where('name', $role)->exists()) {
            abort(404);
        }
        return view('auth.' . $role . '.login.index', compact('role'));
    }

    public function adminForm()
    {
        $role = 'admin';
        if (!Role::where('name', $role)->exists()) {
            abort(404);
        }
        $metas = getSeoData('adminLogin');
        return view('auth.' . $role . '.login.index', compact('role', 'metas'));
    }

    public function otp(Request $request)
    {
        $phone = $request->phone;
        return view('auth.send_otp.index', compact('phone'));
    }

    public function signup(Request $request)
    {
        if (session()->has('phone')) {
            $phone = session()->get('phone');
            return view('auth.signup.index', compact('phone'));
        } else {
            return redirect()->back()->with('error', "Something went wrong!");
        }
    }

    public function validateSignup(Request $request)
    {

        $existEmail = User::whereEmail($request->email)->first();
        if ($existEmail) {
            return back()->with('error', 'Email is already Exists!');
        }

        $validator = Validator::make($request->all(), [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->with('error', $validator->messages()->first());
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->syncRoles([3]);
        auth()->loginUsingId($user->id);
        return redirect(route('index'));
    }

    public function validateOTP(Request $request)
    {
        $get_otp = implode('', $request->otp);
        if (session()->get('otp') == $get_otp) {
            $phone = session()->get('phone');
            $user = User::where('phone', '!=', null)->wherePhone($phone)->first();
            if ($user) {
                if (auth()->check()) {
                    auth()->logout();
                }

                // Setting Dynamic Session Domain for logging in

                auth()->loginUsingId($user->id);
                return redirect()->route('index');
            } else {
                return redirect(route('signup'));
            }
        } else {
            return back()->with('error', 'The OTP entered is incorrect');
        }
    }

    public function validateLoginByNumber(Request $request)
    {
        if (is_array($request->phone)) {
            $phone = implode('', $request->phone);
        } else {
            $phoneArray = str_split($request->phone);
            $phone = implode('', $phoneArray);
        }
        if (strlen($phone) > 10 || strlen($phone) < 10) {
            return back()->with('error', 'Phone number should be 10 digits!');
        }
        $otp = rand(1000, 9999);
        $phone = $phone;
        session()->put('otp', $otp);
        session()->put('phone', $phone);
        $mailContent = MailSmsTemplate::where('code', '=', "otp-send")->first();
        if ($mailContent) {
            $arr = [
                '{OTP}' => $otp,
            ];
            $msg = DynamicMailTemplateFormatter($mailContent->body, $mailContent->variables, $arr);
            //  sendSms($phone,$msg,$mailContent->footer);
        }

        return redirect(route('otp-index') . '?phone=' . $phone);
    }

    protected function validateLogin(Request $request)
    {
        $recaptchaRule = getSetting('recaptcha') == 0 ? 'sometimes' : 'required';

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => $recaptchaRule,
        ]);
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::firstOrCreate([
            'email' => $googleUser->getEmail(),
        ], [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        auth()->login($user);
        return redirect()->to('/home'); // Redirect to the home or dashboard
    }

     public function login(Request $request, $role)
    {
         
        $request['password'] = base64_decode($request->input('password'));
        $this->validateLogin($request);

        if (getSetting('recaptcha') != 0) {
            $recaptchaToken = $request->input('g-recaptcha-response');
            $secretKey = env('RECAPTCHA_SECRET_KEY');

            // Verify reCAPTCHA with Google API
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secretKey,
                'response' => $recaptchaToken,
                'remoteip' => $request->ip(),
            ]);

            // Handle response
            $result = $response->json(); // Directly decode the response as an array

            if ($response->failed()) {
                return redirect()->back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed. Please try again.']);
            }

            // Check if reCAPTCHA is successful and score is above threshold
            if ((!$result['success'] || isset($result['score'])) && $result['score'] < 0.5) {
                return redirect()->back()->withErrors(['recaptcha' => 'reCAPTCHA verification failed.']);
            }
        }

        if (!Role::where('name', $role)->exists()) {
            return back()->with('error', 'Role is not found!.');
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        // return $request->all();
        if ($this->guard()->validate($this->credentials($request))) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password, ])) {
                $this->incrementLoginAttempts($request);
                return back()->with('error', 'This account is not activated.');
            } elseif (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1])) {
                if (auth()->user()->hasRole($role)) {
                    if (getSetting('authentication_mode') == 2) {
                        return $this->validateLoginByNumber();
                    } else {
                        $user = auth()->user();

                        $activity['user_id'] = auth()->user()->id;
                        $activity['ip_address'] = $request->ip();
                        $activity['model'] = User::class;
                        $activity['model_id'] = $user->id;
                        $activity['incident'] = "Login - User {$user->full_name} ({$user->getPrefix()}) logged in successfully";
                        $activity['version'] = getRequestVersion($request);
                        $activity['platform'] = getRequestPlatform($request);
                        logUserActivity($activity);

                        if (AuthRole() == 'User') {
                            return redirect(route('panel.user.dashboard.index'));
                        } else {
                            return redirect(route('panel.admin.dashboard.index'));
                        }
                    }
                } else {
                    $this->incrementLoginAttempts($request);
                    Auth::logout();
                    return back()->with('error', 'Account not found with ' . $role . ' role.');
                }
            }
        } else {
            $this->incrementLoginAttempts($request);
            return redirect()->back()->with('error', 'Credentials do not match our database.');
        }
    }

    public function redirectToLinkedIn()
    {
        return Socialite::driver('linkedin')->redirect();
    }

    public function handleLinkedInCallback()
    {
        $linkedinUser = Socialite::driver('linkedin')->user();

        $user = User::firstOrCreate([
            'email' => $linkedinUser->getEmail(),
        ], [
            'name' => $linkedinUser->getName(),
            'linkedin_id' => $linkedinUser->getId(),
        ]);

        auth()->login($user);
        return redirect()->to('/home'); // Redirect to the home or dashboard
    }
}
