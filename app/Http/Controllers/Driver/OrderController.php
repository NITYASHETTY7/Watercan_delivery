<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Defenzelite product
 * @author  <Defenzelite hq@defenzelite.com>
 * @license <https://www.defenzelite.com Defenzelite Private Limited>
 * @version <zStarter: 202402-V2.0>
 * @link    <https://www.defenzelite.com>
 */

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Exception;

class OrderController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Order';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $perPage = 10;
            $statusFilter = $request->status ?? 'pending';
            $query = Order::where('assign_to', auth()->id());

            if ($statusFilter === 'pending') {
                $query->whereNotIn('status', [
                    Order::STATUS_DELIVERED,
                    Order::STATUS_CANCELLED,
                ]);
            } elseif ($statusFilter === 'completed') {
                $query->where('status', Order::STATUS_DELIVERED);
            } else {
                $query->whereNotIn('status', [
                    Order::STATUS_CANCELLED,
                ]);
            }

            $orders = $query->latest()->paginate($perPage);
            if ($request->ajax()) {
                return response()->json([
                    'html' => view('panel.driver.order.partials.order-list', compact('orders'))->render(),
                    'hasMore' => $orders->hasMorePages(),
                ]);
            }

            return view('panel.driver.order.index', compact('orders', 'perPage'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function show(Request $request, $id)
    {
        try {
            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $order = Order::find($id);

            $customerCoordinates = $order->address->geo_coordinates ?? null;
            $driverCoordinates = $order->assignTo->geo_coordinates ?? null;

            $customerData = [
                'lat' => $customerCoordinates['latitude'] ?? null,
                'lng' => $customerCoordinates['longitude'] ?? null,
                'address' => $order->user->address ?? 'Unknown address',
            ];

            $driverData = [
                'lat' => $driverCoordinates['latitude'] ?? null,
                'lng' => $driverCoordinates['longitude'] ?? null,
                'vehicle' => $order->assignTo->vehicle_details ?? 'Vehicle info unavailable',
            ];

            return view('panel.driver.order.show', compact('order', 'customerData', 'driverData'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $order = Order::find($id);
        $user = auth()->user();

        if ((int) $request->status === Order::STATUS_DELIVERED) {
            $request->validate([
                'delivery_challan' => 'required|file|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            ]);
            if ($request->hasFile('delivery_challan')) {
                $file = $request->file('delivery_challan');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $collectionName = 'delivery_challan';
                $this->uploadFileInMedia($order, $file, $collectionName);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Delivery Challan file is required to confirm delivery.',
                ]);
            }
        }



        if ($order->status == Order::STATUS_DELIVERED) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled because it has already been delivered.',
            ]);
        }

        if ($order->status == Order::STATUS_CANCELLED) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be cancelled because it is already cancelled.',
            ]);
        }
        $order->status = $request->status;
        $order->save();

        $data = [
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'model' => 'Order Status Updated',
            'model_id' => $order->id ?? null,
            'incident' => $user->full_name . " (" . $user->getPrefix() . ") Updated the Order " . " (" . $order->getPrefix() . ")",
            'version'    => getRequestVersion(request()),
            'platform'   => getRequestPlatform(request()),
        ];
        logUserActivity($data);


        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order updated!.',
            ]);
        }

        return redirect()->back()->with('success', 'Order has been Updated successfully.');
    }

    public function updateDeliveryStatus(Request $request, $id)
    {
        // 1. Decrypt ID and Find Order
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }

        $order = Order::find($id);
        $user = auth()->user();

        // Safety check: Order status is already delivered
        if ((int) $order->status === Order::STATUS_DELIVERED) {
            return response()->json([
                'success' => false,
                'message' => 'This order has already been marked as delivered.',
            ]);
        }

        // 2. Validation (Specific to delivery challan)
        $request->validate([
            'delivery_challan' => 'required|file|mimes:jpeg,png,jpg|max:5120', // Max 5MB
        ]);

        // 3. File Upload and Status Update
        try {
            if ($request->hasFile('delivery_challan')) {
                $file = $request->file('delivery_challan');
                $collectionName = 'delivery_challan';

                // Assuming $this->uploadFileInMedia handles file storage (e.g., Spatie Media Library)
                $this->uploadFileInMedia($order, $file, $collectionName);
            }

            // Set status to DELIVERED
            $order->status = Order::STATUS_DELIVERED;
            $order->save();
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during file upload or status update.',
            ], 500);
        }

        // 4. Log Activity
        $data = [
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'model' => 'Order Status Updated',
            'model_id' => $order->id ?? null,
            'incident' => $user->full_name . " (" . $user->getPrefix() . ") Updated the Order " . " (" . $order->getPrefix() . ") status to Delivered.",
            'version'    => getRequestVersion(request()),
            'platform'   => getRequestPlatform(request()),
        ];

        logUserActivity($data);


        // 5. Response
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order successfully marked as delivered and challan uploaded!',
            ]);
        }

        return redirect()->back()->with('success', 'Order has been marked as delivered successfully.');
    }
}
