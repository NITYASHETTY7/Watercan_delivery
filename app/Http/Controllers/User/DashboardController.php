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

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Exception;

class DashboardController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Dashboard';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            return view('panel.user.dashboard.index');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function logoutAs()
    {

        if (auth()->guest()) {
            return redirect()->url('/');
        }

        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {


            if (auth()->user()->hasRole('user')) {
                $role = "?role=User";
            } else {
                $role = "?role=Admin";
            }
            $admin_id = session()->get('admin_user_id');
            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');

            auth()->loginUsingId((int) $admin_id);

            return redirect(route('panel.admin.users.index') . $role);
        } else {

            session()->forget('admin_user_id');
            session()->forget('admin_user_name');
            session()->forget('temp_user_id');

            auth()->logout();
            return redirect('/');
        }
    }
}
