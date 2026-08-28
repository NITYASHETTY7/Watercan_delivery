<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ZonePincodeUserRequest;
use App\Models\ZonePincode;
use App\Models\ZonePincodeUser;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use DB;

class ZonePincodeUserController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Pincode Drivers';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $length = 10;
            $runShimmer = 0;
            $zonePincode = null;
            $label = $this->label;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
            
            $zonePincodeUsers = ZonePincodeUser::query();

            if (checkRequestKey('search')) {
                $zonePincodeUsers->where('id', 'like', $request->search)
                ->orWhereHas('user', function ($userQuery) use ($request) {
                    $search = '%' . trim($request->search) . '%';
                    $userQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', $search)
                        ->orWhere('first_name', 'like', $search)
                        ->orWhere('last_name', 'like', $search)
                        ->orWhere('email', 'like', $search);
                });
            }
            
            if (checkRequestKey('zone_pincode_id')) {
                $zone_pincode_id = $request->zone_pincode_id;
                if (!is_numeric($zone_pincode_id)) {
                    $zone_pincode_id = secureToken($zone_pincode_id, 'decrypt');
                }

                $zonePincode = ZonePincode::where('id', $zone_pincode_id)->first();
                if(!$zonePincode) {
                    return back()->with('error', 'Invalid Zone Pincode ID!');
                }

                $label = $zonePincode->pincode.' '.$this->label;
                $zonePincodeUsers->where('zone_pincode_id', $zone_pincode_id);
            }
    
            if (checkRequestKey(['from', 'to'])) {
                $zonePincodeUsers->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }
    
            if (checkRequestKey('asc')) {
                $zonePincodeUsers->orderBy($request->get('asc'), 'asc');
            }
    
            if (checkRequestKey('desc')) {
                $zonePincodeUsers->orderBy($request->get('desc'), 'desc');
            }
            if ($request->ajax()) {
                $zonePincodeUsers = $zonePincodeUsers->latest()->paginate($length);
                return view('panel.admin.zone_pincode_users.load', ['zonePincodeUsers' => $zonePincodeUsers, 'runShimmer' => $runShimmer])->render();
            } else {
                $zonePincodeUsers = $zonePincodeUsers->whereId(0)->paginate($length);
                $runShimmer = 1;
            }
            return view('panel.admin.zone_pincode_users.index', compact('zonePincodeUsers', 'label', 'runShimmer', 'zonePincode'));
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            if ($request->wantsJson()) {
                return response()->json(['error' => $errorMessage], 500);
            } else {
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $label = Str::singular($this->label);
            return view('panel.admin.zone_pincode_users.create.index', compact('label'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ZonePincodeUserRequest $request)
    {
        try {
            $zone_pincode_id = $request->zone_pincode_id;
            if(!is_numeric($zone_pincode_id)) {
                $zone_pincode_id = secureToken($zone_pincode_id, 'decrypt');
            }
            $zonePincode = ZonePincode::where('id', $zone_pincode_id)->first();
            if(!$zonePincode) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'title' => 'Invalid Zone Pincode ID!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Invalid Zone ID!')->withInput();
                }
            }

            $request['zone_pincode_id'] = $zonePincode->id;
            $request['zone_id'] = $zonePincode->zone_id;
            $request['branch_id'] = $zonePincode->branch_id;

            foreach ($request->user_id as $key => $user_id) {
                $request['user_id'] = $user_id;

                $chk = ZonePincodeUser::where('user_id', $user_id)->where('zone_pincode_id', $zonePincode->id)->first();
                if(!$chk) {
                    ZonePincodeUser::create($request->all());
                }
            }
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Pincode User Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Pincode User Created Successfully')->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $label = Str::singular($this->label);
            $zonePincodeUser = ZonePincodeUser::where('id', $id)->firstOrFail();
            return view('panel.admin.zone_pincode_users.edit.index', compact('zonePincodeUser', 'label'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(ZonePincodeUserRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }

            $zonePincodeUser = ZonePincodeUser::where('id', $id)->firstOrFail();
            $chk = ZonePincodeUser::where('id', '!=', $id)->where('user_id', $request->user_id)
            ->where('zone_pincode_id', $zonePincodeUser->zone_pincode_id)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'title' => 'This Driver is already assigned in this pincode!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This Driver is already assigned in this pincode!')->withInput();
                }
            }

            $zonePincodeUser->update($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Pincode User Updated Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Pincode User Updated Successfully')->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'error',
                    'title' => $errorMessage
                ]);
            }
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Zone  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
           
            $zonePincodeUser = ZonePincodeUser::where('id', $id)->firstOrFail();
            $zonePincodeUser->delete();
            return redirect()->back()->with('success', 'Zone Pincode User Deleted Successfully')->withInput();
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage);
        }
    }

    /**
     * Bulk Action
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkAction(Request $request)
    {
        try {
            $html = [];
            $type = "success";
            if (!isset($request->ids)) {
                return response()->json([
                    'status' => 'error',
                ]);
                return back()->with('error', __('ui.hands_up'));
            }

            switch ($request->action) {
                case ('delete'):
                    ZonePincodeUser::whereIn('id', $request->ids)->get()->each->delete();

                    $msg = 'Bulk delete!';
                    $title =  __('ui.deleted') . " " . count($request->ids) . " " . __('ui.records_successfully');
                    break;
            }

            if (request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'column' => $request->column,
                    'action' => $request->action,
                    'data' => $request->ids,
                    'title' => $title,
                    'html' => $html,
                ]);
            }

            return back()->with($type, $msg);
        } catch (\Throwable $th) {
            return back()->with('error', __('ui.error_msg') . $th->getMessage());
        }
    }
}
