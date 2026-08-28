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
use App\Models\UserKyc;

class UserKycController extends Controller
{
    public function updateKyc(Request $request) {
        try {
            $request->validate([
                'driving_licence' => 'required|file',
                // 'signature' => 'required|file',
                // 'seal' => 'required|file',
            ]);

            $user = auth()->user();
            if ($request->hasFile("driving_licence")) {
                $driving_licence = $this->uploadFile($request->file("driving_licence"), "kyc")->getFilePath();
            } else {
                $driving_licence = null;
            }
            // if ($request->hasFile("signature")) {
            //     $signature = $this->uploadFile($request->file("signature"), "kyc")->getFilePath();
            // } else {
            //     $signature = null;
            // }
            // if ($request->hasFile("seal")) {
            //     $seal = $this->uploadFile($request->file("seal"), "kyc")->getFilePath();
            // } else {
            //     $seal = null;
            // }   

            UserKyc::create([
                'user_id' => auth()->id(),
                'status' => 0,
                'driving_licence' => $driving_licence,
                // 'signature' => $signature,
                // 'seal' => $seal,
            ]);

            return $this->success('Kyc Submitted Successfully!');
        } catch (\Throwable $th) {
            return $this->error("Something wend wrong " . $th->getMessage());
        }
    }
}
