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
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Jobs\ConvertSubscriptionOrders;
use Exception;
use Razorpay\Api\Api;

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
        $status = $request->status ?? [1, 2, 3];
        $perPage = 10;

        $ordersQuery = auth()->user()
            ->orders()
            ->where('type', Order::TYPE_EXPRESS)
            ->whereIn('status', $status)
            ->latest();

        $orders = $ordersQuery->paginate($perPage);

        // On AJAX fetch, send partial HTML + flag
        if ($request->ajax()) {
            $hasMore = $orders->hasMorePages();
            return response()->json([
                'html' => view('panel.user.order.include.details', compact('orders'))->render(),
                'hasMore' => $hasMore,
                'total' => $ordersQuery->count(),
            ]);
        }

        // Initial load counts for all tabs
        $placedCount = auth()->user()->orders()->whereIn('status', [1, 2, 3])->count();
        $deliveredCount = auth()->user()->orders()->where('status', 4)->count();
        $cancelledCount = auth()->user()->orders()->where('status', 5)->count();

        // Initial page load returns the full Blade view
        return view('panel.user.order.index', compact('orders', 'placedCount', 'deliveredCount', 'cancelledCount', 'perPage'));
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


        return view('panel.user.order.show', compact('order', 'customerData', 'driverData'));
        } catch (Exception $e) {
            return redirect()->route('panel.user.order.index',['app_back'=>true])
                ->with('error', 'Invalid ID.');
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
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

        // } catch (\Throwable $e) {
        //     return back()->with('error', __('ui.error_msg') . $e->getMessage());
        // }
    }

    public function store(Request $request)
    {
        // try {

        $user = auth()->user();
        $carts = Cart::where('user_id', $user->id)
        ->where('qty', '>', 0)->get();

        // Check if the cart is empty (use count() for collections)
        if ($carts->isEmpty()) {
            return redirect()->route('panel.user.cart.index')->with('error', 'Your cart is empty.');
        }

        $userAddress = UserAddress::where('id', $request->address_id)->first();

        if (!$userAddress) {
            // Handle case where address is not found
            return back()->with('error', 'Invalid address selected.');
        }

        $pincode = $userAddress->details['pincode'] ?? "560001";
        $branchZone = getBranchZoneByPincode($pincode);
        $formattedAddress = formatUserAddress($userAddress);
        $branchAddress = getBranchAddressById($branchZone['branch_id'] ?? null);
        $fromAddress = $branchAddress ?: getSetting('frontend_footer_address') ?: formatCompanyAddress();

        // --- Order Type and Total Calculation ---
        $type = null;
        $grandTotal = 0;

        // If 'buyNow', total is the sum of totals already stored in the cart
        if ($request->order_mode === 'buyNow') {
            $type = Order::TYPE_EXPRESS;
            $grandTotal = $carts->sum('total');
        } else {
            // If 'subscription', total comes from the calculated field in the form
            $type = Order::TYPE_SUBSCRIPTION;
            $grandTotal = $request->calculated_total;
        }

        // Ensure grandTotal is numeric and positive
        $grandTotal = max(0, (float)$grandTotal);

        $remark = $request->order_mode === 'subscription'
            ? 'Subscription order successfully placed by user.'
            : 'Express order successfully placed by user.';

       $gstPercent = getSetting('gst_rate') ?? 18;

        // Back-calculation from inclusive price
        $totalGstAmount = $grandTotal - $grandTotal / (1 + $gstPercent / 100);

        $cgstPercent = $gstPercent / 2;
        $sgstPercent = $gstPercent / 2;

        $cgstAmount = $totalGstAmount / 2;
        $sgstAmount = $totalGstAmount / 2;

        $itemTotal = $grandTotal - $totalGstAmount;

        // --- Schedule Logic ---
        $scheduleValue = $request->schedule_value;

        if (!is_array($scheduleValue)) {
            // Handle case if it's comma-separated string like "M,T,W"
            $scheduleValue = explode(',', $scheduleValue);
        }

        // --- Create Order Transaction ID ---
        $txn_id = date('Ymd') . '-' . 'UID' . $user->id . '-' . rand(0, 9999);

        // --- Order Creation ---
        $order = Order::create([
            'user_id' => $user->id,
            'branch_id' => $branchZone['branch_id'] ?? 1,
            'zone_id' => $branchZone['zone_id'] ?? 1,
            'zone_pincode_id' => $branchZone['zone_pincode_id'] ?? 1,

            // Removed: 'product_id', 'qty', 'rate' (now handled by OrderItem)
            'assign_to' => null,
            'parent_order_id' => null,
            'type' => $type,
            'status' => Order::STATUS_PENDING,
            'payment_status' => Order::PAYMENT_STATUS_UNPAID,

            // Used calculated aggregated values
            'tax_amount' => $totalGstAmount,
            'sub_total' => $itemTotal,
            'total' => $grandTotal,

            'from' => $fromAddress ?? null,
            'to' => $formattedAddress ?? null,
            'date' => $request->buy_now_date,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'schedule_type' => $request->schedule_type,
            'schedule_value' => $scheduleValue,
            'txn_no' => $txn_id,
            'remark' => $remark,
            'address_id' => $userAddress->id,
        ]);

        // --- OrderItem Creation Loop ---
        foreach ($carts as $cart) {
            $product = $cart->product;

            // Ensure the product exists before creating the OrderItem
            if ($product) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => Product::class,
                    'item_id' => $product->id,
                    'tax_percent' => $gstPercent,
                    'qty' => $cart->qty,               // Quantity of this specific item
                    'rate' => $product->price,         // Unit price of the product
                    'price' => $cart->total,           // Total price (rate * qty) for this line item
                ]);
            }
        }

        // Log Activity Created
        $data = [
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'model' => 'Order Created',
            'model_id' => $order->id ?? null,
            'incident' => $user->full_name . " (" . $user->getPrefix() . ") Created New Order " . " (" . $order->getPrefix() . ")",
            'version' => getRequestVersion(request()),
            'platform' => getRequestPlatform(request()),
        ];

        logUserActivity($data);

        // Delete Cart Items
        Cart::where('user_id', $user->id)->delete();
        // $carts->delete(); // This is equivalent to the above line

        $sso_token = $user->sso_token;

        // Remove the unused 'product' variable from compact
        return view('panel.user.order.include.payment', compact('user', 'order', 'sso_token'));

        // } catch (\Exception $e) {
        //     return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        // }
    }



    public function payment(Request $request)
    {

        if ($request->razorpay_payment_id) {

            $razorKey = config('services.razorpay.key');
            $razorSecret = config('services.razorpay.secret');

            $api = new Api($razorKey, $razorSecret);
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (!$payment) {
                return redirect()->back()->with('error', 'Payment not found.');
            }

            // Prevent double capture — capture only if not already captured
            if ($payment['status'] !== 'captured') {
                $response = $payment->capture(['amount' => $payment['amount']]);
            } else {
                $response = $payment; // already captured, reuse
            }

            // Find order
            $order = Order::find($request->order_id);

            if (!$order) {
                return redirect()->route('index',['app_back' => true])->withError('We are experiencing technical difficulties processing this order. Please contact Book My Water System with your questions.');
            }

            if ($order->payment_status == Order::PAYMENT_STATUS_PAID) {
                return redirect()->route('index',['app_back' => true])->withError('Transaction denied, This orderID has been already paid. Please contact Book My Water System in case you were charged twice.');
            }   

            // If capture successful
            if ($response && $response['status'] == 'captured') {
                $order->update([
                    'payment_status' => Order::PAYMENT_STATUS_PAID,
                ]);

                if ($order->type == Order::TYPE_SUBSCRIPTION) {
                    ConvertSubscriptionOrders::dispatch($order->id);
                }
            }
            return redirect()->route('panel.user.checkout.thankyou', secureToken($order->id))
                ->with('success', 'Payment successful and order placed.');
        }

        return redirect()->back()->with('error', 'Payment failed: invalid request.');
    }

    public function retryPayment(Request $request, $id)
    {
        try {

            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }
            $order = Order::find($id);

            $user = auth()->user();

            $latestProduct = Product::latest()->first();
            $product = $order->product ?? $latestProduct;


            $sso_token = $user->sso_token;

            return view('panel.user.order.include.payment', compact('user', 'order', 'product', 'sso_token'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        }
    }





    public function updateStatus(Request $request, $id)
    {
        if (!is_numeric($id)) {
            $id = secureToken($id, 'decrypt');
        }
        $order = Order::find($id);
        $user = auth()->user();


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

        // Log Activity Created
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
        // Log Activity Created
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Order updated!.',
            ]);
        }

        return redirect()->back()->with('success', 'Order has been updated successfully.');
    }

    public function newCart(Request $request)
    {
        $user = auth()->user();
        $product = Product::latest()->first();

        if (!$product) {
            return back()->with('error', 'No product found.');
        }

        $price = $product->price;
        $qty = $user->account_type == User::ACCOUNT_TYPE_BUSINESS ? 20 : 1;
        $total = $price * $qty;

        $cart = Cart::create([
            'user_id' => $user->id,
            'type' => Product::class,
            'type_id' => $product->id,
            'qty' => $qty,
            'price' => $price,
            'total' => $total,
        ]);

        $userAddresses = UserAddress::with(['country', 'state', 'city'])
            ->where('user_id', $user->id)
            ->get();

        return view('panel.user.cart.index', compact('product', 'userAddresses', 'cart'));
    }
}
