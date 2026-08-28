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
use App\Http\Requests\PermissionRequest;
use App\Models\Role;
use App\Models\Permission;
use Exception;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
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
                $length = request()->get('length');
            }
            $roles = Role::get();
            $permissions = Permission::query();
            if (checkRequestKey('search')) {
                $permissions->where('name', 'like', '%' . request()->get('search') . '%');
            }
            if (checkRequestKey('asc')) {
                $permissions->orderBy($request->get('asc'), 'asc');
            }

            if (checkRequestKey('desc')) {
                $permissions->orderBy($request->get('desc'), 'desc');
            }
            $allPermissions = $permissions->latest()->paginate($length);
            if ($permissions !== null) {
                if (request()->ajax()) {
                    return view('panel.admin.permissions.load', ['allPermissions' => $allPermissions, 'roles' => $roles])->render();
                }
                return view('panel.admin.permissions.index', compact('roles', 'allPermissions'));
            } else {
                return redirect()->back()->with('error', __('ui.permissions_not_found'));
            }
        } catch (\Exception $e) {
            $bug = $e->getMessage();
            return redirect()->back()->with('error', $bug);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request->permission,
                'group' => $request->group
            ]);
            $permission->roles()->sync($request->roles);
            if ($permission) {
                return redirect()->route('panel.admin.permissions.index')->with('success', __('ui.permission_created'));
            } else {
                return redirect()->route('panel.admin.permissions.index')->with('error', __('ui.failed_to_create_permission'));
            }
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
    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }

            $permission   = Permission::find($id);
            if ($permission) {
                $delete = $permission->delete();
                $perm   = $permission->roles()->delete();
                return redirect(route('panel.admin.permissions.index'))->with('success', __('ui.permission_deleted'));
            } else {
                return redirect('404');
            }
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
