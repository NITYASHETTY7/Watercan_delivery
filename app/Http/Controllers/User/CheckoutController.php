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
use App\Models\Order;
use App\Models\UserAddress;
use Exception;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Checkout';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $userAddress = UserAddress::where('id', $request->address_id)->first();
            return view('panel.user.checkout.index', compact('userAddress'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $userId = auth()->id();
            $userAddressId = $request->address_id;
            $userAddress = UserAddress::where('id', $userAddressId)->first();


            $carts = Cart::where('user_id', $userId)
                ->where('qty', '>', 0) // <--- Added this condition
                ->get()
                ->take(2);

            if (empty($carts)) {
                return redirect()->route('panel.user.order.index');
            }

            return view('panel.user.checkout.index', compact('userAddress', 'carts'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }


    public function calculateTotal(Request $request)
    {

        $carts = Cart::where('user_id', auth()->id())->latest()->get()->take(2);
        $pricePerUnit = $carts->sum('total') ?? 0;
        $qty = $carts->sum('qty') ?? 1;

        if ($request->order_mode === 'buyNow') {
            return response()->json([
                'formatted_total' => format_price($pricePerUnit),
                'total' => $pricePerUnit,
                'days' => 1,
                'price' => $pricePerUnit,
                'qty' => $qty,
                'frequency' => null,
            ]);
        }

        if (
            !$request->frequency ||
            !$request->start_date ||
            !$request->end_date
        ) {
            return response()->json([
                'formatted_total' => format_price(0),
                'total' => 0,
                'days' => 0,
                'price' => $pricePerUnit,
                'qty' => $qty,
                'frequency' => null,
            ]);
        }


        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = 0;

        $current = $start->copy();

        switch ($request->frequency) {
            case 'daily':
                $days = $current->diffInDays($end) + 1;
                break;

            case 'weekly':
                $selectedDays = $request->weekly_days ?? []; // e.g. ['M', 'T']
                $days = 0;

                // Map first-letter days to actual weekday abbreviations (Mon, Tue, etc.)
                $dayMap = [
                    'Sun' => ['Sun'], // if you want one 'S' for Sunday only
                    'Mon' => ['Mon'],
                    'Tue' => ['Tue'], // 👈 only Tuesday
                    'Wed' => ['Wed'],
                    'Thurs' => ['Thu'], // optional if you want a second 'T' button for Thursday
                    'Fri' => ['Fri'],
                    'Sat' => ['Sat'], // optional if you want a second 'S' button for Saturday
                ];

                // Expand selection into full weekday abbreviations
                $matchedDays = collect($selectedDays)
                    ->flatMap(fn($d) => $dayMap[$d] ?? [])
                    ->unique()
                    ->values()
                    ->toArray();


                $current = $start->copy();

                while ($current->lte($end)) {
                    if (in_array($current->format('D'), $matchedDays)) {
                        $days++;
                    }
                    $current->addDay();
                }

                break;


            case 'monthly':
                $selectedDates = $request->monthly_dates ?? [];

                $days = 0;

                $current = $start->copy();

                while ($current->lte($end)) {
                    $year = $current->year;
                    $month = $current->month;

                    foreach ($selectedDates as $date) {
                        // return dd($selectedDates);
                        // Ensure valid day for this month
                        if (checkdate($month, $date, $year)) {
                            $day = Carbon::createFromDate($year, $month, $date);
                            $days++;
                            // if ($day->between($start, $end)) {
                            // }
                        }
                    }

                    // Move to next month
                    $current->addMonthNoOverflow()->startOfMonth();
                }
                break;
        }

        $total = $days * $pricePerUnit;



        return response()->json([
            'formatted_total' => format_price($total),
            'total' => $total,
            'days' => $days,
            'price' => $pricePerUnit,
            'qty' => $qty,
            'frequency' => $request->frequency,
        ]);
    }

    // public function calculateTotal(Request $request)
    // {
    //     $cart = Cart::where('user_id', auth()->id())->latest()->first();
    //     $pricePerUnit = $cart->price ?? 0;
    //     $qty = $cart->qty ?? 1;

    //     $start = Carbon::parse($request->start_date);
    //     $end = Carbon::parse($request->end_date);
    //     $days = 0;

    //     switch ($request->frequency) {
    //         case 'daily':
    //             // Check only the actual difference
    //             $days = $start->diffInDaysFiltered(function (Carbon $date) use ($start, $end) {
    //                 return $date->between($start, $end, true); // Include start and end
    //             }, $end);
    //             break;

    //         case 'weekly':
    //             $selectedDays = explode(',', $request->weekly_days) ?? [];

    //             // Map keys from your frontend ('Mon', 'Tues', 'Thurs', etc.) to Carbon day names ('Mon', 'Tue', 'Thu', etc.)
    //             $dayMap = [
    //                 'Sun' => 'Sun',
    //                 'Mon' => 'Mon',
    //                 'Tue' => 'Tue',
    //                 'Wed' => 'Wed',
    //                 'Thurs' => 'Thu',
    //                 'Fri' => 'Fri',
    //                 'Sat' => 'Sat',
    //             ];
    //             $matchedDays = collect($selectedDays)
    //                 ->map(fn($d) => $dayMap[$d] ?? null)
    //                 ->filter()
    //                 ->toArray();

    //             $current = $start->copy();

    //             while ($current->lte($end)) {
    //                 if (in_array($current->format('D'), $matchedDays)) {
    //                     $days++;
    //                 }
    //                 $current->addDay();
    //             }

    //             break;


    //         case 'monthly':
    //             $selectedDates = array_map('intval', explode(',', $request->monthly_dates) ?? []);
    //             $days = 0;

    //             // Iterate over every single day in the range
    //             $current = $start->copy();

    //             while ($current->lte($end)) {
    //                 // Check if the current day's date matches any of the selected dates
    //                 if (in_array($current->day, $selectedDates)) {
    //                     $days++;
    //                 }
    //                 $current->addDay();
    //             }
    //             break;
    //     }

    //     // Safety check: if days is 0, the subscription is not valid
    //     if ($request->order_mode === 'subscription' && $days === 0) {
    //         // Return an error to prevent form submission logic if needed, 
    //         // or let the frontend logic handle the submission prevention.
    //         // For calculation, 0 is correct.
    //     }

    //     $total = $days * $pricePerUnit * $qty;

    //     return response()->json(['formatted_total' => format_price($total), 'total' => $total]);
    // }



    public function thankyou(Request $request, $id)
    {
        try {

            if (!is_numeric($id)) {
                $id = secureToken($id, 'decrypt');
            }

            $order = Order::find($id);

            $expectedDeliveryDate = calculateExpectedDeliveryDate($id);

            return view('panel.user.checkout.thankyou', compact('order', 'expectedDeliveryDate'));
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
