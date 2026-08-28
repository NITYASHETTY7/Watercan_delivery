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
use App\Models\User;

use App\Models\MailSmsTemplate;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
// use PragmaRX\Google2FAQRCode\Google2FA;
use Google2FA;

class MFAController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\SupportTicket $supportTicket
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update([
                'google2fa_secret' => $request->secret_key,
                ]);
            return back();
        }
        return back()->with('error', 'User Not Found');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MFA $MFA
     * @return \Illuminate\Http\Response
     */
    public function resetForm()
    {
        return view('auth.mfa.reset.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MFA $MFA
     * @return \Illuminate\Http\Response
     */
    public function mfaEnabled()
    {
        auth()->user()->update([
            'google2fa_secret' => null
            ]);
        Google2FA::logout();
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MFA $MFA
     * @return \Illuminate\Http\Response
     */
    public function mfaReset(Request $request)
    {
        if ($request->password != null && Hash::check($request->password, auth()->user()->password)) {
            $this->mfaEnabled();
            // return back();
            return redirect(url('/'));
        } else {
            return back()->with('error', 'Please Enter valid password');
        }
    }

    public function resendEmailOTP(Request $request) {
        if(auth()->user()->email != null){
            $user = auth()->user();
            $otp = rand(1000,9999);
            $user->update([
                'temp_otp' =>$otp,
            ]);
            $template = MailSmsTemplate::where('code', 'otp-send')->first();
            if ($template) {
                $arr = [
                    "{user_name}" => $user->full_name,
                    "{otp}" => $otp
                ];
                
                if($user && $user->email){
                    $this->sendMailTo($user->email, $template, $arr);
                }
            }
            return response()->json([
                'status' =>'success',
                'msg'=>'Otp send Successfully'
            ]);
        }else{
            return response()->json([
                'status' =>'error',
                'msg'=>'Something went Wrong'
            ]);
        }
    }
    
    public function sendOtpToEmail(Request $request) {
        if(auth()->user()->email != null){
            $user = auth()->user();
            $otp = rand(1000,9999);
            $user->update([
                'temp_otp' =>$otp,
            ]);

            $template = MailSmsTemplate::where('code', 'otp-send')->first();
            if ($template) {
                $arr = [
                    "{user_name}" => $user->full_name,
                    "{otp}" => $otp
                ];
                
                if($user && $user->email){
                    $this->sendMailTo($user->email, $template, $arr);
                }
            }
            return view('auth.mfa.otp.index');
        }
    }
    
    public function verifyOtp(Request $request) {
        try{
            if(auth()->user()){
                $user = auth()->user();
                if($user->temp_otp == $request->otp){
                     $this->mfaEnabled();
                    if(auth()->check()){
                        if(AuthRole() == 'Admin'){
                            return redirect()->route('panel.admin.dashboard.index');
                        }elseif(AuthRole() == 'User'){
                            return redirect()->route('panel.user.dashboard.index');
                        }else{
                            return redirect()->route(strtolower(AuthRole()).'.dashboard.index');
                        }
                    }else{
                        return back();
                    }
                }else{
                    return back()->with('error','Enter Valid OTP. try again');
                }
            }
        }catch(Exception $e){
            return back()->with('error','Something Went Wrong!'.$e->getMessage());
        }
    }
}
