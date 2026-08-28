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
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Role;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */


    protected $redirectTo = RouteServiceProvider::HOME;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm($role)
    {
        if (!Role::where('name', $role)->exists()) {
            abort(404);
        }
        return view('auth.' . $role . '.register.index', compact('role'));

    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
                'first_name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255','unique:users'],
                'password' => ['required', 'string', 'min:6', 'confirmed'],
                'phone' => [ 'required', 'numeric','digits_between:10,15','unique:users']
            ]);
    }

    public function register(RegisterRequest $request, $role)
    {
        if (!Role::where('name', $role)->exists()) {
            return $request->wantsJson() ? response()->json(['error' => 'Role does\'nt exists'], 401) : back()->with('error', 'Role does\'nt exists')->withInput();
        }
        $this->validator($request->all())->validate();
        $user = User::whereRoleIs($role)->where('email', $request->email)->orWhere('phone', $request->phone)->first();
        if ($user) {
            return $request->wantsJson() ? response()->json(['error' => 'Email or phone number has already been taken.'], 401) : back()->with('error', 'Email or phone number has already been taken.')->withInput();
        }
        event(new Registered($user = $this->create($request->all(), $role)));
        $this->guard()->login($user);
        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data, $role)
    {
        $delegate_access = rand(100000, 999999);
        User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone' => str_replace(' ','',$data['phone']),
            'country_code' => $data['country_code'],
            'password' => Hash::make($data['password']),
            'delegate_access' => $delegate_access,
        ]);
        $userFirst = User::whereEmail($data['email'])->first();
        $userFirst->syncRoles([$role]);
        return $userFirst;
    }
}
