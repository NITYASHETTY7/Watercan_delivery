<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ZoneRequest;
use App\Models\Zone;
use App\Models\Branch;
use App\Models\ZonePincodeUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ZoneController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Zones';
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
            $label = $this->label;
            $runShimmer = 0;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
    
            $zones = Zone::query();
    
            if (checkRequestKey('search')) {
                $zones->where(
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->get('search') . '%');
                    }
                );
            }
            
            $branch = null;
            if (checkRequestKey('branch_id')) {
                $branch_id = $request->branch_id;
                if (!is_numeric($branch_id)) {
                    $branch_id = secureToken($branch_id, 'decrypt');
                }
                $branch = Branch::where('id', $branch_id)->first();
                if(!$branch) {
                    return back()->with('error', 'Invalid Branch ID!');
                }
                $label = $branch->name.' '.$this->label;
                $zones->where('branch_id', $branch_id);
            }
    
            if (checkRequestKey(['from', 'to'])) {
                $zones->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }
    
            if (checkRequestKey('asc')) {
                $zones->orderBy($request->get('asc'), 'asc');
            }
    
            if (checkRequestKey('desc')) {
                $zones->orderBy($request->get('desc'), 'desc');
            }
    
            if ($request->ajax()) {
                $zones = $zones->latest()->paginate($length);
                return view('panel.admin.zones.load', ['zones' => $zones, 'runShimmer' => $runShimmer])->render();
            } else {
                $zones = $zones->whereId(0)->paginate($length);
                $runShimmer = 1;
            }

            return view('panel.admin.zones.index', compact('zones', 'label', 'runShimmer', 'branch'));
        } catch (\Throwable $e) {
            $errorMessage = $e->getMessage();
            if ($request->wantsJson()) {
                return response()->json(['error' => $errorMessage], 500);
            } else {
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }
        }
    }

    public function mapSection(Request $request)
    {
        try {
            $branch_id = $request->branch_id;
            if (!is_numeric($branch_id)) {
                $branch_id = secureToken($branch_id, 'decrypt');
            }
            $selected_pincode_ids = $request->pincode_ids ?? [];
            $branchZones = Zone::where('branch_id', $branch_id)->with('zonePincodes')->get(); 
            $zoneUserIds = ZonePincodeUser::query();
            if(!empty($selected_pincode_ids)) {
                $zoneUserIds->whereIn('zone_pincode_id', $selected_pincode_ids);
            }
            $zoneUserIds = $zoneUserIds->whereIn('zone_id', $branchZones->pluck('id'))
            ->pluck('user_id');
            
            $users = User::whereIn('id', $zoneUserIds)->get();

            $html = view('panel.admin.zones.includes.map_section', [
                'branchZones' => $branchZones, 
                'selected_pincode_ids' => $selected_pincode_ids, 
            ])->render();
            
            return response()->json([
                'html' => $html,
                'users' => $users 
            ]);
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
            return view('panel.admin.zones.create.index', compact('label'));
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
    public function store(ZoneRequest $request)
    {
        try {
            $branch_id = $request->branch_id;
            if(!is_numeric($branch_id)) {
                $branch_id = secureToken($branch_id, 'decrypt');
            }

            $request['branch_id'] = $branch_id;
            $chk = Zone::where('name', $request->name)->where('branch_id', $branch_id)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This zone name is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This zone name is already created!')->withInput();
                }
            }
            
            Zone::create($request->all());
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Created Successfully')->withInput();
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
            $zone = Zone::where('id', $id)->firstOrFail();
            return view('panel.admin.zones.edit.index', compact('zone', 'label'));
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
    public function update(ZoneRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $zone = Zone::where('id', $id)->firstOrFail();
            $chk = Zone::where('name', $request->name)->where('branch_id', $zone->branch_id)->where('id', '!=', $zone->id)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This zone name is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This zone name is already created!')->withInput();
                }
            }

            $zone->update($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Zone Updated Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Zone Updated Successfully')->withInput();
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
           
            $zone = Zone::where('id', $id)->firstOrFail();
            // Start Checking Validation
            if($zone->zonePincodes->count() > 0) {
                return redirect()->back()->with('error', 'You cannot delete this zone because it is linked to one or more pincodes.')->withInput();
            }
            // End Checking Validation
            $zone->delete();
            return redirect()->back()->with('success', 'Zone Deleted Successfully')->withInput();
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
                    // Zone::whereIn('id', $request->ids)->get()->each->delete();
                    $zones = Zone::whereIn('id', $request->ids)->get();
                    foreach ($zones as $key => $zone) {
                        if($zone->zonePincodes->count() > 0) {
                            if (request()->ajax()) {
                                return response()->json([
                                    'status' => 'error',
                                    'title' => 'You cannot delete this zone '.$zone->getPrefix().' because it is linked to one or more pincodes.',
                                ]);
                            }
                            return redirect()->back()->with('error', 'You cannot delete this zone because it is linked to one or more pincodes.')->withInput();
                        }

                        $zone->delete();
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
