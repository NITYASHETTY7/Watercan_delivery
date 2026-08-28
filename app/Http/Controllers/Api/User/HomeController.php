<?php

/**
 *
 * @category ZStarter
 *
 * @ref     Book My Water product
 * @author  <Book My Water info@bookmywater.come>
 * @license <https://watercane-dev.dze-labs.in Book My Water>
 * @version <zStarter: 202306-V1.0>
 * @link    <https://watercane-dev.dze-labs.in>
 */

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Branch;
use App\Models\Zone;
use App\Models\User;
use App\Models\ZonePincodeUser;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;

class HomeController extends Controller
{
   public function customerHome()
    {
        try {
            $today = Carbon::now()->startOfDay();

            // ------------------- ACTIVE SUBSCRIPTIONS ------------------- //
            $activeSubscriptions = Order::whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->where('type', Order::TYPE_SUBSCRIPTION)
                ->select('id', 'schedule_type', 'schedule_value', 'total')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            foreach ($activeSubscriptions as $activeSubscription) {

                $subscriptionItems = $activeSubscription->orderItems;

                $activeSubscription['schedule_type_label'] = Order::SCHEDULE_TYPES[$activeSubscription->schedule_type]['label'] ?? 'Daily';

                $scheduleType = $activeSubscription->schedule_type;
                $scheduleValues = is_array($activeSubscription->schedule_value)
                    ? $activeSubscription->schedule_value
                    : (json_decode($activeSubscription->schedule_value, true) ?? []);

                $nextDelivery = null;
                $now = Carbon::now();

                switch ($scheduleType) {
                    case Order::SCHEDULE_TYPE_DAILY:
                        $nextDelivery = $now->copy()->addDay();
                        break;

                    case Order::SCHEDULE_TYPE_WEEKLY:
                        if (!empty($scheduleValues)) {
                            foreach (range(0, 6) as $i) {
                                $checkDay = $now->copy()->addDays($i)->format('l');
                                if (in_array($checkDay, $scheduleValues)) {
                                    $nextDelivery = $now->copy()->addDays($i);
                                    break;
                                }
                            }
                        }
                        break;

                    case Order::SCHEDULE_TYPE_MONTHLY:
                        if (!empty($scheduleValues)) {
                            sort($scheduleValues);
                            $day = null;

                            foreach ($scheduleValues as $scheduleDay) {
                                if ((int)$scheduleDay >= (int)$now->format('d')) {
                                    $day = (int)$scheduleDay;
                                    break;
                                }
                            }

                            if ($day) {
                                $nextDelivery = $now->copy()->day($day);
                            } else {
                                $nextDelivery = $now->copy()->addMonthNoOverflow()->day((int)$scheduleValues[0]);
                            }
                        }
                        break;

                    default:
                        $nextDelivery = $now;
                }

                $activeSubscription['next_delivery'] = $nextDelivery
                    ? $nextDelivery->format('M d, Y')
                    : $now->format('M d, Y');

                $activeSubscription['cans_count'] =  $subscriptionItems->sum('qty') ?? 1;
                $activeSubscription['address'] =  $activeSubscription->to;
            }

            // ------------------- UPCOMING DELIVERIES ------------------- //
            $upComingDeliveries = Order::whereDate('date', '>=', $today)
                ->where('type', Order::TYPE_EXPRESS)
                ->select('id', 'date', 'schedule_type', 'schedule_value', 'total')
                ->where('user_id', auth()->id())
                ->whereIn('status', [
                        Order::STATUS_PENDING,
                        Order::STATUS_ASSIGNED,
                        Order::STATUS_INROUTE,
                    ])
                ->orderBy('date')
                ->get();

            foreach ($upComingDeliveries as $upComingDelivery) {
                $deliveryItems = $upComingDelivery->orderItems;
                $deliveryDate = Carbon::parse($upComingDelivery->date)->startOfDay();

                // Calculate day difference
                $diffInDays = $today->diffInDays($deliveryDate, false);

                if ($diffInDays === 0) {
                    $nextDelivery = 'Today';
                } elseif ($diffInDays === 1) {
                    $nextDelivery = 'Tomorrow';
                } elseif ($diffInDays === 2) {
                    $nextDelivery = 'Day After Tomorrow';
                } else {
                    $nextDelivery = $deliveryDate->format('M d, Y');
                }

                $upComingDelivery['schedule_type_label'] = Order::SCHEDULE_TYPES[$upComingDelivery->schedule_type]['label'] ?? 'Daily';
                $upComingDelivery['next_delivery'] = $nextDelivery;
                $upComingDelivery['cans_count'] =  $deliveryItems->sum('qty');
                $upComingDelivery['address'] =  $upComingDelivery->to;
            }

            return $this->success([
                'active_subscriptions' => $activeSubscriptions,
                'upcoming_deliveries' => $upComingDeliveries
            ]);
        } catch (\Throwable $th) {
            return $this->error("Something went wrong: " . $th->getMessage());
        }
    }

    public function driverHome()
    {
        try {
             $user = User::where('id', auth()->id())->select('id', 'first_name', 'last_name', 'created_at', 'geo_coordinates')->first();

            $pincodes = ZonePincodeUser::where('user_id', $user->id)

            ->get(['branch_id', 'zone_id']);

            $branch_ids = $pincodes->pluck('branch_id')->toArray();
            $zone_ids = $pincodes->pluck('zone_id')->toArray();


             $branches = Branch::whereIn('id', $branch_ids)
                ->select('id', 'name')
                ->get();

            $zones = Zone::whereIn('id', $zone_ids)
                ->select('id', 'name')
                ->get();

            $pendingDeliveries = Order::whereIn('status', [Order::STATUS_PENDING, Order::STATUS_ASSIGNED, Order::STATUS_INROUTE])
                ->where('assign_to', auth()->id())
                ->where('date', now()->format('Y-m-d'))
                ->where('type', Order::TYPE_EXPRESS)
                ->where('payment_status', Order::PAYMENT_STATUS_PAID)
                ->with('user', function($user) {
                    $user->select('id', 'first_name', 'last_name', 'phone');
                })
                ->select('id', 'user_id', 'date', 'to','assign_to')
                ->limit(20)
                ->get();

            return $this->success([
                'user' => $user,
                'branches' => $branches,
                'zones' => $zones,
                'pending_deliveries' => $pendingDeliveries
            ]);
        } catch (\Throwable $th) {
            return $this->error("Something went wrong: " . $th->getMessage());
        }
    }

}
