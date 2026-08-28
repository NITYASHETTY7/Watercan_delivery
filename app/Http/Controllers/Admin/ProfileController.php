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

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Country;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use DateTimeZone;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $countries = Country::all();
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        $google2fa = app('pragmarx.google2fa');
        $auth["google2fa_secret"] = $google2fa->generateSecretKey();
        $QR_Image = $google2fa->getQRCodeInline('zStarter', $user->email, $auth['google2fa_secret']);
        $secret = $auth["google2fa_secret"];
        return view('panel.admin.profile.index', compact('user', 'countries', 'timezones', 'QR_Image', 'secret'));
    }

    /**
     * Update the specified  resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(ProfileRequest $request, $id)
    {
        try {

            if (!is_numeric($id)) {
                $id = decrypt($id);
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $user = User::find($id);

            $response = validateUniqueEmailAndPhone($request, $user->id);
            if ($response) {
                return $response;
            }

            if ($user->preferences != null) {
                if (isset($user->preferences['theme_id']) && $user->preferences['theme_id'] != null) {
                    $theme_id = $user->preferences['theme_id'];
                } else {
                    $theme_id = '1';
                }
            } else {
                $theme_id = '1';
            }

            $preferences = [
                'language' => $request->language ?? 'en',
                'theme_id' => $theme_id,
            ];

            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'timezone' => $request->timezone,
                'phone' => str_replace(' ', '', $request['phone']),
                'country_code' => $request['country_code'],
                'dob' => $request->dob,
                'gender' => $request->gender,
                'country_id' => $request->country_id,
                'bio' => $request->bio,
                'city_id' => $request->city_id,
                'state_id' => $request->state_id,
                'pincode' => $request->pincode,
                'address' => $request->address,
                'preferences' => $preferences,
            ]);
            $user = User::whereId($id)->first();
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $id,
                    'message' => 'Success',
                    'title' => __('ui.profile_updated')
                ]);
            }
            return redirect()->back()->with('success', __('ui.profile_updated'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Update Password the specified  resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(ProfileRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            if ($request->password !== $request->confirm_password) {
                return back()->with('error', __('ui.passwords_do_not_match'));
            }
            User::find($id)->update([
                'password' => Hash::make($request->password),
            ]);
            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $id,
                    'message' => 'Success',
                    'title' => __('ui.password_updated')
                ]);
            }
            return back()->with('success', __('ui.password_updated'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Update Profile the specified  resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateProfileImg(Request $request, $id)
    {


        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
                $user = User::findOrFail($id);
                if ($request->hasFile('avatar')) {
                if ($user->avatar != null) {
                    unlinkFile(storage_path() . '/app/public/backend/users', $user->avatar);
                }
                $image = $request->file('avatar');
                $path = storage_path() . '/app/public/backend/users/';
                $imageName = 'profile_image_' . $user->id.rand(000, 999).'.' . $image->getClientOriginalExtension();
                $image->move($path, $imageName);
                $user->avatar=$imageName;
            } else {
                return back()->with('error', 'Please select an image to upload!');
            }
            
            $user->update(['avatar' => $imageName]);

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $id,
                    'message' => 'Success',
                    'title' => __('ui.profile_image_updated')
                ]);
            }

            return back()->with('success', __('ui.profile_image_updated'));
        } catch (\Exception $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    public function removeProfileImg($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = decrypt($id);
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $user = User::find($id);
            if ($user) {
                if ($user->avatar != null) {
                    unlinkFile(storage_path() . '/app/public/backend/users', $user->avatar);
                    $user->avatar = null;
                }
                $user->update(['avatar' => null]);
                return back()->with('success', 'Profile image removed successfully!');
            } else {
                return back()->with('error', 'User not found!');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'There was an error: ' . $e->getMessage());
        }
    }
}
