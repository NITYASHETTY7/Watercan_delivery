<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=no">

    {{-- @if (request()->routeIs('common.pdf.invoice.dom.pdf'))
        <style>
            /* 0. Core Settings for DOMPDF */
            body {
                font-family: 'DejaVu Sans', sans-serif !important;
                box-sizing: border-box !important;
                /* Crucial: ensures padding doesn't add to total width */
            }

            /* 1. Remove all margins/padding from the page and root elements */
            @page {
                margin: 10px !important;
                padding: 10px !important;
            }

            body,
            html {
                margin: 0px !important;
                padding: 0px !important;
                overflow: hidden !important;
                box-sizing: border-box !important;
            }

            /* 2. Container sizing and padding for PDF */
            #tax-invoice-wrapper,
            .invoice-container {
                margin: 0 !important;
                padding: 10px 25px !important;
                box-shadow: none !important;
                width: 100% !important;
                padding: 2px 12px 5px 2px !important;
                min-width: 100% !important;
                max-width: 100% !important;
                box-sizing: border-box !important;
            }

            /* 3. Grid/Layout Fixes (as before) */
            .details-grid {
                display: block !important;
            }

            .details-column {
                width: 100% !important;
            }

            /* 4. Table Layout Fixes for PDF */
            table.product-table {
                width: 100% !important;
                table-layout: fixed !important;
                border-collapse: collapse !important;
            }

            /* Apply font to header for consistency */
            .invoice-header {
                font-family: 'DejaVu Sans', sans-serif !important;
            }

            /* Hide actions */
            .invoice-actions {
                display: none !important;
            }
        </style>
    @endif --}}

    @if (request()->routeIs('common.pdf.invoice.dom.pdf'))
        <style>
            /* PDF SAFE SETTINGS */
            @page {
                margin: 20px !important;
                /* Increased margin */
            }

            body {
                font-family: 'DejaVu Sans', sans-serif !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            html {
                margin: 0 !important;
                padding: 0 !important;
            }

            /* Outer wrapper */
            #tax-invoice-wrapper {
                margin: 0 !important;
                padding: 15px !important;
                /* FIX: Uniform padding */
                width: 100% !important;
                box-sizing: border-box !important;
                background: #ffffff !important;
                /* prevent grey blocks in pdf */
            }

            /* Main container */
            .invoice-container {
                width: 100% !important;
                max-width: 100% !important;
                margin: 0 !important;
                padding: 20px !important;
                /* FIX: Balanced padding */
                box-shadow: none !important;
                border-radius: 0 !important;
                box-sizing: border-box !important;
            }

            /* Grid fix */
            .details-grid {
                display: block !important;
                width: 100% !important;
            }

            .details-column {
                width: 100% !important;
                margin-bottom: 12px !important;
            }

            /* Table fix */
            table.product-table {
                width: 100% !important;
                border-collapse: collapse !important;
            }

            table.product-table th,
            table.product-table td {
                font-size: 13px !important;
                padding: 8px !important;
                /* FIX: Makes spacing consistent */
            }

            /* Remove web buttons */
            .invoice-actions {
                display: none !important;
            }
        </style>
    @endif



    <style>
        /* Reset */
        html,
        body {
            padding: 0;
            margin: 0;
            width: 100%;
            max-width: 100%;
            overflow-x: hidden !important;
            font-family: Arial, sans-serif;
            -webkit-text-size-adjust: none !important;
        }

        #tax-invoice-wrapper {
            background: #f6f6f9;
            padding: 20px;
            display: flex;
            justify-content: center;
            min-height: 100vh;
        }

        .invoice-container {
            background: #fff;
            width: 100%;
            max-width: 900px;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 20px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        /* HEADER */
        .invoice-header {
            display: flex;
            flex-direction: column;

            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .invoice-title {
            font-size: 26px;
            font-weight: 800;
            color: #007bff;
            text-align: left;
            margin-bottom: 0;
        }

        .invoice-logo img {
            max-height: 55px;
        }

        /* GRID */
        .details-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 25px;
        }

        .details-column {
            flex: 1 1 45%;
            min-width: 240px;
        }

        .details-column h3 {
            font-size: 17px;
            color: #545454;
            margin-bottom: 10px;
            border-bottom: 2px solid #e2e2e2;
            padding-bottom: 4px;
        }

        .details-column p {
            margin: 6px 0;
            font-size: 14px;
            color: #333;
            line-height: 1.4;
            word-break: break-word;
        }

        /* TABLE - FIXED FOR MOBILE */
        .table-responsive {
            width: 100%;
            border: 1px solid #e0e0e0;
            overflow-x: hidden !important;
        }

        table.product-table {
            width: 100% !important;
            border-collapse: collapse;
            table-layout: fixed !important;
            /* prevents overflow */
            min-width: unset !important;
            /* removed 700px */
        }

        table.product-table th {
            background: #f3f4f6;
            color: #000000;
            padding: 12px;
            font-size: 14px;
            white-space: normal !important;
            /* allow wrapping */
            text-align: left;
        }

        table.product-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e2e2;
            font-size: 14px;
            white-space: normal !important;
            word-break: break-word;
        }

        /* TOTALS */
        .total-summary-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
        }

        .total-summary {
            width: 100%;
            max-width: 340px;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            overflow: hidden;
        }

        .total-row {
            padding: 10px 14px;
            border-bottom: 1px dashed #ddd;
            font-size: 15px;
        }

        .grand-total {
            background: #eaf1ff;
            font-size: 18px;
            font-weight: 700;
            border-top: 2px solid #007bff;
        }

        /* NOTES */
        .invoice-notes {
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 13px;
            margin-top: 45px;
            color: #666;
        }

        /* ACTION BUTTONS */
        .invoice-actions {
            margin-top: 35px;
            display: flex;
            justify-content: right;
            gap: 10px;
            flex-wrap: wrap;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }

        .list-disc li{
            list-style: none;
            font-size: 15px;
        }

        .invoice-btns {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #007bff;
            color: #fff;
            padding: 10px 14px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            justify-content: center;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-icon {
            width: 18px;
            height: 18px;
            fill: #fff;
        }

        /* ——————————————
        MOBILE FIXES (NO SCROLL)
        —————————————— */
        @media (max-width: 600px) {

            html,
            body {
                overflow-x: hidden !important;
            }

            #tax-invoice-wrapper {
                padding: 10px !important;
            }

            .invoice-container {
                width: 100% !important;
                max-width: 100% !important;
                padding: 15px !important;
                border-radius: 8px !important;
                box-sizing: border-box !important;
            }

            .invoice-header {
                flex-direction: column !important;
                align-items: flex-start !important;
                text-align: left !important;
                margin-bottom: 10px !important;
            }

            .invoice-title {
                font-size: 22px !important;
                margin-top: 10px !important;
                margin-bottom: 0px !important;
            }

            /* GRID FIX */
            .details-grid {
                display: block !important;
                width: 100% !important;
            }

            .details-column {
                width: 100% !important;
                min-width: 100% !important;
            }


            /* TABLE FIXED (no scroll) */
            table.product-table th,
            table.product-table td {
                font-size: 12px !important;
                padding: 8px !important;
                word-break: break-word !important;
            }

            /* BUTTONS STACK */
            .invoice-actions {
                flex-direction: column !important;
                width: 100% !important;
            }

            table.product-table th:nth-child(1),
            table.product-table td:nth-child(1) {
                width: 75% !important;
            }

            table.product-table th:last-child,
            table.product-table td:last-child {
                width: 25% !important;
                text-align: right !important;
            }

        }
    </style>
</head>
@if ($order->type == App\Models\Order::TYPE_SUBSCRIPTION)
    @php
        $totalDeliveryDays = calculateSubscriptionDeliveryDays(
            $order->start_date,
            $order->end_date,
            $order->schedule_type,
            $order->schedule_value,
        );
    @endphp
@endif

<body>
    <div id="tax-invoice-wrapper">

        <div class="invoice-container">

            <!-- HEADER -->
            <div class="invoice-header">
                <div class="invoice-logo">
                    <img src="{{ getBackendLogo(getSetting('white_app_logo')) }}" alt="{{ getSetting('app_name') }}">
                </div>
                <h1 class="invoice-title">Tax Invoice</h1>
            </div>

            <!-- INVOICE DETAILS -->
            <div class="details-grid">
                {{-- 1. Invoice Details Column (Standard) --}}
                <div class="details-column">
                    <h3>Invoice Details</h3>
                    <p><strong>Invoice No:</strong> {{ $order->getPrefix() }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y') }}</p>

                    <p>
                        <strong>Delivery Date:</strong>
                        @php
                            $expectedDate = calculateExpectedDeliveryDate($order->id);
                        @endphp

                        @if ($expectedDate instanceof \Carbon\Carbon)
                            {{ $expectedDate->format('d M Y') }}
                        @elseif (is_string($expectedDate))
                            {{ $expectedDate }}
                        @else
                            -
                        @endif
                    </p>
                </div>

                {{-- 2. Subscription Details Column (Conditional) --}}
                @if ($order->type == App\Models\Order::TYPE_SUBSCRIPTION)
                    <div class="details-column invoice-info">
                        <h3>Subscription Details</h3>

                        <p class="mb-0">
                            <strong>Timeline:</strong>
                            {{ @\Carbon\Carbon::parse($order->start_date)->format('d/m/Y') ?? '--' }} -
                            {{ @\Carbon\Carbon::parse($order->end_date)->format('d/m/Y') ?? '--' }}
                        </p>

                        <p class="mb-0">
                            <strong>Frequency Type:</strong>
                            {{ @\App\Models\Order::SCHEDULE_TYPES[@$order->schedule_type]['label'] ?? '--' }}
                        </p>

                        {{-- Conditional Schedule Value Display --}}
                        @if ($order->schedule_value && !empty($order->schedule_value))
                            @php
                                // Determine the heading based on the schedule type
                                // Assuming App\Models\Order::SCHEDULE_TYPE_MONTHLY is defined
                                $scheduleHeading = 'Schedule Days'; // Default for weekly/daily

                                if (@$order->schedule_type == App\Models\Order::SCHEDULE_TYPE_MONTHLY) {
                                    $scheduleHeading = 'Schedule Dates';
                                }

                                $schedule = $order->schedule_value;

                                if (is_array($schedule)) {
                                    // Filter out empty/null values if it's an array (e.g., from checkboxes)
                                    $filtered_schedule = array_filter($schedule);
                                } else {
                                    $filtered_schedule = $schedule;
                                }
                                $is_empty_schedule = empty($filtered_schedule);
                            @endphp

                            <p class="mb-0">
                                <strong>{{ $scheduleHeading }}:</strong>

                                @if (!$is_empty_schedule)
                                    @if (is_array($filtered_schedule))
                                        {{ implode(', ', $filtered_schedule) }}
                                    @else
                                        {{ $filtered_schedule }}
                                    @endif
                                @else
                                    Every Day
                                @endif
                            </p>
                        @endif
                        {{-- End Conditional Schedule Value Display --}}

                    </div>
                @endif
            </div>

            <!-- ADDRESS -->
            <div class="details-grid">
                <div class="details-column">
                    <h3>From (Pickup/Shipping)</h3>
                    <p>{!! $order->from !!}</p>
                </div>

                <div class="details-column">
                    <h3>To (Delivery)</h3>
                    <p>{{ $order->to }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="product-table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($order->orderItems as $orderItem)
                            @php
                                $product = $orderItem->product;
                                $taxPercent = $orderItem->tax_percent ?? getSetting('tax_rate') ?? 18;
                            @endphp

                            <tr>
                                <!-- ITEM COLUMN -->
                                <td>
                                    <strong>{{ @$product->name }}</strong><br>

                                    {{-- Quantity --}}
                                    Qty: {{ $orderItem->qty }}

                                    {{-- Delivery Days (subscription only) --}}
                                    @if ($order->type == App\Models\Order::TYPE_SUBSCRIPTION)
                                        <br>
                                        Delivery: {{ $totalDeliveryDays ?? '--' }}
                                        {{ ($totalDeliveryDays ?? 0) == 1 ? 'Day' : 'Days' }}
                                    @endif
                                </td>

                                <!-- TOTAL COLUMN -->
                                <td>
                                    {{ format_price($orderItem->price) }}
                                </td>
                            </tr>
                        @endforeach


                        <!-- SUBTOTAL -->
                        <tr>
                            <td><strong>Subtotal</strong></td>
                            <td>{{ format_price($order->sub_total) }}</td>
                        </tr>

                        <tr>
                            @php
                                $cgstPercent = ($taxPercent ?? 18) / 2;
                                $cgstAmount = ($order->tax_amount ?? 0) / 2;
                            @endphp

                            <td><strong>CGST ({{ $cgstPercent }}%)</strong></td>
                            <td>{{ format_price($cgstAmount) }}</td>
                        </tr>


                        <!-- TAX -->
                        <tr>
                            @php
                                $sgstPercent = ($taxPercent ?? 18) / 2;
                                $sgstAmount = ($order->tax_amount ?? 0) / 2;
                            @endphp

                            <td><strong>SGST ({{ $sgstPercent }}%)</strong></td>
                            <td>{{ format_price($sgstAmount) }}</td>
                        </tr>

                        <!-- GRAND TOTAL -->
                        <tr class="grand-total-row">
                            <td><strong>Grand Total</strong></td>
                            <td><strong>{{ format_price($order->total) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

            </div>

         <!-- NOTES & LEGAL INFO -->
        <div class="invoice-notes mt-4 text-gray-700">
            <!-- General Note -->
            <p class="mb-2">
                <strong>Note:</strong> This is a computer-generated invoice and does not require a signature.
            </p>

            <!-- Divider -->
            <hr class="border-t border-gray-300 my-2">

            <!-- Company & Legal Information -->
            <div class="mt-2">
                <p class="font-semibold mb-1">Company & Legal Information:</p>
                <ul class="list-disc ml-2 space-y-1"style="padding-left:0px;">
                    <li style="margin-bottom:0.4rem;"><strong>CIN Number:</strong> {{ getSetting('cin_number') }}</li>
                    <li><strong>FSSAI Number:</strong> {{ getSetting('fssai_number') }}</li>
                </ul>
            </div>
        </div>

            <!-- BUTTONS (only for web, not in PDF) -->
            @if (!request()->routeIs('common.pdf.invoice.dom.pdf'))
                <div class="invoice-actions">
                    @if (AuthRole() == 'User')
                        <a href="external:{{ route('common.pdf.invoice.dom.pdf', secureToken($order->id)) }}"
                            class="invoice-btns">
                            <svg class="btn-icon" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2
                        2 0 0 0 2-2V8l-6-6zM14 9V3.5L19.5 9H14z" />
                            </svg>
                            Download PDF
                        </a>
                    @else
                        <a href="{{ route('common.pdf.invoice.dom.pdf', secureToken($order->id)) }}"
                            class="invoice-btns">
                            <svg class="btn-icon" viewBox="0 0 24 24">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2
                        2 0 0 0 2-2V8l-6-6zM14 9V3.5L19.5 9H14z" />
                            </svg>
                            Download PDF
                        </a>
                    @endif


                    {{-- <button class="invoice-btns btn-secondary" onclick="window.print()">
                        <svg class="btn-icon" viewBox="0 0 24 24">
                            <path d="M19 8H5a2 2 0 0 0-2 2v6h4v4h10v-4h4v-6a2
                        2 0 0 0-2-2zm-3 10H8v-4h8v4zM17 3H7v4h10V3z" />
                        </svg>
                        Print Invoice
                    </button> --}}
                </div>
            @endif

        </div>
    </div>
</body>

</html>
