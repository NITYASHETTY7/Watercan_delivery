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

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class DOMpdfController extends Controller
{


    public function index($orderId)
    {
        // try {
        if (!is_numeric($orderId)) {
            $orderId = decrypt($orderId);
        }

        $order = Order::with('user')->findOrFail($orderId);
        $user = auth()->user();

        // Generate the PDF
        $pdf = Pdf::loadView('panel.common.order.invoice.default.index', [
            'order' => $order,
            'user' => $user,
            'is_pdf' => true,
        ])
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'DejaVu Sans', // MUST be here
            ]);

        // Return download
        return $pdf->download('invoice_' . $order->txn_no . '.pdf');
        // } catch (\Exception $e) {
        //     return response()->json(['error' => 'Failed to generate PDF: ' . $e->getMessage()], 500);
        // }
    }
}
