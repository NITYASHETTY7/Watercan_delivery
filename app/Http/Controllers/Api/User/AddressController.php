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
use App\Models\UserAddress;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

  class AddressController extends Controller
{
    public function storeAddress(Request $request) {
        try {

            $request->validate([
                'address_1' => 'required|string',
                'address_2' => 'nullable|string',
                // 'country_id' => 'required|numeric',
                // 'state_id' => 'required|numeric',
                // 'city_id' => 'required|numeric',
                'pincode' => 'required|numeric',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'type' => 'required|numeric|in:1,2',
            ]);

            $user = auth()->user();

            $geo_coordinates = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            // $user->update([
            //     'geo_coordinates' => $geo_coordinates,
            // ]);
            
            $countryId = Country::where('name',$request->country)->first()->id ?? null; 
            $stateId = State::where('name',$request->state)->first()->id ?? null; 
            $cityId = City::where('name',$request->city)->first()->id ?? null; 

            $details = [
                'type' => $request->type,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country' => $countryId,
                'state' => $stateId,
                'city' => $cityId,
                'pincode' => $request->pincode,
            ];
            // Add User Address
            UserAddress::create([
                'user_id' => $user->id,
                'details' => $details,
                'is_primary' => 1,
                'geo_coordinates' => $geo_coordinates,
            ]);
            
            return $this->success('Address Created Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }

    public function updateAddress(Request $request, $id) {
        try {
            $request->validate([
                'address_1' => 'required|string',
                'address_2' => 'nullable|string',
                // 'country_id' => 'required|numeric',
                // 'state_id' => 'required|numeric',
                // 'city_id' => 'required|numeric',
                'pincode' => 'required|numeric',
                'type' => 'required|numeric|in:1,2',
            ]);

            $user = auth()->user();

            $address = UserAddress::where('id', $id)->where('user_id', auth()->id())->first();
            if(!$address) {
                return $this->error('Address not found or access denied!');
            } 
            $countryId = Country::where('name',$request->country)->first()->id ?? null; 
            $stateId = State::where('name',$request->state)->first()->id ?? null; 
            $cityId = City::where('name',$request->city)->first()->id ?? null; 

            $details = [
                'type' => $request->type,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country' => $countryId,
                'state' => $stateId,
                'city' => $cityId,
                'pincode' => $request->pincode,
            ];

            $geo_coordinates = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            // Add User Address
            $address->update([
                'details' => $details,
                'geo_coordinates' => $geo_coordinates
            ]);
            // End User Address

            return $this->success('Address Updated Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }

    public function deleteAddress($id) {
        try {
            $address = UserAddress::where('id', $id)->where('user_id', auth()->id())->first();
            if(!$address) {
                return $this->error('Address not found or access denied!');
            } 

            // Add User Address
            $address->delete();
            // End User Address

            return $this->success('Address Deleted Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }
}
