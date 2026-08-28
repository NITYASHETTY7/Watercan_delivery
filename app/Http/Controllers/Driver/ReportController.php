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
use App\Models\Order;
use Illuminate\Support\Carbon;
use Exception;

class ReportController extends Controller
{
    public $label;
    function __construct()
    {
        $this->label = 'Report';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delivery()
    {
        try {
            $driverId = auth()->id();
            $driver = auth()->user();

            $orders = \App\Models\Order::where('assign_to', $driverId)->get();

            // If no orders found
            if ($orders->isEmpty()) {
                return view('panel.driver.report.delivery', [
                    'summary' => [
                        'total' => 0,
                        'delivered' => 0,
                        'cancelled' => 0,
                        'success_rate' => 0,
                    ],
                    'chartData' => [],
                    'tableData' => [],
                    'updatedOn' => now()->format('d M Y'),
                    'isNewDriver' => true,
                ]);
            }

            // Overall Summary
            $total = $orders->count();
            $delivered = $orders->where('status', \App\Models\Order::STATUS_DELIVERED)->count();
            $cancelled = $orders->where('status', \App\Models\Order::STATUS_CANCELLED)->count();
            $successRate = $total > 0 ? round(($delivered / $total) * 100) : 0;

            // Date Range Fix — include TODAY
            $registeredAt = $driver->created_at->startOfDay();
            $today = now()->startOfDay();

            $daysSinceCreated = $registeredAt->diffInDays($today);

            // Start date should display max 30 days OR from registration date
            $startDate = $daysSinceCreated >= 30
                ? $today->copy()->subDays(29)
                : $registeredAt;

            // Period — include today
            $period = new \DatePeriod(
                $startDate,
                new \DateInterval('P1D'),
                $today->copy()->addDay() // include TODAY
            );

            // Prepare chart arrays
            $chartLabels = [];
            $chartDelivered = [];
            $chartCancelled = [];

            foreach ($period as $date) {
                $carbonDate = \Carbon\Carbon::parse($date);

                $startDay = $carbonDate->copy()->startOfDay();
                $endDay   = $carbonDate->copy()->endOfDay();

                $chartLabels[] = $carbonDate->format('M j');

                $chartDelivered[] = $orders
                    ->whereBetween('created_at', [$startDay, $endDay])
                    ->where('status', \App\Models\Order::STATUS_DELIVERED)
                    ->count();

                $chartCancelled[] = $orders
                    ->whereBetween('created_at', [$startDay, $endDay])
                    ->where('status', \App\Models\Order::STATUS_CANCELLED)
                    ->count();
            }

            // Table Data — also includes TODAY now
            $tableData = collect($period)->map(function ($date) use ($orders) {

                $carbonDate = \Carbon\Carbon::parse($date);

                $startDay = $carbonDate->copy()->startOfDay();
                $endDay   = $carbonDate->copy()->endOfDay();

                $pending = $orders->whereBetween('created_at', [$startDay, $endDay])
                    ->whereIn('status', [
                        \App\Models\Order::STATUS_PENDING,
                        \App\Models\Order::STATUS_ASSIGNED,
                        \App\Models\Order::STATUS_INROUTE
                    ])->count();

                $delivered = $orders->whereBetween('created_at', [$startDay, $endDay])
                    ->where('status', \App\Models\Order::STATUS_DELIVERED)->count();

                $cancelled = $orders->whereBetween('created_at', [$startDay, $endDay])
                    ->where('status', \App\Models\Order::STATUS_CANCELLED)->count();

                return [
                    'date' => $carbonDate->format('d M Y'),
                    'pending' => $pending,
                    'delivered' => $delivered,
                    'cancelled' => $cancelled,
                    'total' => $pending + $delivered + $cancelled,
                ];
            });

            return view('panel.driver.report.delivery', [
                'summary' => [
                    'total' => $total,
                    'delivered' => $delivered,
                    'cancelled' => $cancelled,
                    'success_rate' => $successRate,
                ],
                'chartData' => [
                    'labels' => $chartLabels,
                    'delivered' => $chartDelivered,
                    'cancelled' => $chartCancelled,
                ],
                'tableData' => $tableData,
                'updatedOn' => now()->format('d M Y'),
                'isNewDriver' => false,
            ]);
        } catch (\Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }



    public function revenue()
    {
        try {
            return view('panel.driver.report.revenue');
        } catch (Exception $e) {
            return back()->with('error', __('ui.something_went_wrong') . $e->getMessage());
        }
    }
}
