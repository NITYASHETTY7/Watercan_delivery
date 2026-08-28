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

use DB;
use App\Models\Role;
use Illuminate\Support\Str;
use App\Http\Requests\RoleRequest;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = __('ui.left_sidebar_roles');
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $groups = \DB::table('permissions')
                ->select('group', \DB::raw('GROUP_CONCAT(name) as permission_names'))
                ->groupBy('group')
                ->get()
                ->toArray();
            $roles = Role::groupBy('name')->get();
            $label = $this->label;
            return view('panel.admin.roles.index', compact('groups', 'roles', 'label'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = Role::create([
                'name' => $request->role,
                'display_name' => $request->display_name,
                'description' => $request->description,
                ]);
            if ($request->has('permissions') && $request->has('permissions') != null) {
                $role->syncPermissions($request->permissions);
            }

            if ($role) {
                return back()->with('success', __('ui.role_created'));
            } else {
                return back()->with('error',  __('ui.failed_to_create_role'));
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            $label = Str::singular($this->label);
            return redirect()->back()->with('error', $bug, compact('label'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
            
        }
          $role = Role::find($id);
        if ($role) {
            $role_permission = $role->permissions()->pluck('id')->toArray();
            $allPermissions = DB::table('permissions')
                ->select('group', DB::raw('GROUP_CONCAT(id) as permission_ids'), DB::raw('GROUP_CONCAT(name) as permission_names'))
                ->groupBy('group')
                ->get()
                ->toArray();
            $label = $this->label;
            return view('panel.admin.roles.edit.index', compact('role', 'role_permission', 'allPermissions', 'label'));
        } else {
            return redirect('404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $role = Role::find($id);
            $role->update([
                    'name' => $request->role,
                    'display_name' => $request->display_name,
                    'description' => $request->description,
                ]);
            if ($request->has('permissions') && is_array($request->permissions)) {
                $role->syncPermissions($request->permissions);
            } elseif ($request->permissions === null) {
                $role->detachPermissions($role->permissions->pluck('id')->toArray());
            }
            return redirect()->route('panel.admin.roles.index')->with('success', __('ui.role_info_updated'));
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if ($role) {
            $role->delete();
            $role->detachPermissions($role->permissions->pluck('name'));
            return back()->with('success', __('ui.role_deleted'));
        } else {
            return redirect('404');
        }
    }
}
