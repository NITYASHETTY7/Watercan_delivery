<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
use App\Models\ZonePincode;
use Exception;

class UserAddressController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'UserAddress';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userAddresses = UserAddress::where('user_id', auth()->id())->latest()->get();

            return view('panel.user.address.index', compact('userAddresses'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $userAddress = UserAddress::where('id', $id)->first();

            return view('panel.user.address.edit', compact('userAddress'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Decrypt ID if not numeric
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            
            $userAddress = UserAddress::findOrFail($id);
            
            // Validate fields
            $request->validate([
                'address_1' => 'required|string',
                // 'address_2' => 'string',
                // 'country_id' => 'required|numeric',
                // 'state_id' => 'required|numeric',
                // 'city_id' => 'required|numeric',
                'pincode' => 'required|numeric',
                'type' => 'required|numeric',
            ]);
            
            // Update JSON details column
            $userAddress->details = [
                'type'       => $request->type,
                'address_1'  => $request->address_1,
                'address_2'  => $request->address_2,
                // 'country_id' => $request->country_id,
                // 'state_id'   => $request->state_id,
                // 'city_id'    => $request->city_id,
                'pincode'    => $request->pincode,
            ];


            $userAddress->geo_coordinates = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];
            
            // Save changes
            $userAddress->save();

            return redirect()
                ->route('panel.user.address.index')
                ->with('success', 'Address updated successfully.');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        }
    }



    public function destroy(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }

            $userAddress = UserAddress::findOrFail($id);

            // Delete the user
            $userAddress->forceDelete();

            return redirect()
                ->route('panel.user.address.index')
                ->with('success', 'Address deleted successfully.');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }



    public function checkPincode(Request $request)
    {
        $pincode = $request->input('pincode');

        $exists = ZonePincode::where('pincode', $pincode)->exists();

        return response()->json(['exists' => $exists]);
    }



    public function store(Request $request)
    {
        try {
            $request->validate([
                'address_1' => 'required|string',
                // 'address_2' => 'string',
                // 'country_id' => 'required|numeric',
                // 'state_id' => 'required|numeric',
                // 'city_id' => 'required|numeric',
                'pincode' => 'required|numeric',
                'type' => 'required|numeric',
            ]);
            
            $user = auth()->user();
            
            $details = [
                'type' => $request->type,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                // 'country_id' => $request->country_id,
                // 'state_id' => $request->state_id,
                // 'city_id' => $request->city_id,
                'pincode' => $request->pincode,
            ];
            
            $geo_coordinates = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];
            
            // Add User Address
            $userAddress =  UserAddress::create([
                'user_id' => $user->id,
                'details' => $details,
                'is_primary' => 1,
                'geo_coordinates' => $geo_coordinates
            ]);

            // End User Address

            return back()->with('success', 'Address Created Successfully!');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
