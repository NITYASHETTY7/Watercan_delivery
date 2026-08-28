<!DOCTYPE html>
<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<head>
    <div id="tax-invoice-wrapper">
        <style>
            body {
                font-family: 'DejaVu Sans', sans-serif;
                margin: 0;
                padding: 0;
            }

            /* Define a clean primary color and shadows */
            :root {
                --primary-color: #007bff;
                --text-color: #343a40;
                --light-bg: #f8f9fa;
                --border-color: #e9ecef;
                --font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            /* --- Base Styling --- */
            #tax-invoice-wrapper {
                background-color: var(--light-bg);
                font-family: var(--font-family);
                color: var(--text-color);
                padding: 40px 20px;
                min-height: 100vh;
                box-sizing: border-box;
            }

            #tax-invoice-wrapper * {
                box-sizing: border-box;
            }

            .invoice-container {
                max-width: 900px;
                width: 100%;
                margin: 0 auto;
                /* Center the container */
                background-color: #ffffff;
                padding: 40px;
                border-radius: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            }

            /* Utility classes */
            .text-right {
                text-align: right;
            }

            .text-center {
                text-align: center;
            }

            .fw-bold {
                font-weight: 700;
            }

            .text-muted-sub {
                color: #6c757d;
                font-size: 13px;
            }

            /* --- Header --- */
            .invoice-header {
                overflow: hidden;
                /* Contains floated elements */
                margin-bottom: 25px;
                padding-bottom: 15px;
                border-bottom: 3px solid var(--primary-color);
            }

            .invoice-logo {
                float: left;
            }

            .invoice-logo img {
                max-height: 50px;
                width: auto;
            }

            .invoice-title {
                float: right;
                font-size: 36px;
                font-weight: 800;
                color: var(--primary-color);
                text-align: right;
                margin: 0;
            }

            /* --- Details (Flexbox replacement with inline-block/float) --- */
            .details-grid {
                overflow: hidden;
                /* Contains floats */
                margin-bottom: 30px;
            }

            .details-column {
                width: 48%;
                /* Adjusting for padding/border */
                float: left;
                padding: 0;
                min-width: 250px;
                /* Spacing between columns without 'gap' */
            }

            .details-column:nth-child(even) {
                float: right;
            }

            .details-column h3 {
                font-size: 18px;
                font-weight: 700;
                margin-bottom: 12px;
                color: var(--primary-color);
                border-bottom: 2px solid var(--border-color);
                padding-bottom: 5px;
            }

            .details-column p {
                font-size: 14px;
                color: var(--text-color);
                margin: 6px 0;
                line-height: 1.4;
            }

            /* Ensure address lines break correctly and are not hidden */
            .details-column .address-line,
            .details-column p.mb-0 {
                word-break: break-word;
                /* FIX: Word-break for long addresses */
                overflow-wrap: break-word;
                /* Modern standard */
                white-space: normal;
            }

            .details-column p strong {
                width: 120px;
                display: inline-block;
                font-weight: 600;
                color: #212529;
                flex-shrink: 0;
            }

            /* --- Product Table --- */
            .product-table {
                width: 100%;
                border-collapse: collapse;
                /* Better for PDF rendering */
                margin-top: 30px;
                font-size: 14px;
                border-radius: 8px;
                overflow: hidden;
                border: 1px solid var(--border-color);
                /* Add full border for robustness */
            }

            .product-table th,
            .product-table td {
                padding: 12px 15px;
                border: 1px solid var(--border-color);
                /* Add internal borders */
            }

            .product-table th {
                border-color: var(--primary-color);
                /* Make header borders distinct */
            }

            .product-table thead th {
                background-color: var(--primary-color);
                color: #ffffff;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.5px;
            }

            /* --- Total Summary (Float-based positioning) --- */
            .total-summary-container {
                overflow: hidden;
                /* Contains floated elements */
                margin-top: 25px;
            }

            .total-summary {
                width: 100%;
                max-width: 350px;
                float: right;
                /* Align right */
                border: 1px solid var(--border-color);
                border-radius: 8px;
                overflow: hidden;
                background-color: #fff;
            }

            .total-row {
                overflow: hidden;
                /* Contains floats */
                padding: 10px 15px;
                font-size: 15px;
                border-bottom: 1px dashed var(--border-color);
            }

            .total-row span {
                display: block;
                float: left;
                width: 50%;
            }

            .total-row span:last-child {
                float: right;
                text-align: right;
            }

            .total-row.grand-total {
                font-weight: 700;
                background-color: #e9f0f9;
                color: var(--text-color);
                font-size: 18px;
                padding: 15px;
                border-top: 2px solid var(--primary-color);
            }

            /* --- Notes & Actions --- */
            .invoice-notes {
                margin-top: 40px;
                font-size: 13px;
                color: #6c757d;
                border-top: 1px solid var(--border-color);
                padding-top: 20px;
            }

            .invoice-actions {
                text-align: right;
                margin-top: 40px;
                padding: 20px 0;
                border-top: 1px solid var(--border-color);
            }

            .invoice-btns {
                padding: 10px 16px;
                font-size: 13px !important;
                font-weight: 600;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                margin: 0 5px;
                /* Increase margin for better separation */
                transition: background-color 0.2s, box-shadow 0.2s;
                text-decoration: none;
                /* Ensure anchor tag looks like button */
                display: inline-block;
                /* Essential for padding/sizing */
            }

            .btn-primary {
                background-color: var(--primary-color);
                color: #fff;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                box-shadow: 0 2px 8px rgba(0, 123, 255, 0.3);
            }

            .btn-secondary {
                background-color: #6c757d;
                color: #fff;
            }

            .btn-secondary:hover {
                background-color: #5a6268;
                box-shadow: 0 2px 8px rgba(108, 117, 125, 0.3);
            }

            /* Icon spacing */
            .invoice-btns i {
                margin-right: 5px;
            }

            /* --- Print Specific Styles --- */
            @media print {
                /* ... (keep your existing print styles) ... */
            }

            /* --- Responsive --- */
            @media(max-width:768px) {
                .invoice-container {
                    padding: 20px;
                }

                .invoice-logo,
                .invoice-title {
                    float: none;
                    text-align: center;
                }

                .invoice-header {
                    text-align: center;
                }

                .invoice-title {
                    font-size: 30px;
                    margin-top: 15px;
                }

                .details-column {
                    width: 100%;
                    float: none;
                    margin-bottom: 20px;
                    min-width: auto;
                }

                .total-summary {
                    float: none;
                    max-width: 100%;
                }

                .invoice-actions {
                    text-align: center;
                }

                .invoice-btns {
                    width: 90%;
                    margin: 10px auto;
                    display: block;
                }
            }
        </style>
</head>

<body>



    <div class="invoice-container">
        <div class="invoice-header">
            <div class="invoice-logo">
                <img src="{{ getBackendLogo(getSetting('white_app_logo')) }}" alt="{{ getSetting('app_name') }}">
            </div>
            <h1 class="invoice-title">Tax Invoice</h1>
        </div>

        <div class="details-grid">
            <div class="details-column invoice-info">
                <h3>Invoice Details</h3>
                <p><strong>Invoice No:</strong> <span>{{ $order->getPrefix() }}</span></p>
                <p><strong>Order Date:</strong> <span>{{ $order->created_at->format('d M Y') }}</span></p>
                <p><strong>Delivery Date:</strong>

                    <span>{{ @$order->latestStatusUpdateUserLog ? @$order->latestStatusUpdateUserLog->created_at->format('d M Y') : '-' }}</span>
                </p>
            </div>
            

        </div>

        <div class="details-grid">
            <div class="details-column address-box">
                <h3> From (Pickup/Shipping)</h3>
                <p class="mb-0">{{ $order->from }}</p>
            </div>
            <div class="details-column seller-info">
                <h3>To (Delivery)</h3>
                <p class="mb-0">{{ $order->to }}</p>
            </div>
        </div>

        <table class="product-table">
            <thead>
                <tr>
                    <th>S.No.</th>
                    <th>Item</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Price (per unit)</th>
                    <th class="text-right">Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>{{ @$product->name }}</td>
                    <td class="text-center">{{ @$order->qty }}</td>
                    <td class="text-right">{{ format_price($product->price) }}</td>
                    <td class="text-right">{{ format_price($order->total) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-summary-container">
            <div class="total-summary">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>{{ format_price($order->sub_total) ?? '--' }}</span>
                </div>
                <div class="total-row">
                    <span>IGST (18%)</span>
                    <span>{{ format_price($order->tax_amount) ?? '--' }}</span>
                </div>
                <div class="total-row grand-total">
                    <span>Grand Total</span>
                    <span>{{ format_price(@$order->total ?? '--') }}</span>
                </div>
            </div>
        </div>




        <div class="invoice-notes">
            <p class="mb-0"><strong>Note:</strong> This is a computer-generated Tax Invoice and does not require a
                signature. All prices are inclusive of applicable taxes.</p>
        </div>

        @if (!request()->routeIs('panel.admin.pdf.invoice.dom.pdf'))
            <div class="invoice-actions">
                {{-- Download PDF Button --}}
                <a href="{{ route('panel.admin.pdf.invoice.dom.pdf', secureToken($order->id)) }}"
                    class="btn-primary invoice-btns">
                    <i class="fas fa-file-pdf"></i> Download PDF
                </a>

                {{-- Print Invoice Button --}}
                <button class="btn-secondary invoice-btns" onclick="window.print()">
                    <i class="fas fa-print"></i> Print Invoice
                </button>
            </div>
        @endif
    </div>

</body>



</html>
