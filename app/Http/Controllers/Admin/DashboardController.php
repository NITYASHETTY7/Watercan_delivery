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
use App\Models\Order;
use App\Models\Role;
use App\Models\SupportTicket;
use Exception;
use DB;

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
            $label = $this->label;
            $user = auth()->user();
            $roles = Role::get();
            $stats = [];
            
            $stats['delivered_orders'] = Order::where('type',Order::TYPE_EXPRESS)->where('status', Order::STATUS_DELIVERED)->count(); 
            $stats['pending_orders'] = Order::where('type',Order::TYPE_EXPRESS)->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_ASSIGNED, Order::STATUS_INROUTE])->count(); 
            $stats['cancelled_orders'] = Order::where('type',Order::TYPE_EXPRESS)->where('status', Order::STATUS_CANCELLED)->count(); 

            $recentExpressOrders = Order::where('type',Order::TYPE_EXPRESS)->where('assign_to',null)->whereIn('status', [Order::STATUS_PENDING])->orderBy('date')->limit(10)->get();

            $recentSubscriptionOrders = Order::where('type',Order::TYPE_SUBSCRIPTION)->where('assign_to',null)->whereIn('status', [Order::STATUS_PENDING])->orderBy('start_date')->limit(10)->get();


            $totalExpressOrders = Order::where('type',Order::TYPE_EXPRESS)->count();

            $totalSubscriptionsOrders = Order::where('type',Order::TYPE_SUBSCRIPTION)->count();

            $topExpressZones = Order::select('zone_id', DB::raw('COUNT(*) as total_orders'))
                ->groupBy('zone_id')
                ->where('type',Order::TYPE_EXPRESS)
                ->orderByDesc('total_orders')
                ->take(5)
                ->with('zone:id,name') 
                ->get();

            $topSubscriptionsZones = Order::select('zone_id', DB::raw('COUNT(*) as total_orders'))
                ->groupBy('zone_id')
                ->orderByDesc('total_orders')
                ->where('type',Order::TYPE_SUBSCRIPTION)
                ->take(5)
                ->with('zone:id,name') 
                ->get();
            

            $supportTickets = SupportTicket::where('status', '!=', SupportTicket::STATUS_RESOLVED)->limit(10)->get();
            
            $ordersByDay = Order::select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->whereBetween('date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('total_orders', 'date')
            ->toArray();

            // Fill missing days with 0
            $chartLabels = [];

            $chartData = [];

            for ($i = 6; $i >= 0; $i--) {
                $day = now()->subDays($i)->format('Y-m-d');
                $chartLabels[] = now()->subDays($i)->format('d M'); 
                $chartData[] = $ordersByDay[$day] ?? 0;
            }

            return view('panel.admin.dashboard.index', compact(
                'user',
                'label',
                'roles',
                'stats',
                'recentExpressOrders',
                'recentSubscriptionOrders',
                'topExpressZones',
                'topSubscriptionsZones',
                'totalExpressOrders',
                'totalSubscriptionsOrders',
                'supportTickets',
                'chartData',
                'chartLabels'
            ));
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
