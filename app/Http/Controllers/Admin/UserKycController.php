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
use App\Models\UserKyc;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UserKycController extends Controller
{

    public function index(Request $request){
        try{
            $length = 10;
            $runShimmer = 0;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
            $id = $request->get('user_id');

            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif ($request->has('user_id') && env('SECURE_ENDPOINT') === '1') {
                abort(403);   
            }

            $user_kycs = UserKyc::query();
            if (checkRequestKey('search')) {
                $user_kycs->where(
                    function ($q) use ($request) {
                        $q->where('id', 'like', '%' . $request->get('search') . '%')
                            ->orWhere('details', 'like', '%' . $request->get('search') . '%');
                    }
                );
            }


            if($id){
                $user_kycs->where('user_id',$id);
            }

            $user_kycs = $user_kycs->latest()->paginate($length);

            if ($request->ajax()) {
                return view('panel.admin.users.includes.kyc.index', ['user_kycs' => $user_kycs,'runShimmer' => $runShimmer])->render();
            } elseif ($request->has('fetch_data')) {
                return $user_kycs;
            }
            return view('panel.admin.users.includes.kyc.index', compact('user_kycs','runShimmer'));
        }catch(Exception $e){
            return back()->with('error', __('ui.error_msg'). $e->getMessage());
        }

    }


    Public function show($id){
        try{

            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
                
            }

            $userKyc = Userkyc::find($id);
            if(!$userKyc){
                return back()->with('error', __('ui.kyc_not_found'));
            }
            return view('panel.admin.users.includes.kyc.show', compact('userKyc'));
        }catch(Exception $e){
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    public function update(Request $request, $id){
        try{

            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
                
            }

            $userKyc = UserKyc::find($id);
            if (!$userKyc) {
                return back()->with('error', __('ui.kyc_not_found'));
            }
            if($request->status == UserKyc::STATUS_VERIFIED){
                $updatedDetails = array_merge($userKyc->details ?? [], [
                    'remark' => $request->confirmation_remark,
                ]);
            }elseif($request->status == UserKyc::STATUS_REJECTED){
                $updatedDetails = array_merge($userKyc->details ?? [], [
                    'rejection_remark' => $request->rejection_remark,
                ]);
            }

            $userKyc->update([
                'status' => $request->status,
                'details' => $updatedDetails,
            ]);
            return back()->with('success', __('ui.ekyc_updated'));

        }catch(Exception $e){
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
                
            }
            $userkyc = UserKyc::find($id);

            if ($userkyc) {
                $userkyc->delete();
                return back()->with('success', __('ui.user_kyc_deleted'));
            } else {
                return back()->with('error', __('ui.user_kyc_not_found'));
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.error_msg'). $e->getMessage());
        }
    }


      /**
     * update the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id){
        try{

            if(!is_numeric($id)){
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
                
            }

            $userKyc = UserKyc::find($id);
            if(!$userKyc){
                $responseData = [
                    'status' => 'error',
                    'message' => 'Error',
                    'title' => __('ui.kyc_not_found'),
                ];
                return responseOrRedirect($request, $responseData);
            }
           // Notification logic based on KYC status
                $data['user_id'] = $userKyc->user_id;

                if ($request->status == UserKyc::STATUS_VERIFIED) {
                    $updatedDetails = array_merge($userKyc->details ?? [], [
                        'remark' => $request->confirmation_remark,
                    ]);

                    $data['title'] = __('ui.ekyc_submitted');
                    $data['notification'] = __('template.ekyc_approved', [
                        'auth_user' => auth()->user()->full_name,
                        'status' => UserKyc::STATUS_VERIFIED,
                    ]);
                    $data['link'] = '#';
                } elseif ($request->status == UserKyc::STATUS_REJECTED) {
                    // $updatedDetails = array_merge($userKyc->details ?? [], [
                    //     'rejection_remark' => $request->rejection_remark,
                    // ]);
                    $userKyc->delete();

                    $data['title'] = __('ui.ekyc_submitted');
                    $data['notification'] = __('template.ekyc_rejection', [
                        'auth_user' => auth()->user()->full_name,
                        'rejection_reason' => $request->rejection_remark,
                    ]);
                    $data['link'] = '#';
                }

                pushOnSiteNotification($data);

            if(@$userKyc && @$updatedDetails) {
                $userKyc->update([
                    'action_by' => auth()->id(),
                    'status' => $request->status,
                    'details' => $updatedDetails,
                ]);
            }

            $responseData = [
                'status' => 'success',
                'message' => 'Success',
                'title' => __('ui.ekyc_updated'),
            ];

            return responseOrRedirect($request, $responseData);

        }catch(Exception $e){
            $responseData = [
                'status' => 'error',
                'message' => 'Error',
                'title' =>  __('ui.error_msg') . $e->getMessage(),
            ];
            return responseOrRedirect($request, $responseData);
        }
    }

}
