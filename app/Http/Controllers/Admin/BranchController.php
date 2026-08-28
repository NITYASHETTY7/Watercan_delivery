<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BranchRequest;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BranchController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Branches';
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
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
    
            $branches = Branch::query();
    
            if (checkRequestKey('search')) {
                $branches->where(
                    function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->get('search') . '%');
                    }
                );
            }
    
            if (checkRequestKey(['from', 'to'])) {
                $branches->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }
    
            if (checkRequestKey('asc')) {
                $branches->orderBy($request->get('asc'), 'asc');
            }
    
            if (checkRequestKey('desc')) {
                $branches->orderBy($request->get('desc'), 'desc');
            }
    
            $label = $this->label;
    
            if ($request->ajax()) {
                $branches = $branches->latest()->paginate($length);
                return view('panel.admin.branches.load', ['branches' => $branches, 'runShimmer' => $runShimmer])->render();
            } else {
                $branches = $branches->whereId(0)->paginate($length);
                $runShimmer = 1;
            }
            return view('panel.admin.branches.index', compact('branches', 'label', 'runShimmer'));
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
            return view('panel.admin.branches.create.index', compact('label'));
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
    public function store(BranchRequest $request)
    {
        try {
            $chk = Branch::where('name', $request->name)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This branch name is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This branch name is already created!')->withInput();
                }
            }
            
            Branch::create($request->all());
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Branch Created Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Branch Created Successfully')->withInput();
            }
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
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
            $branch = Branch::where('id', $id)->firstOrFail();
            return view('panel.admin.branches.edit.index', compact('branch', 'label'));
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update(BranchRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            if(!$request->is_published) {
                $request['is_published'] =  0; 
            }
            $chk = Branch::where('id', '!=', $id)->where('name', $request->name)->first();
            if($chk) {
                if ($request->wantsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'error',
                        'title' => 'This branch name is already created!'
                    ]);
                } else {
                    return redirect()->back()->with('error', 'This branch name is already created!')->withInput();
                }
            }

            $branch = Branch::where('id', $id)->firstOrFail();
            $branch->update($request->all());

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Success',
                    'title' => 'Branch Updated Successfully'
                ]);
            } else {
                return redirect()->back()->with('success', 'Branch Updated Successfully')->withInput();
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
     * @param  \App\Models\Branch  $branch
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
           
            $branch = Branch::where('id', $id)->firstOrFail();

            // Start Checking Validation
            if($branch->zones->count() > 0) {
                return redirect()->back()->with('error', 'You cannot delete this branch because it is linked to one or more zones.')->withInput();
            }
            // End Checking Validation

            $branch->delete();
            return redirect()->back()->with('success', 'Branch Deleted Successfully')->withInput();
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
                    $branches = Branch::whereIn('id', $request->ids)->get();
                    foreach ($branches as $key => $branch) {
                        if($branch->zones->count() > 0) {
                            if (request()->ajax()) {
                                return response()->json([
                                    'status' => 'error',
                                    'title' => 'You cannot delete this branch '.$branch->getPrefix().' because it is linked to one or more zones.',
                                ]);
                            }
                            return redirect()->back()->with('error', 'You cannot delete this branch because it is linked to one or more zones.')->withInput();
                        }

                        $branch->delete();
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