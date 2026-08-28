<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;

class OrderController extends Controller
{
    public $label;
    function __construct()
    {
       $this->label = request()->has('type')
        ? ((request()->get('type') == Order::TYPE_EXPRESS ? 'Express' : 'Subscription') . ' Orders')
        : 'Orders';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // try {
            $length = 10;
            $runShimmer = 0;
            if (checkRequestKey('length')) {
                $length = $request->get('length');
            }
    
            $orders = Order::query();

            if (checkRequestKey('search')) {
                $search = '%' . trim($request->search) . '%';

                $orders->where('id',$search)->where(function ($q) use ($search) {

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

            if (checkRequestKey(['from', 'to'])) {
                $orders->whereBetween(
                    'created_at',
                    [
                        Carbon::parse($request->from)->format('Y-m-d') . ' 00:00:00',
                        Carbon::parse($request->to)->format('Y-m-d') . " 23:59:59"
                    ]
                );
            }

            if (checkRequestKey('type')) {
                 $orders->where('type', $request->type);
            }

            if (checkRequestKey('assign_to')) {
                 $orders->where('assign_to', null);
            }


            if (checkRequestKey('pending_status') && request('pending_status') === 'pending') {
                $orders->whereIn('status', [
                    Order::STATUS_PENDING,
                    Order::STATUS_ASSIGNED,
                    Order::STATUS_INROUTE,
                ]);
            }
            
            
            if (checkRequestKey('schedule_type')) {
                $orders->where('schedule_type', $request->schedule_type);
            }

            if (checkRequestKey('status')) {
                $orders->where('status', $request->status);
            }
            
            if (checkRequestKey('customer_type')) {
                $orders->whereHas('user', function ($q) use ($request) {
                    $q->where('account_type', $request->customer_type);
                });
            }
            
            if (checkRequestKey('payment_status')) {
                $orders->where('payment_status', $request->payment_status);
            }
    
            if (checkRequestKey('user_id')) {
                $orders->where('assign_to', $request->user_id);
            }
    
            if (checkRequestKey('asc')) {
                $orders->orderBy($request->get('asc'), 'asc');
            }
            
            if (checkRequestKey('desc')) {
                $orders->orderBy($request->get('desc'), 'desc');
            }
    
            $label = $this->label;
            $statuses = Order::STATUSES;
            $accountTypes = User::ACCOUNT_TYPES;
            $paymentStatuses = Order::PAYMENT_STATUSES;
    
            if ($request->ajax()) {
                $orders = $orders->latest()->paginate($length);
                return view('panel.admin.orders.load', ['orders' => $orders, 'runShimmer' => $runShimmer])->render();
            } else {
                $orders = $orders->whereId(0)->paginate($length);
                $runShimmer = 1;
            }
            return view('panel.admin.orders.index', compact('orders', 'label', 'runShimmer', 'statuses', 'paymentStatuses','accountTypes'));
        // } catch (\Throwable $e) {
        //     $errorMessage = $e->getMessage();
        //     if ($request->wantsJson()) {
        //         return response()->json(['error' => $errorMessage], 500);
        //     } else {
        //         return redirect()->back()->with('error', $errorMessage)->withInput();
        //     }
        // }
    }

    public function show(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id,'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $order = Order::whereId($id)->firstOrFail();
            $orderItems = $order->orderItems;
            $label = 'Order';
            $statuses = Order::STATUSES;
            
            return view('panel.admin.orders.show', compact('order', 'label', 'statuses', 'orderItems'));
        } catch (\Throwable $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    public function invoice(Request $request, $id)
    {
        // try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }

            $order = Order::with('user')->whereId($id)->firstOrFail();
            $label = 'Order';
            $statuses = Order::STATUSES;

            return view('panel.common.order.invoice.default.index', compact('order', 'label', 'statuses'));

            // return view('panel.admin.orders.invoice', compact('order', 'label', 'statuses', 'product'));

        // } catch (\Throwable $e) {
        //     return back()->with('error', __('ui.error_msg') . $e->getMessage());
        // }
    }



     /**
     * Update Order Status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
   public function updateStatus(Request $request, $id)
{
    try {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        } elseif (env('SECURE_ENDPOINT') == 1) {
            abort(403);
        }

        $order = Order::find($id);

        if (!$order) {
            return back()->with('error', __('ui.order_not_found'));
        }

        $updateData = [
            'status' => $request->status,
        ];

        if ($request->status == Order::STATUS_CANCELLED_BY_ADMIN) {
            $request->validate([
                'rejection_reason' => 'required|string|max:1000',
            ]);

            $updateData['rejection_reason'] = $request->rejection_reason;
        } else {

            $updateData['rejection_reason'] = null;
        }

        $order->update($updateData);

        $data = [
            'user_id' => $order->user_id,
            'title'   => __('ui.order_status') . $order->status_parsed->label,
            'link'    => '#',
            'notification' => __('template.your_order_status_updated', [
                'status' => $order->status_parsed->label
            ]),
        ];

        pushOnSiteNotification($data);

        return back()->with('success', __('ui.status_update'));

    } catch (\Throwable $e) {
        return back()->with('error', __('ui.error_msg') . $e->getMessage());
    }
}

    /**
     * Update Payment Status.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id,'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $order = Order::whereId($id)->first();
            if ($order) {
                $order->update(['payment_status' => $request->payment_status]);
                $data['user_id'] =  $order->user_id;
                $data['title'] = __('ui.payment_status');
                $data['link'] = '#';

                $data['notification'] = __('template.order_payment_status_update', [
                    'auth_user' => auth()->user()->full_name,
                    'order_status' => Order::PAYMENT_STATUSES[$order->payment_status]['label']
                ]);
                pushOnSiteNotification($data);
                return back()->with('success', __('ui.status_update'));
            }
            return back()->with('error', __('ui.order_not_found'));
        } catch (\Throwable $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }
    
    public function updateAssignTo(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id,'decrypt');
            } elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }
            $order = Order::whereId($id)->first();
            if ($order) {
                $order->update([
                    'assign_to' => $request->assign_to,
                    'remark' => $request->remark, 
                ]);
                
                if($order->status == Order::STATUS_PENDING) {
                    $order->update(['status' => Order::STATUS_ASSIGNED]);
                }
                $data['user_id'] =  $order->assign_to;
                $data['title'] = __('ui.order_assigned');
                $data['link'] = '#';

                $data['notification'] = __('template.order_delivery_assigned', [
                    'order_id' => $order->getPrefix(),
                ]);
                pushOnSiteNotification($data);
                return back()->with('success', __('ui.order_assigned_successfully'));
            }
            return back()->with('error', __('ui.order_not_found'));
        } catch (\Throwable $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id,'decrypt');
            }elseif (env('SECURE_ENDPOINT') == 1) {
                abort(403);
            }

            $order = Order::find($id);
            if ($order) {

                $order_items = $order->orderItems;
                if ($order_items->count() > 0) {
                    foreach ($order_items as $order_item) {
                        $order_item->delete();
                    }
                }
                $order->delete();

                return back()->with('success', __('ui.order_deleted'));
            } else {
                return back()->with('error', __('ui.order_not_found'));
            }
        } catch (\Throwable $e) {
            return back()->with('error', __('ui.error_msg') . $e->getMessage());
        }
    }

}
