<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Jobs\ConvertSubscriptionOrders;

class CronController extends Controller
{
    /**
     * Handle the cron job logic.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    // ========= This cron convert 3 days before subscription orders to express orders ========
    public function createExpressOrders()
    {
        try {
            // Job Dispatch
            ConvertSubscriptionOrders::dispatch();
            
            // Return success response
            return $this->success('Express Orders Created Successfully!');
        } catch (Exception $e) {
            // Handle exceptions and return error response
            return $this->error("Error: " . $e->getMessage());
        }
    }
}
