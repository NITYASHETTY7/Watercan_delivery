<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\State;
use App\Models\WebsiteEnquiry;
use App\Models\City;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required',
            'password' => 'required',
        ]);

        $identifier = $request->identifier;
        $password = $request->password;

        // Determine login type: email, phone, or username
        if (str_contains($identifier, '@')) {
            $field = 'email';
        } elseif (is_numeric($identifier)) {
            $field = 'phone';
        } else {
            $field = 'username';
        }

        // Try to find the user by identifier
        $user = User::where($field, $identifier)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => "This {$field} does not exist in our system. Please enter a valid {$field}.",
            ], 404);
        }

        if ($user->status == 0) {
            return response()->json([
                'status' => 'error',
                'message' => "Your account is currently inactive. Please contact support for assistance.",
            ], 401);
        }

        // Attempt authentication
        if (!Auth::attempt([$field => $identifier, 'password' => $password])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Login failed. Please check your credentials and try again.',
            ], 401);
        }

        // Successful login
        $user = Auth::user(); // authenticated user
        $user['prefix_id'] = $user->getPrefix();

        // Generate token (for Laravel Sanctum)
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfully!',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }


    public function loginWithPhone(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'role' => 'required',
        ]);

        $role = "User";

        if($request->role  == "driver"){
            $role = "Driver";
        }

        $otp = rand(1000, 9999);
        $user = User::where('phone', $request->get('phone'))->whereRoleIs($role);

        if (!$user->exists()) {
            return $this->errorOk($role .' does not exist with this number.');
        }

        $user->update([
            'temp_otp' => $otp,
        ]);

        $message = "Welcome to Book My Water!

        Your OTP for login is: {$otp}

        This code is valid for 10 minutes. For your security, do not share this OTP with anyone.";

        sendWhatsappText($request->get('phone'), $message);

        return $this->success($otp);
    }


    public function helpdesk(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|numeric|numeric_phone_length:10,15',
            'message' => 'required|string',
        ]);

        try {
            WebsiteEnquiry::create([
                'name' => $request->full_name,
                'status' => 0,
                'description' => $request->message . ' ' . $request->email . ' ' . $request->phone,
            ]);
            return $this->successMessage('Enquiry Created Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric_phone_length:10,15',
            'otp' => 'required',
        ]);
        $user = User::where('phone', $request->get('phone'))
            ->where('temp_otp', $request->get('otp'));
        if (!$user->exists()) {
            return $this->errorOk('Invalid OTP.');
        }

        $user = $user->first();
        if ($user->phone_verified_at == null) {
            $user->update(['phone_verified_at' => now(), 'status' => 1]);
        }

        auth()->loginUsingId($user->id);
        $accessToken = Auth::user()->createToken('authToken')->plainTextToken;
        $userData = User::whereId(auth()->id())->latest()->first();
        return $this->success([
            'user' => $userData,
            'token' => $accessToken,
        ]);
    }

    public function getStates(Request $request)
    {
        $states =  State::where('country_id', 101)->get(['id', 'name']);
        if ($states->count() > 0) {
            return $this->success($states);
        } else {
            return $this->error('No States Found!');
        }
    }

    public function getCities(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
        ]);
        $cities =  City::where('state_id', $request->state_id)->get(['id', 'name']);
        if ($cities->count() > 0) {
            return $this->success($cities);
        } else {
            return $this->error('No City Found!');
        }
    }

    public function register(Request $request)
    {
        try {
            // Validation
            $request->validate([
                'first_name'    => 'required|string',
                'last_name'     => 'required|string',
                // 'email'         => 'required|email|string',
                // 'password'      => 'nullable|string|min:6',
                'country_code'  => 'nullable|numeric',
                'phone'         => 'required|numeric|numeric_phone_length:10,15|unique:users,phone',
                'role'          => 'required|numeric|exists:roles,id',
                'account_type'  => 'nullable|in:1,2',
            ]);

            // Check if user already exists
            $userExists = User::where('phone', $request->phone)->first();

            // if ($userExists && $userExists->phone_verified_at != null) {
            //     return $this->error('This number already exists in our system!');
            // }

            if ($request->role == 1) {
                return $this->error('Please enter a valid role!');
            }

            // Prepare extra data
            $vehicle_details = [
                'vehicle_name'   => $request->vehicle_name,
                'vehicle_type'   => $request->vehicle_type,
                'vehicle_number' => $request->vehicle_number,
            ];

            $business_payload = [
                'company_name' => $request->company_name,
                'gst_number'   => $request->gst_number,
            ];

            $otp = rand(1000, 9999);

            // Build user record
            $userRecord = [
                'first_name'       => ucfirst($request->first_name),
                'last_name'        => ucfirst($request->last_name),
                'email'            => $request->email,
                'password'         => Hash::make($request->password) ?? $request->phone,
                'phone'            => $request->phone,
                'country_code'     => $request->country_code,
                'dob'              => $request->dob,
                'vehicle_details'  => $vehicle_details,
                'business_payload' => $business_payload,
                'temp_otp'         => $otp,
                'account_type'     => $request->account_type,
                'phone_verified_at'     => now(),
                'status'           => 1,
            ];

            // Update or Create logic
            if ($userExists) {
                // Update unverified user
                $userExists->update($userRecord);
                $user = $userExists;
            } else {
                // Create new user
                $user = User::create($userRecord);
            }

            // Assign Role
            $user->syncRoles([$request->role]);

            // Add prefix ID
            $user['prefix_id'] = $user->getPrefix();

            auth()->loginUsingId($user->id);

            $accessToken = Auth::user()->createToken('authToken')->plainTextToken;
            $userData = User::whereId(auth()->id())->latest()->first();


            // Return success with OTP
            return response()->json([
                'status'  => 'success',
                'message' => 'OTP sent successfully!',
                'data'    => [
                    'otp' => $otp,
                    'user' => $userData,
                    'token' => $accessToken,
                ],
            ]);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
    public function registerOtp(Request $request)
    {

        $user = User::where('phone', $request->get('phone'))->first();

        if ($user) {
            return $this->error('User already exist with this number.', 200);
        }

        $otp = rand(1000, 9999);
        $message = " Welcome to Book My Water!

        Your One-Time Password (OTP) for account verification is: {$otp}

        This OTP is valid for 10 minutes.

        For your security, please do not share this code with anyone.

        Thank you for choosing Book My Water.";

        sendWhatsappText($request->get('phone'), $message);

        return response()->json([
            'status' => 'success',
            'message' => 'Otp sent Successfully!',
            'data' => [
                'otp' => $otp,
            ],
        ]);
    }



    public function resetPassword(Request $request)
    {
        $validData = $request->validate([
            'email' => 'required|email|string',
        ]);
        try {
            $user = User::where('email', $request->get('email'))->first();
            if (!$user) {
                return $this->error('User not found!', 200);
            }
            $otp = rand(1000, 9999);
            $user->temp_otp = $otp;
            $user->save();
            $body = "To reset your password, please use the following One Time Password (OTP):" . $otp . "<br> Thank you for using." . config('app.name');
            StaticMail($user->name, $user->email, "Reset Password in" . config('app.name'), $body, $mail_footer = null);
            return $this->successMessage("OTP Sent Successfully!");
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function resetOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required',
        ]);
        try {
            $user = User::where('phone', $request->get('phone'))->first();
            if (!$user) {
                return $this->error('User not found!', 200);
            }
            $otp = rand(1000, 9999);
            $user->temp_otp = $otp;
            $user->save();

            $message = "Welcome to Book My Water!

            Your OTP for login is: {$otp}

            This code is valid for 10 minutes. For your security, do not share this OTP with anyone.";

            sendWhatsappText($request->get('phone'), $message);
            return $this->success(['otp' => $otp]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage());
        }
    }

    public function changeResetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
        ]);
        try {
            $user = User::where('email', $request->get('email'))->first();
            if (!$user) {
                return $this->error('User not found!', 200);
            }
            $user->temp_otp = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->successMessage("Password updated successfully!");
        } catch (\Throwable $th) {
            return $this->error("Sorry! Failed to reset password! " . $th->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string'
        ]);
        // match old password
        if (Hash::check($request->old_password, Auth::user()->password)) {
            User::find(auth()->user()->id)
                ->update([
                    'password' => Hash::make($request->password)
                ]);
            return $this->successMessage("Password has been changed!");
        }
        return $this->error("Password not matched!");
    }

    public function profile(Request $request)
    {
        $user = auth()->user();
        $user['prefix_id'] = $user->getPrefix();
        return response([

            'status' => 'success',
            'data' => [
                'user' => $user,
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|numeric_phone_length:10,15',
        ]);
        try {
            $user = User::where('id', auth()->id())->first();
            if ($user) {
                // if ($request->hasFile('avatar')) {
                //     if ($user->avatar != null) {
                //         unlinkFile(storage_path() . '/app/public/backend/users', $user->avatar);
                //     }
                //     $image = $request->file('avatar');
                //     $path = storage_path() . '/app/public/backend/users/';
                //     $imageName = 'profile_image_' . $user->id . rand(000, 999) . '.' . $image->getClientOriginalExtension();
                //     $image->move($path, $imageName);
                // } else {
                //     $imageName = collect(explode('/', $user->avatar))->last();
                // }

                // Prepare extra data
                $vehicle_details = [
                    'vehicle_name'   => $request->vehicle_name,
                    'vehicle_type'   => $request->vehicle_type,
                    'vehicle_number' => $request->vehicle_number,
                ];

                $business_payload = [
                    'company_name' => $request->company_name,
                    'gst_number'   => $request->gst_number,
                ];

                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'dob'              => $request->dob,
                    'email'              => $request->email,
                    'vehicle_details'  => $vehicle_details,
                    'business_payload' => $business_payload,
                    'phone' => $request->phone,
                    'gender' => $request->gender,
                    // 'avatar' => $imageName,
                    'account_type'     => $request->account_type,
                ]);
                return $this->successMessage('Profile Updated Successfully!');
            } else {
                return $this->errorOk('This User does\'t exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! " . $e->getMessage());
        }
    }

    public function updateAvatar(Request $request)
    {
        try {
            $user = User::where('id', auth()->id())->select('id', 'first_name', 'last_name', 'avatar')->first();
            if ($user) {
                if ($request->hasFile('avatar')) {
                    if ($user->avatar != null) {
                        unlinkFile(storage_path() . '/app/public/backend/users',$user->avatar);
                    }
                    $image = $request->file('avatar');
                    $path = storage_path() . '/app/public/backend/users/';
                    $imageName = 'profile_image_' . $user->id . rand(000, 999) . '.' . $image->getClientOriginalExtension();
                    $image->move($path, $imageName);
                } else {
                    $imageName = collect(explode('/', $user->avatar))->last();
                }

                $user->update([
                    'avatar' => $imageName,
                ]);

                return $this->success($user);
            } else {
                return $this->errorOk('This User does\'t exist!');
            }
        } catch (\Exception $e) {
            return $this->error("Sorry! Failed to data! " . $e->getMessage());
        }
    }


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success('Logged out successfully!');
    }

    public function updateDeviceToken(Request $request)
    {
        auth()->user()->update([
            'broadcast_token' => $request->get('broadcast_token'),
        ]);
        return $this->successMessage('Updated');
    }

    public function forgetPassword(Request $request)
    {
        // Validate email input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // Attempt to send the reset link
        $response = Password::sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 'success',
                'message' => 'Password reset link sent to your email.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unable to send reset link. Please try again later.',
        ], 500);
    }
}
