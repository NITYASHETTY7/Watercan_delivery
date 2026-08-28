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

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;

class WorldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getStates(Request $request)
    {
        $states = State::whereCountryId($request->country_id)->orderBy('name', 'ASC')->get();
        $html = '<option value="" readonly>Select State</option>';
        foreach ($states as $index => $state) {
            $html .= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }
        return $html;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCities(Request $request)
    {
        $cities = City::whereStateId($request->state_id)->orderBy('name', 'ASC')->get();
 
        $html = '<option value="" readonly>Select City</option>';
        foreach ($cities as $index => $city) {
            $html .= '<option value="' . $city->id . '">' . $city->name . '</option>';
        }
        return $html;
    }
}
