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
use Illuminate\Http\Request;
use Exception;
use App\Models\Order;

class SubscriptionController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Subscription';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $query = Order::where('user_id', auth()->id())
                ->where('type', Order::TYPE_SUBSCRIPTION);

            // Determine the status filter from the request, defaulting to 'pending'
            $statusFilter = $request->status ?? 'pending';

            // Filter by status
            switch ($statusFilter) {
                case 'pending':
                    $query->whereIn('status', [
                        Order::STATUS_PENDING,
                        Order::STATUS_ASSIGNED,
                        Order::STATUS_INROUTE,
                    ]);
                    break;
                case 'completed':
                    $query->where('status', Order::STATUS_DELIVERED);
                    break;
                case 'cancelled':
                    $query->where('status', Order::STATUS_CANCELLED);
                    break;
            }
            $subscriptions = $query->orderByDesc('id')->paginate(10);

            // For AJAX requests — return only rendered HTML and pagination flag
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('panel.user.subscriptions.include.partials', compact('subscriptions'))->render(),
                    'hasMore' => $subscriptions->hasMorePages()
                ]);
            }

            // For normal (non-AJAX) page load
            // Pass the initial status filter to the view to correctly activate the tab
            return view('panel.user.subscriptions.index', compact('subscriptions', 'statusFilter'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }


    public function show(Request $request, $id)
    {
        // try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }

            $subscription = Order::with(['branch', 'zone', 'assignTo'])
                ->findOrFail($id);

            $subscriptionOrders = Order::where('user_id', auth()->id())->where('parent_order_id', $subscription->id)->get();

            // get user address details from “to” JSON or DB if needed
            $userAddress = $subscription->to ?? null;

            // Format branch + zone info
            $branchName = $subscription->branch?->name ?? 'N/A';
            $zoneName = $subscription->zone?->name ?? 'N/A';
            $pincode = json_decode($subscription->to, true)['pincode'] ?? '—';

            return view('panel.user.subscriptions.show', compact(
                'subscription',
                'branchName',
                'zoneName',
                'pincode',
                'userAddress',
                'subscriptionOrders'
            ));
        // } catch (Exception $e) {
        //     return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        // }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }

        $subscription = Order::find($id);
        $user = auth()->user();

        // 1. Check if already delivered or cancelled
        if ($subscription->status == Order::STATUS_DELIVERED) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription cannot be cancelled because it has already been completed.',
            ]);
        }

        if ($subscription->status == Order::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription is already cancelled.',
            ]);
        }

        // 2. Update main subscription status
        $subscription->status = Order::STATUS_CANCELLED;
        $subscription->save();

        // 3. Cancel all future subscription orders (child orders)
        Order::where('parent_order_id', $subscription->id)
            ->whereIn('status', [
                Order::STATUS_PENDING,
                Order::STATUS_ASSIGNED,
                Order::STATUS_INROUTE
            ])
            ->update(['status' => Order::STATUS_CANCELLED]);

        // 4. Log activity
        $data = [
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'model' => 'Subscription Cancelled',
            'model_id' => $subscription->id,
            'incident' => $user->full_name . " (" . $user->getPrefix() . ") cancelled subscription (" . $subscription->getPrefix() . ")",
            'version'    => getRequestVersion(request()),
            'platform'   => getRequestPlatform(request()),
        ];
        logUserActivity($data);

        // 5. AJAX response
        return response()->json([
            'success' => true,
            'message' => 'Subscription cancelled successfully!',
        ]);
    }
}
