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
use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use DB;

class ReportController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Order Tracking Pipeline Report';
    }

    public function index(Request $request)
    {
        try {
            $length = 10;
            $label = $this->label;

            // Get requested dates
            $from_date = $request->from_date;
            $to_date = $request->to_date;

            // Default: current month's start & end
            if (empty($from_date) || empty($to_date)) {
                $from_date = now()->startOfMonth()->toDateString();
                $to_date = now()->endOfMonth()->toDateString();
            }

            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }

            $orders = Order::query();

            // Search filter
            if (checkRequestKey('search')) {
                $search = '%' . trim($request->search) . '%';

                $orders->where(function ($q) use ($search) {

                    $q->orWhere('id', 'like', $search);

                    // User relation
                    $q->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', $search)
                            ->orWhere('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search)
                            ->orWhere('email', 'like', $search);
                    })
                    // Branch relation
                    ->orWhereHas('branch', function ($branchQuery) use ($search) {
                        $branchQuery->where('name', 'like', $search);
                    })
                    // Zone relation
                    ->orWhereHas('zone', function ($zoneQuery) use ($search) {
                        $zoneQuery->where('name', 'like', $search);
                    })
                    // Zone Pincode relation
                    ->orWhereHas('zonePincode', function ($pincodeQuery) use ($search) {
                        $pincodeQuery->where('pincode', 'like', $search);
                    })
                    // AssignTo relation
                    ->orWhereHas('assignTo', function ($assignQuery) use ($search) {
                        $assignQuery->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', $search)
                            ->orWhere('first_name', 'like', $search)
                            ->orWhere('last_name', 'like', $search)
                            ->orWhere('email', 'like', $search);
                    });
                });
            }

            // Date range filter (with defaults applied)
            $orders->whereBetween('date', [$from_date, $to_date]);

            // Sorting
            if (checkRequestKey('asc')) {
                $orders->orderBy($request->get('asc'), 'asc');
            }

            if (checkRequestKey('desc')) {
                $orders->orderBy($request->get('desc'), 'desc');
            }

             if (checkRequestKey('customer_type')) {
                $orders->whereHas('user', function ($q) use ($request) {
                    $q->where('account_type', $request->customer_type);
                });
            }
            

            // Filter type
            $orders = $orders->where('type', Order::TYPE_EXPRESS)
                            ->latest()
                            ->paginate($length);


            $accountTypes = User::ACCOUNT_TYPES;


            $data = [
                'orders' => $orders,
                'from_date' => $from_date,
                'to_date' => $to_date,
                'label' => $label,
                'accountTypes' => $accountTypes,
            ];

            if ($request->ajax()) {
                return view('panel.admin.reports.load', $data)->render();
            }

            return view('panel.admin.reports.index', $data);
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

}
