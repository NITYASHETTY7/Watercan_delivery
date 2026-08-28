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

class LocationController extends Controller
{
    public function updateLocation(Request $request) {
        try {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $user = auth()->user();

            $geo_coordinates = [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ];

            $user->update([
                'geo_coordinates' => $geo_coordinates,
            ]);

            return $this->success('Location updated successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }
}
