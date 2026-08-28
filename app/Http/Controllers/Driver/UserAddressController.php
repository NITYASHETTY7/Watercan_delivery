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

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserAddress;
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
            return view('panel.driver.support-tickets.index');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'address_1' => 'required|string',
                'address_2' => 'required|string',
                'country_id' => 'required|numeric',
                'state_id' => 'required|numeric',
                'city_id' => 'required|numeric',
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

            $user->update([
                'geo_coordinates' => $geo_coordinates,
            ]);

            $details = [
                'type' => $request->type,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
                'city_id' => $request->city_id,
                'pincode' => $request->pincode,
            ];
            // Add User Address
            UserAddress::create([
                'user_id' => $user->id,
                'details' => $details,
                'is_primary' => 1,
            ]);
            // End User Address

            return $this->success('Address Updated Successfully!');

            return view('panel.driver.support-tickets.create');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
