<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Log;

class ConvertSubscriptionOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected ?int $order_id;

    public function __construct(?int $order_id = null)
    {
        $this->order_id = $order_id;
    }

    public function handle()
    {
        try {
            $today = Carbon::now()->startOfDay();
            $convertedCount = 0;

            $orders = Order::query();

            // Filter by order ID if provided
            if ($this->order_id !== null) {
                $orders->where('id', $this->order_id);
            }

            $orders = $orders->where('type', Order::TYPE_SUBSCRIPTION)
                ->whereDate('end_date', '>=', $today)
                ->whereIn('status', [Order::STATUS_PENDING, Order::STATUS_ASSIGNED])
                ->where(function ($query) use ($today) {
                    $query->whereNull('last_sync_at')
                        ->orWhere('last_sync_at', '<', $today);
                })
                ->get();


            Log::info("ConvertSubscriptionOrders Job started. Found {$orders->count()} subscription orders.");

            foreach ($orders as $order) {
                $startDate = Carbon::parse($order->start_date)->startOfDay();
                $endDate = Carbon::parse($order->end_date)->startOfDay();

                if ($today->lt($startDate) || $today->gt($endDate)) {
                    continue;
                }

                $targetDate = $today->copy()->startOfDay();

                if (!$targetDate->between($startDate, $endDate, true)) {
                    continue;
                }

                if ($this->isDeliveryScheduledOn($order, $targetDate)) {
                    $existOrder = Order::where('type', Order::TYPE_EXPRESS)
                        ->where('parent_order_id', $order->id)
                        ->where('date', $targetDate)
                        ->first();

                    if (!$existOrder) {

                        $this->convertOrderToExpress($order, $targetDate);
                        $order->last_sync_at = now();
                        $convertedCount++;
                    }
                }
            }

            Log::info("ConvertSubscriptionOrders Job finished. Converted {$convertedCount} subscription orders to express orders.");
        } catch (Exception $e) {
            Log::error("Error converting subscriptions: {$e->getMessage()}", ['exception' => $e]);
        }
    }

    /**
     * Determine if the order is scheduled for delivery on the given target date.
     */
    protected function isDeliveryScheduledOn(Order $order, Carbon $targetDate): bool
    {
        switch ($order->schedule_type) {
            case Order::SCHEDULE_TYPE_DAILY:
                return true;

            case Order::SCHEDULE_TYPE_WEEKLY:
                $days = array_map('strtolower', $order->schedule_value ?? []);
                return in_array(strtolower($targetDate->format('l')), $days);

            case Order::SCHEDULE_TYPE_MONTHLY:
                $days = array_map('intval', $order->schedule_value ?? []);
                return in_array($targetDate->day, $days);

            default:
                return false;
        }
    }

    /**
     * Convert a subscription order into an express order for the given date.
     */
    protected function convertOrderToExpress(Order $originalOrder, Carbon $scheduledDate): void
    {
        $expressOrder = $originalOrder->replicate();
        $expressOrder->type = Order::TYPE_EXPRESS;
        $expressOrder->parent_order_id = $originalOrder->id;
        $expressOrder->date = $scheduledDate;
        $expressOrder->schedule_type = null;
        $expressOrder->schedule_value = null;
        $expressOrder->save();

        $originalOrder->load('orderItems'); // Ensure order items are loaded

        foreach ($originalOrder->orderItems as $originalItem) {
            // Replicate the individual OrderItem
            $expressItem = $originalItem->replicate();
            
            // Link the replicated item to the new express order ID
            $expressItem->order_id = $expressOrder->id;
            
            // Save the new item
            $expressItem->save(); 
        }
        

        Log::info("Converted subscription order #{$originalOrder->id} to express order #{$expressOrder->id} for date {$scheduledDate->toDateString()}.");
    }
}
