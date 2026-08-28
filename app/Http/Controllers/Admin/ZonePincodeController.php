<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ZonePincodeRequest;
use App\Models\ZonePincode;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ZonePincodeController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Zone Pincodes';
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
            $label = $this->label;
            $zone = null;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
    
            $zonePincodes = ZonePincode::query();
    
            if (checkRequestKey('search')) {
                $zonePincodes->where(
                    function ($q) use ($request) {
                        $q->where('pincode', 'like', '%' . $request->get('search') . '%');
                    }
                );
            }
            
            if (checkRequestKey('zone_id')) {
                $zone_id = $request->zone_id;
                if (!is_numeric($zone_id)) {
                    $zone_id = secureToken($zone_id, 'decrypt');
                }

                $zone = Zone::where('id', $zone_id)->first();
                if(!$zone) {
                    return back()->with('error', 'Invalid Zone ID!');
                }

                $label = $zone->name.' '.$this->label;
                $zonePincodes->where('zone_id', $zone_id);
            }
    
            if (checkRequestKey(['from', 'to'])) {
                $zonePincodes->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }
    
            if (checkRequestKey('asc')) {
                $zonePincodes->orderBy($request->get('asc'), 'asc');
            }
    
            if (checkRequestKey('desc')) {
                $zonePincodes->orderBy($request->get('desc'), 'desc');
            }
    
            if ($request->ajax()) {
                $zonePincodes = $zonePincodes->latest()->paginate($length);
                return view('panel.admin.zone_pincodes.load', ['zonePincodes' => $zonePincodes, 'runShimmer' => $runShimmer])->render();
            } else {
                $zonePincodes = $zonePincodes->whereId(0)->paginate($length);
                $runShimmer = 1;
            }
            return view('panel.admin.zone_pincodes.index', compact('zonePincodes', 'label', 'runShimmer', 'zone'));
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
            return view('panel.admin.zone_pincodes.create.index', compact('label'));
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
    public function store(ZonePincodeRequest $request)
    {
        try {
            $zone_id = $request->zone_id;
            if(!is_numeric($zone_id)) {
                $zone_id = secureToken($zone_id, 'decrypt');
            }
            $zone = Zone::where('id', $zone_id)->first();
            if(!$zone) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Error',
                        'title' => 'Invalid Zone ID!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'Invalid Zone ID!')->withInput();
                }
            }
            $chk = ZonePincode::where('pincode', $request->pincode)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This zone pincode is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This zone pincode is already created!')->withInput();
                }
            }
            
            $request['zone_id'] = $zone->id;
            $request['branch_id'] = $zone->branch_id;


            ZonePincode::create($request->all());
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Pincode Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Pincode Created Successfully')->withInput();
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
            $zonePincode = ZonePincode::where('id', $id)->firstOrFail();
            return view('panel.admin.zone_pincodes.edit.index', compact('zonePincode', 'label'));
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
    public function update(ZonePincodeRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $zonePincode = ZonePincode::where('id', $id)->firstOrFail();
            $chk = ZonePincode::where('pincode', $request->pincode)
                ->where('id', '!=', $zonePincode->id)
                ->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This zone pincode is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This zone pincode is already created!')->withInput();
                }
            }

            $zonePincode->update($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Pincode Updated Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Pincode Updated Successfully')->withInput();
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
           
            $zonePincode = ZonePincode::where('id', $id)->firstOrFail();
            // Start Checking Validation
            if($zonePincode->zonePincodeUsers->count() > 0) {
                return redirect()->back()->with('error', 'You cannot delete this pincode because it is linked to one or more driver.')->withInput();
            }
            // End Checking Validation

            $zonePincode->delete();
            return redirect()->back()->with('success', 'Zone Pincode Deleted Successfully')->withInput();
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
                    // ZonePincode::whereIn('id', $request->ids)->get()->each->delete();
                    $zonePincodes = ZonePincode::whereIn('id', $request->ids)->get();
                    foreach ($zonePincodes as $zonePincode) {
                        if($zonePincode->zonePincodeUsers->count() > 0) {
                            if (request()->ajax()) {
                                return response()->json([
                                    'status' => 'error',
                                    'title' => 'You cannot delete this pincode '.$zonePincode->getPrefix().' because it is linked to one or more drivers.',
                                ]);
                            }
                            return redirect()->back()->with('error', 'You cannot delete this pincode because it is linked to one or more drivers.')->withInput();
                        }

                        $zonePincode->delete();
                    }

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
