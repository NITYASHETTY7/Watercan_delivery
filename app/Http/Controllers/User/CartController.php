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
use App\Models\Product;
use App\Models\UserAddress;
use App\Models\Cart;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;

class CartController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Cart';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexOld(Request $request)
    {
        try {
            $ssoToken = $request->get('sso_token');

            if ($ssoToken && !auth()->check()) {

                $userFromSso = User::where('sso_token', $ssoToken)->first();

                if ($userFromSso) {
                    auth()->loginUsingId($userFromSso->id);
                }
            }

            $userId = auth()->id();

            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please log in to view your cart.');
            }


            $product = Product::latest()->first();

            if (!$product) {
                return back()->with('error', 'No product found. Please configure the default product.');
            }

            $cart = Cart::where('user_id', $userId)->latest()->first();

            if (!$cart) {

                $user = auth()->user();

                $price = $product->price;

                // Load dynamic minimum quantities from settings
                $minQtyB2C = getSetting('min_qty_b2c') ?? 1; // individual
                $minQtyB2B = getSetting('min_qty_b2b') ?? 1; // business

                // Decide qty based on account type
                $qty = $user->account_type == User::ACCOUNT_TYPE_BUSINESS
                    ? $minQtyB2B
                    : $minQtyB2C;

                // Calculate total
                $total = $price * $qty;


                $cart = Cart::create([
                    'user_id' => $userId,
                    'type' => Product::class,
                    'type_id' => $product->id,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $total,
                ]);
            }

            $userAddresses = UserAddress::with(['country', 'state', 'city'])
                ->where('user_id', $userId)
                ->latest()
                ->get();


            return view('panel.user.cart.index', compact('product', 'userAddresses', 'cart'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        try {
            $ssoToken = $request->get('sso_token');

            if ($ssoToken && !auth()->check()) {
                $userFromSso = User::where('sso_token', $ssoToken)->first();
                if ($userFromSso) {
                    auth()->loginUsingId($userFromSso->id);
                }
            }

            $userId = auth()->id();
            if (!$userId) {
                return redirect()->route('login')->with('error', 'Please log in to view your cart.');
            }

            $user = auth()->user();
            $products = Product::orderBy('id', 'asc')->get();
            $productIds = Product::pluck('id')->toArray();

            if ($products->isEmpty()) {
                return back()->with('error', 'No products found.');
            }
            $minQtyB2C = max((int) (getSetting('min_qty_b2c') ?: 1), 1);
            $minQtyB2B = max((int) (getSetting('min_qty_b2b') ?: 1), 1);


            // Check if cart already exists
            $existingCart = Cart::where('user_id', $userId)->whereIn('type_id', $productIds)->exists();

            // If no cart exists → create items for *all* products
            if (!$existingCart) {
                foreach ($products as $index => $product) {
                    if ($index === 0) {
                        $qty = $user->account_type == User::ACCOUNT_TYPE_BUSINESS
                            ? $minQtyB2B
                            : $minQtyB2C;
                    } else {
                        $qty = 0;
                    }

                    $price = (float) $product->price;
                    $total = $price * $qty;

                    Cart::create([
                        'user_id' => $userId,
                        'type' => Product::class,
                        'type_id' => $product->id,
                        'qty' => $qty,
                        'price' => $price,
                        'total' => $total,
                    ]);
                }
            }

            $carts = Cart::where('user_id', $userId)->get()->take(2);
            $userAddresses = UserAddress::with(['country', 'state', 'city'])
                ->where('user_id', $userId)
                ->latest()
                ->get();

            return view('panel.user.cart.index', [
                'products' => $products,
                'userAddresses' => $userAddresses,
                'carts' => $carts
            ]);
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        }
    }






    public function updateAddress(Request $request)
    {
        try {
            $addressId = $request->addressId;
            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->latest()->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            // Update cart
            $cart->update([
                'address_id' => $addressId,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Cart Address updated!",
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateQuantityOld(Request $request)
    {
        try {

            $user = auth()->user();
            $cart = Cart::where('user_id', $user->id)->latest()->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart not found'], 404);
            }

            $product = $cart->product;
            $price = $product->price;
            $gstPercent = 18;

            // Quantity rules
            $minQtyB2C = getSetting('min_qty_b2c') ?? 1; // individual
            $minQtyB2B = getSetting('min_qty_b2b') ?? 1; // business

            $minQty = $user->account_type == User::ACCOUNT_TYPE_BUSINESS
                ? $minQtyB2B
                : $minQtyB2C;

            $qty = max((int)$request->qty, $minQty); // enforce min qty

            // Calculate totals
            $totalPrice = $price * $qty;
            $gstAmount = $totalPrice - $totalPrice / (1 + $gstPercent / 100);
            $itemTotal = $totalPrice - $gstAmount;

            // Update cart
            $cart->update([
                'qty' => $qty,
                'price' => $price,
                'total' => $totalPrice,
            ]);

            return response()->json([
                'success' => true,
                'qty' => $qty,
                'item_total' => format_price($itemTotal),
                'gst' => format_price($gstAmount),
                'total' => format_price($totalPrice),
                'footer_total' => format_price($totalPrice),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateQuantity(Request $request)
    {
        try {
            $user = auth()->user();

            // 1. Find the specific cart item belonging to this user
            $cart = Cart::where('user_id', $user->id)
                ->where('id', $request->cart_id)
                ->first();

            if (!$cart) {
                return response()->json(['error' => 'Cart item not found'], 404);
            }

            // 2. Determine Min Qty Logic for this specific item
            $minQty = 0; // Default for normal products

            // Logic: If it is the main product (type_id 1), apply B2B/B2C rules
            if ($cart->type_id == 1) {
                $minQtyB2C = getSetting('min_qty_b2c') ?? 1;
                $minQtyB2B = getSetting('min_qty_b2b') ?? 1;

                $minQty = ($user->account_type == User::ACCOUNT_TYPE_BUSINESS)
                    ? $minQtyB2B
                    : $minQtyB2C;
            }

            // 3. Enforce Quantity Limits
            $qty = max((int)$request->qty, $minQty);

            // 4. Update this specific cart item
            $product = $cart->product;
            $price = $product->price;

            // Calculate total for this specific line item
            $lineTotal = $price * $qty;

            $cart->update([
                'qty'   => $qty,
                'price' => $price,
                'total' => $lineTotal,
            ]);

            $allItems = Cart::where('user_id', $user->id)->get();

            $grandTotal = 0;

            foreach ($allItems as $item) {
                $grandTotal += $item->total; // Assuming 'total' column stores (price * qty)
            }


            $gstPercent = getSetting('gst_rate') ?? 18;

            // Total GST amount (inclusive back-calculation)
            $totalGstAmount = $grandTotal - ($grandTotal / (1 + ($gstPercent / 100)));

            // Split GST into CGST & SGST
            $cgstAmount = $totalGstAmount / 2;
            $sgstAmount = $totalGstAmount / 2;

            // Item total excluding tax
            $itemTotalExclTax = $grandTotal - $totalGstAmount;

            return response()->json([
                'success'         => true,
                'qty'             => $qty, // Return the qty of the updated item

                // Return Global Bill Totals
                'bill_item_total' => format_price($itemTotalExclTax),
                'bill_cgst'       => format_price($cgstAmount),
                'bill_sgst'       => format_price($sgstAmount),
                'bill_total'      => format_price($grandTotal),
                'footer_total'    => format_price($grandTotal),
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }




    public function store()
    {
        try {
            // $user = auth()->user();
            // $product = Product::latest()->first();

            // if (!$product) {
            //     return back()->with('error', 'No product found.');
            // }

            // $price = $product->price;

            // // Dynamic minimum quantities
            // $minQtyB2C = getSetting('min_qty_b2c') ?? 1; // Individual
            // $minQtyB2B = getSetting('min_qty_b2b') ?? 1; // Business

            // // Determine qty based on account type
            // $qty = $user->account_type == User::ACCOUNT_TYPE_BUSINESS
            //     ? $minQtyB2B
            //     : $minQtyB2C;

            // // Calculate total
            // $total = $price * $qty;

            // $cart = Cart::create([
            //     'user_id' => $user->id,
            //     'type' => Product::class,
            //     'type_id' => $product->id,
            //     'qty' => $qty,
            //     'price' => $price,
            //     'total' => $total,
            // ]);

            // $userAddresses = UserAddress::with(['country', 'state', 'city'])
            //     ->where('user_id', $user->id)
            //     ->get();

            return redirect()->route('panel.user.cart.index');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . ' ' . $e->getMessage());
        }
    }
}
