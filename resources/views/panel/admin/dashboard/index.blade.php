@extends('layouts.main')
@section('title', __('ui.left_sidebar_dashboard'))

@section('content')

    <head>
        <style>
            /* --- Overview Card Styles --- */
            .overview-card {
                height: 180px;
                position: relative;
                overflow: hidden;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-radius: 16px !important;
            }

            .overview-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            }

            .overview-card .card-icon {
                background-color: rgba(255, 255, 255, 0.2);
                width: 60px;
                height: 60px;
                border-radius: 20%;
                /* makes it perfectly round */
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .card-icon {
                background-color: rgba(255, 255, 255, 0.2);
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .overview-card .card-icon i {
                font-size: 28px;
                /* larger icon size */
            }

            .overview-card .overview-text-content small {
                font-size: 1rem;
                /* larger label */
                font-weight: 500;
            }

            .overview-card .overview-text-content h3 {
                font-size: 2.1rem;
                /* larger main number */
                font-weight: 700;
                margin-top: 5px;
            }

            .overview-card .chart-line {
                position: absolute;
                right: 16px;
                bottom: 14px;
                opacity: 0.35;
            }

            @media (max-width: 768px) {
                .overview-card {
                    height: 160px;
                }

                .overview-card .card-icon {
                    width: 50px;
                    height: 50px;
                    padding: 10px;
                }

                .overview-card .card-icon i {
                    font-size: 24px;
                }

                .overview-card .overview-text-content h3 {
                    font-size: 2rem;
                }
            }


            /* --- Donut Chart Styles --- */
            .donut-chart-svg {
                width: 180px;
                height: 180px;
            }

            .donut-arc {
                transition: opacity 0.2s ease;
            }

            .donut-center-value {
                font-size: 28px;
                font-weight: 700;
                fill: #111827;
                text-anchor: middle;
            }

            .donut-center-label {
                font-size: 11px;
                font-weight: 500;
                fill: #6b7280;
                text-anchor: middle;
                text-transform: uppercase;
                letter-spacing: 0.3px;
            }

            /* --- Legend Styles --- */
            .legend-row {
                transition: all 0.2s ease;
            }

            .legend-row:hover {
                background-color: #f5f5f5;
                border-color: #e5e7eb;
            }

            .legend-marker {
                width: 12px;
                height: 12px;
                flex-shrink: 0;
            }

            .legend-zone-name {
                font-size: 13px;
                line-height: 1.2;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .legend-zone-percent {
                font-size: 11px;
            }

            .legend-value {
                font-size: 15px;
            }

            /* --- Line Chart Styles --- */
            .line-chart-svg {
                width: 100%;
                height: auto;
            }

            .grid-line-h {
                stroke: #f3f4f6;
                stroke-width: 1;
            }

            .axis-label-y {
                font-size: 11px;
                font-weight: 500;
                fill: #9ca3af;
                text-anchor: end;
            }

            .axis-label-x {
                font-size: 11px;
                font-weight: 600;
                fill: #6b7280;
                text-anchor: middle;
            }

            .line-stroke {
                fill: none;
                stroke: #2563eb;
                stroke-width: 2.5;
                stroke-linecap: round;
                stroke-linejoin: round;
            }

            .point-group circle {
                transition: all 0.2s ease;
            }

            .point-group:hover circle:nth-child(2) {
                r: 5;
            }

            .point-group:hover circle:nth-child(3) {
                r: 2;
            }

            .legend-dot {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                display: inline-block;
            }


            .legend-label-inline {
                font-size: 12px;
            }

            .summary-label {
                font-size: 12px;
            }

            .summary-value {
                font-size: 13px;
            }

            /* --- Ticket Card Styles --- */
            .ticket-card {
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                border-left: 4px solid transparent;
                position: relative;
                overflow: hidden;
            }

            .ticket-card::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 4px;
                height: 100%;
                background: linear-gradient(180deg, #2563eb 0%, #2563eb 100%);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .ticket-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
            }

            .ticket-card:hover::before {
                opacity: 1;
            }

            /* .ticket-number::before {
                                                                    content: '💬';
                                                                    font-size: 1rem;
                                                                    margin-right: 0.3rem;
                                                                } */

            .ticket-description {
                line-height: 1.5;
            }

            .ticket-view-link {
                transition: all 0.2s ease;
            }

            .ticket-view-link:hover {
                background-color: #ecfdf5;
                color: #059669;
                border-color: #d1fae5;
            }


            .table tbody td {
                background-color: #ffffff;
            }

            .table-hover tbody tr:hover td {
                background-color: #f9fafb;
            }

            /* --- Dropdown Styles --- */
            .dropdown-menu {
                z-index: 2000;
            }

            .dropdown-item {
                transition: background-color 0.2s, color 0.2s;
            }

            .dropdown-item:hover,
            .dropdown-item:focus {
                background-color: #eff6ff;
                color: #1d4ed8;
            }

            .dropdown-toggle.btn.btn-primary {
                background-color: #3b82f6 !important;
                border-color: #3b82f6 !important;
                transition: all 0.2s;
            }

            /* --- Custom Badge Styles --- */
            .badge.bg-warning {
                background-color: #fef3c7 !important;
                color: #a16207 !important;
                border: 1px solid #fde68a;
            }

            /* --- Responsive Adjustments --- */
            @media (max-width: 1199px) {
                .orders-insight-content {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 767px) {
                .donut-chart-svg {
                    width: 160px;
                    height: 160px;
                }

                .legend-zone-name {
                    font-size: 12px;
                }

                .legend-value {
                    font-size: 14px;
                }
            }

            .recent-order-scrollable {
                max-height: 500px;
                overflow: auto;
            }
        </style>
    </head>

    <div class="container-fluid px-1">

        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Good Afternoon</h5>
                        </div>
                    </div>
                    <span>
                        Namaste <span class="text-dark dashboard-fullname fw-700">Admin Test</span>
                    </span>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">

                    <nav class="breadcrumb-container" aria-label="breadcrumb" style="margin: 0;">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>

                            <li class="breadcrumb-item active"><a href="javascript:void(0);" class="item">Dashboard
                                </a></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-12 col-sm-12">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="statistic-header">
                            <h5>Order Management</h5>
                        </div>
                    </div>
                </div>
                <div class="statistics-grid">
                    <a class=""
                        href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_EXPRESS, 'status' => App\Models\Order::STATUS_DELIVERED]) }}">
                        <div class="card m-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="state">
                                        <h3 class="text-secondary">{{ @$stats['delivered_orders'] ?? 0 }}</h3>
                                        <h6 class="card-subtitle text-dark fw-700 mb-0">
                                            Success Orders</h6>
                                    </div>
                                    <div class="col-auto icon-size">
                                        <i class="ik ik-check-circle text-muted f-12 btn btn-light btn-icon p-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a class=""
                        href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_EXPRESS, 'pending_status' => 'pending']) }}">
                        <div class="card m-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="state">
                                        <h3 class="text-secondary">{{ @$stats['pending_orders'] ?? 0 }}</h3>
                                        <h6 class="card-subtitle text-dark fw-700 mb-0">
                                            Pending Orders</h6>
                                    </div>
                                    <div class="col-auto icon-size">
                                        <i class="ik ik-alert-circle text-muted f-12 btn btn-light btn-icon p-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a class=""
                        href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_EXPRESS, 'status' => App\Models\Order::STATUS_CANCELLED]) }}">
                        <div class="card m-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div class="state">
                                        <h3 class="text-secondary">{{ @$stats['cancelled_orders'] ?? 0 }}</h3>
                                        <h6 class="card-subtitle text-dark fw-700 mb-0">
                                            Cancelled Orders</h6>
                                    </div>
                                    <div class="col-auto icon-size">
                                        <i class="ik ik-x-circle text-muted f-12 btn btn-light btn-icon p-2"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>

                </div>
            </div>






            <div class="col-lg-12 col-md-12">

                {{-- Combined Charts Section: Orders Insight and Daily Orders Trend --}}
                @if (isset($totalExpressOrders) && $totalExpressOrders > 0)
                    <section class="">
                        <div class="row g-4">

                            {{-- Orders Insight Card - Left Side --}}
                            <div class="col-lg-4 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="d-flex align-items-center justify-content-center rounded-2 bg-opacity-10 text-primary"
                                                style="width: 36px; height: 36px;">
                                                <i class="fa-solid fa-chart-pie fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-5 fw-bold text-dark mb-0">Orders Distribution</h5>
                                                <p class="mb-0 text-muted">Delivery zone breakdown</p>
                                            </div>
                                        </div>


                                    </div>

                                    @php
                                        $colors = [
                                            ['primary' => '#2563eb', 'light' => '#eff6ff'],
                                            ['primary' => '#059669', 'light' => '#d1fae5'],
                                            ['primary' => '#d97706', 'light' => '#fef3c7'],
                                            ['primary' => '#dc2626', 'light' => '#fee2e2'],
                                            ['primary' => '#7c3aed', 'light' => '#ede9fe'],
                                            ['primary' => '#0891b2', 'light' => '#cffafe'],
                                            ['primary' => '#db2777', 'light' => '#fce7f3'],
                                        ];


                                        $totalExpress = $topExpressZones->sum('total_orders') ?? 0;
                                        $totalSubscription = $topSubscriptionsZones->sum('total_orders') ?? 0;
                                        $circumference = 2 * M_PI * 70;
                                        $offset = 0;

                                    @endphp

                                    <div class="">
                                        <ul class="nav nav-pills custom-pills justify-content-around" id="orderTypeTabs"
                                            role="tablist">

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="express-tab" data-bs-toggle="pill"
                                                    href="#express-pane" role="tab" aria-selected="true">
                                                    Express
                                                </a>
                                            </li>

                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="subscription-tab" data-bs-toggle="pill"
                                                    href="#subscription-pane" role="tab" aria-selected="false">
                                                    Subscription
                                                </a>
                                            </li>

                                        </ul>
                                    </div>

                                    <div class="card-body">
                                        <div class="tab-content" id="orderTypeTabsContent">
                                            <div class="tab-pane fade show active" id="express-pane" role="tabpanel">

                                                <div class="flex-grow-1 d-flex flex-column align-items-center text-center">
                                                    <div class="d-flex justify-content-center mb-4">
                                                        <svg viewBox="0 0 180 180" class="donut-chart-svg">
                                                            {{-- Background circle --}}
                                                            <circle cx="90" cy="90" r="70" fill="none"
                                                                stroke="#f3f4f6" stroke-width="24" />

                                                            {{-- Data segments --}}
                                                            @foreach ($topExpressZones as $index => $expressZone)
                                                                @php
                                                                    $percentage = $totalExpress > 0
                                                                        ? $expressZone->total_orders / $totalExpress
                                                                        : 0;
                                                                        
                                                                    $dashArray = $circumference * $percentage;
                                                                    $dashOffset = -$offset;
                                                                    $offset += $dashArray;
                                                                    $currentColor = $colors[$index % count($colors)];
                                                                @endphp

                                                                <circle class="donut-arc" cx="90" cy="90"
                                                                    r="70" fill="none"
                                                                    stroke="{{ $currentColor['primary'] }}"
                                                                    stroke-width="24"
                                                                    stroke-dasharray="{{ $dashArray }} {{ $circumference - $dashArray }}"
                                                                    stroke-dashoffset="{{ $dashOffset }}"
                                                                    transform="rotate(-90 90 90)" />
                                                            @endforeach

                                                            {{-- Inner circle --}}
                                                            <circle cx="90" cy="90" r="58"
                                                                fill="white" />

                                                            {{-- Center text --}}
                                                            <text x="90" y="82" text-anchor="middle"
                                                                class="donut-center-value">{{ number_format($totalExpressOrders) }}</text>
                                                            <text x="90" y="98" text-anchor="middle"
                                                                class="donut-center-label">Total
                                                                Orders</text>
                                                        </svg>
                                                    </div>

                                                    <div class="top-zones-legend row"style="row-gap: 0.6rem;">
                                                        {{-- Loop through the first 8 top zones --}}
                                                        @foreach ($topExpressZones->take(8) as $index => $expressZone)
                                                            @php
                                                                $currentColor = $colors[$index % count($colors)];
                                                                $zonePercentage =
                                                                    $totalExpress > 0
                                                                        ? round(($expressZone->total_orders / $totalExpress) * 100, 1)
                                                                        : 0;
                                                                $zoneName = $expressZone->zone->name ?? 'Zone ' . ($index + 1);
                                                            @endphp

                                                            <div class="col-12 col-md-6 px-1">
                                                                <div
                                                                    class="p-3 bg-white rounded-3 shadow-sm border h-100 d-flex flex-column justify-content-between">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between gap-3">
                                                                        <div class="legend-zone-name fw-bold text-truncate"
                                                                            style="font-size: 14px"
                                                                            title="{{ $zoneName }}">
                                                                            {{ Str::limit($zoneName, 25) }}
                                                                        </div>
                                                                        <div class="ms-3 text-end">
                                                                            <div
                                                                                class="legend-value fw-bolder text-dark fs-5">
                                                                                {{ $expressZone->total_orders }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="subscription-pane" role="tabpanel">
                                                <div class="flex-grow-1 d-flex flex-column align-items-center text-center">
                                                    <div class="d-flex justify-content-center mb-4">
                                                        <svg viewBox="0 0 180 180" class="donut-chart-svg">
                                                            {{-- Background circle --}}
                                                            <circle cx="90" cy="90" r="70" fill="none"
                                                                stroke="#f3f4f6" stroke-width="24" />

                                                            {{-- Data segments --}}
                                                            @foreach ($topSubscriptionsZones as $index => $subscriptionZone)
                                                                @php
                                                                    $percentage = $totalSubscription > 0
                                                                        ? $subscriptionZone->total_orders / $totalSubscription
                                                                        : 0;
                                                                    $dashArray = $circumference * $percentage;
                                                                    $dashOffset = -$offset;
                                                                    $offset += $dashArray;
                                                                    $currentColor = $colors[$index % count($colors)];
                                                                @endphp

                                                                <circle class="donut-arc" cx="90" cy="90"
                                                                    r="70" fill="none"
                                                                    stroke="{{ $currentColor['primary'] }}"
                                                                    stroke-width="24"
                                                                    stroke-dasharray="{{ $dashArray }} {{ $circumference - $dashArray }}"
                                                                    stroke-dashoffset="{{ $dashOffset }}"
                                                                    transform="rotate(-90 90 90)" />
                                                            @endforeach

                                                            {{-- Inner circle --}}
                                                            <circle cx="90" cy="90" r="58"
                                                                fill="white" />

                                                            {{-- Center text --}}
                                                            <text x="90" y="82" text-anchor="middle"
                                                                class="donut-center-value">{{ number_format($totalSubscriptionsOrders) }}</text>
                                                            <text x="90" y="98" text-anchor="middle"
                                                                class="donut-center-label">Total
                                                                Orders</text>
                                                        </svg>
                                                    </div>

                                                    <div class="top-zones-legend row"style="row-gap: 0.6rem;">
                                                        {{-- Loop through the first 8 top zones --}}
                                                        @foreach ($topSubscriptionsZones->take(8) as $index => $subscriptionZone)
                                                            @php
                                                                $currentColor = $colors[$index % count($colors)];
                                                                $zonePercentage =
                                                                $totalSubscription > 0
                                                                    ? round(($subscriptionZone->total_orders / $totalSubscription) * 100, 1)
                                                                    : 0;
                                                                $zoneName = $subscriptionZone->zone->name ?? 'Zone ' . ($index + 1);
                                                            @endphp

                                                            <div class="col-12 col-md-6 px-1">
                                                                <div
                                                                    class="p-3 bg-white rounded-3 shadow-sm border h-100 d-flex flex-column justify-content-between">
                                                                    <div
                                                                        class="d-flex align-items-center justify-content-between gap-3">
                                                                        <div class="legend-zone-name fw-bold text-truncate"
                                                                            style="font-size: 14px"
                                                                            title="{{ $zoneName }}">
                                                                            {{ Str::limit($zoneName, 25) }}
                                                                        </div>
                                                                        <div class="ms-3 text-end">
                                                                            <div
                                                                                class="legend-value fw-bolder text-dark fs-5">
                                                                                {{ $subscriptionZone->total_orders }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            {{-- Daily Orders Trend Card - Right Side --}}
                            <div class="col-lg-8 col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="d-flex align-items-center justify-content-center rounded-2 bg-opacity-10 text-primary"
                                                style="width: 36px; height: 36px;">
                                                <i class="fa-solid fa-chart-line fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="fs-5 fw-bold text-dark mb-0">Daily Orders Trend</h5>
                                                <p class="mb-0 text-muted">7-day performance overview</p>
                                            </div>
                                        </div>

                                    </div>

                                    @php
                                        $maxVal = max($chartData) > 0 ? max($chartData) : 100;
                                        $maxDisplay = ceil($maxVal / 10) * 10;

                                        $chartWidth = 650;
                                        $chartHeight = 300;
                                        $paddingLeft = 45;
                                        $paddingRight = 20;
                                        $paddingTop = 20;
                                        $paddingBottom = 35;

                                        $chartAreaWidth = $chartWidth - $paddingLeft - $paddingRight;
                                        $chartAreaHeight = $chartHeight - $paddingTop - $paddingBottom;
                                        $pointCount = count($chartData);
                                        $stepX = $pointCount > 1 ? $chartAreaWidth / ($pointCount - 1) : 0;

                                        $points = [];
                                        foreach ($chartData as $index => $value) {
                                            $x = $paddingLeft + $index * $stepX;
                                            $y =
                                                $paddingTop +
                                                ($chartAreaHeight - ($value / $maxDisplay) * $chartAreaHeight);
                                            $points[] = [
                                                'x' => $x,
                                                'y' => $y,
                                                'value' => $value,
                                                'label' => $chartLabels[$index] ?? '',
                                            ];
                                        }

                                        $linePath = '';
                                        foreach ($points as $index => $point) {
                                            if ($index === 0) {
                                                $linePath .= "M {$point['x']} {$point['y']}";
                                            } else {
                                                $linePath .= " L {$point['x']} {$point['y']}";
                                            }
                                        }

                                        $areaPath = $linePath;
                                        $lastPoint = $points[count($points) - 1];
                                        $firstPoint = $points[0];
                                        $bottomY = $paddingTop + $chartAreaHeight;
                                        $areaPath .= " L {$lastPoint['x']} {$bottomY} L {$firstPoint['x']} {$bottomY} Z";

                                        $gridLines = 4;
                                    @endphp

                                    <div class="card-body">
                                        <div class="flex-grow-1 d-flex flex-column position-relative">
                                            {{-- Tooltip --}}
                                            <div id="chart-tooltip"
                                                style="display:none; position:absolute; background:#2563eb; color:white; font-size:13px; padding:4px 8px; border-radius:6px; white-space:nowrap; pointer-events:none; transform:translate(-50%, -120%);">
                                            </div>

                                            <div class="flex-grow-1 mb-3">
                                                <svg class="line-chart-svg"
                                                    viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}"
                                                    preserveAspectRatio="xMidYMid meet"
                                                    style="width:100%; height:auto; cursor:pointer;">

                                                    <defs>
                                                        <linearGradient id="areaGradient" x1="0%" y1="0%"
                                                            x2="0%" y2="100%">
                                                            <stop offset="0%"
                                                                style="stop-color:#2563eb;stop-opacity:0.15" />
                                                            <stop offset="100%"
                                                                style="stop-color:#2563eb;stop-opacity:0.02" />
                                                        </linearGradient>
                                                    </defs>

                                                    {{-- Horizontal grid lines --}}
                                                    @for ($i = 0; $i <= $gridLines; $i++)
                                                        @php
                                                            $yPos = $paddingTop + ($chartAreaHeight / $gridLines) * $i;
                                                            $labelValue = round($maxDisplay * (1 - $i / $gridLines));
                                                        @endphp
                                                        <line x1="{{ $paddingLeft }}" y1="{{ $yPos }}"
                                                            x2="{{ $chartWidth - $paddingRight }}"
                                                            y2="{{ $yPos }}" stroke="#eaeaea"
                                                            stroke-width="1" />
                                                        <text x="{{ $paddingLeft - 8 }}" y="{{ $yPos + 4 }}"
                                                            font-size="11" text-anchor="end"
                                                            fill="#888">{{ $labelValue }}</text>
                                                    @endfor

                                                    {{-- Area fill --}}
                                                    <path d="{{ $areaPath }}" fill="url(#areaGradient)" />

                                                    {{-- Main line --}}
                                                    <path d="{{ $linePath }}" fill="none" stroke="#2563eb"
                                                        stroke-width="2" />

                                                    {{-- Data points --}}
                                                    @foreach ($points as $point)
                                                        <circle class="chart-point" cx="{{ $point['x'] }}"
                                                            cy="{{ $point['y'] }}" r="5" fill="#2563eb"
                                                            data-label="{{ $point['label'] }}"
                                                            data-value="{{ $point['value'] }}" />
                                                        <text x="{{ $point['x'] }}"
                                                            y="{{ $paddingTop + $chartAreaHeight + 20 }}"
                                                            text-anchor="middle" font-size="12"
                                                            fill="#555">{{ $point['label'] }}</text>
                                                    @endforeach
                                                </svg>
                                            </div>

                                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="legend-dot rounded-5 bg-primary mr-2"></div>
                                                    <span class="legend-label-inline fw-semibold text-secondary"
                                                        style="font-size: 14px">Daily Orders</span>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="summary-label fw-medium text-muted mr-2"
                                                        style="font-size: 14px">Peak:</span>
                                                    <span class="summary-value fw-bold text-dark"
                                                        style="font-size: 14px">{{ $maxVal }} orders</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                @if (isset($recentExpressOrders) && $recentExpressOrders->count() > 0)
                    <div class="card">
                        <div class="card-header justify-content-between d-flex align-items-center">
                            <h5 class="fs-5 fw-bold text-dark mb-0">Express Orders Pending Driver and Van Assignment</h5>
                            <span>
                                <a href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_EXPRESS, 'assign_to' => 'NotAssigned']) }}"
                                    class="text-primary fw-700">View More</a>
                            </span>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive recent-order-scrollable">
                                <table id="table" class="table p-0 table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="col_1" width="8%" style="">
                                                @lang('ui.sno')
                                            </th>

                                            <th class="col_2" width="12%" style="">
                                                <div class="d-flex align-items-center gap-1" style="cursor: pointer;">
                                                    <span>Order ID</span>
                                                </div>
                                            </th>

                                            <th class="col_3" width="12%" style="">
                                                <div class="d-flex align-items-center gap-1" style="cursor: pointer;">
                                                    <span style="min-width:120px;">Customer</span>
                                                </div>
                                            </th>

                                            <th class="col_4" width="12%" style="">Date</th>
                                            <th class="col_5" style="min-width:100px;">Amount
                                            </th>
                                            <th class="col_5" style="min-width:100px;">Assigned driver
                                            </th>

                                            <th class="col_7" width="10%" style="">Status</th>
                                            <th class="col_1 no-export" width="8%" style="">
                                                @lang('ui.actions')
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($recentExpressOrders) > 0)
                                            @foreach ($recentExpressOrders as $index => $order)
                                                <tr id="{{ $index + 1 }}">
                                                    <td class="col_1 text-dark" style="">
                                                        {{ $index + 1 }}
                                                    </td>

                                                    <td class="col_2 text-dark" style="">
                                                        {{ $order->getPrefix() }}</td>
                                                    <td class="col_3 text-dark" style="">
                                                        {{ @$order->user->full_name ?? 'N/A' }}
                                                        ({{ \App\Models\User::ACCOUNT_TYPES[@$order->user->account_type]['label'] ?? 'Not Available' }})
                                                    </td>
                                                    <td class="col_4 text-dark" style="min-width: 120px; ">
                                                        {{ $order->date }}</td>

                                                    <td class="col_5 text-dark" style="">
                                                        {{ format_price($order->total) }}</td>

                                                    <td class="col_5 text-danger" style="">
                                                        Not Assigned</td>

                                                    <td class="col_7" style="">
                                                        @php
                                                            $statusClasses = [
                                                                'pending' => 'badge-warning',
                                                                'assigned' => 'badge-primary',
                                                                'completed' => 'badge-success',
                                                                'cancelled' => 'badge-danger',
                                                            ];

                                                            $status = strtolower(@$order->status);
                                                            $badgeClass = $statusClasses[$status] ?? 'badge-secondary';
                                                        @endphp

                                                        <span class="badge {{ $badgeClass }} p-2 text-capitalize"
                                                            style="font-size: 12px;">
                                                            {{ @App\Models\Order::STATUSES[@$order->status]['label'] ?? '' }}
                                                        </span>
                                                    </td>


                                                    <td class="col_1 no-export" style="">
                                                        <div class="dropdown">
                                                            <a href="{{ route('panel.admin.orders.show', secureToken($order->id)) }}"
                                                                class="btn btn-primary text-white">
                                                                View Details
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    @include('panel.admin.include.components.no_data_img.index')
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endif
                @if (isset($recentSubscriptionOrders) && $recentSubscriptionOrders->count() > 0)
                    <div class="card">
                        <div class="card-header justify-content-between d-flex align-items-center">
                            <h5 class="fs-5 fw-bold text-dark mb-0">Subscriptions Orders Pending Driver and Van Assignment
                            </h5>
                            <span>
                                <a href="{{ route('panel.admin.orders.index', ['type' => App\Models\Order::TYPE_SUBSCRIPTION, 'assign_to' => 'NotAssigned']) }}"
                                    class="text-primary fw-700">View More</a>
                            </span>

                        </div>

                        <div class="card-body">
                            <div class="table-responsive recent-order-scrollable">
                                <table id="table" class="table p-0 table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="col_1" width="8%" style="">
                                                @lang('ui.sno')
                                            </th>

                                            <th class="col_2" width="12%" style="">
                                                <div class="d-flex align-items-center gap-1" style="cursor: pointer;">
                                                    <span>OrderID</span>
                                                </div>
                                            </th>

                                            <th class="col_3" width="12%" style="">
                                                <div class="d-flex align-items-center gap-1" style="cursor: pointer;">
                                                    <span style="min-width:120px;">Customer</span>
                                                </div>
                                            </th>

                                            <th class="col_4" width="12%" style="">Date</th>
                                            <th class="col_5" style="min-width:100px; ">Amount
                                            </th>
                                            <th class="col_5" style="min-width:100px;">Assigned driver
                                            </th>
                                            <th class="col_7" width="10%" style="">Status</th>
                                            <th class="col_1 no-export" width="8%" style="">
                                                @lang('ui.actions')
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (count($recentSubscriptionOrders) > 0)
                                            @foreach ($recentSubscriptionOrders as $index => $subscription)
                                                <tr id="{{ $index + 1 }}">

                                                    <td class="col_1 text-dark" style="">
                                                        {{ $index + 1 }}
                                                    </td>

                                                    <td class="col_2 text-dark" style="">
                                                        {{ $subscription->getPrefix() }}</td>
                                                    <td class="col_3 text-dark" style="">
                                                        {{ @$subscription->user->full_name ?? 'N/A' }}
                                                        ({{ \App\Models\User::ACCOUNT_TYPES[@$subscription->user->account_type]['label'] ?? 'Not Available' }})
                                                    </td>

                                                    <td class="col_4 text-dark" style="min-width: 120px;">
                                                        {{ @\Carbon\Carbon::parse($subscription->start_date)->format('d/m/Y') ?? '--' }}
                                                        -
                                                        {{ @\Carbon\Carbon::parse($subscription->end_date)->format('d/m/Y') ?? '--' }}
                                                    </td>



                                                    <td class="col_5 text-dark" style="">
                                                        {{ format_price($subscription->total) }}</td>


                                                    <td class="col_5 text-danger" style="">
                                                        Not Assigned
                                                    </td>

                                                    <td class="col_7" style="">
                                                        @php
                                                            $statusClasses = [
                                                                'pending' => 'badge-warning',
                                                                'assigned' => 'badge-primary',
                                                                'completed' => 'badge-success',
                                                                'cancelled' => 'badge-danger',
                                                            ];

                                                            $status = strtolower(@$subscription->status);
                                                            $badgeClass = $statusClasses[$status] ?? 'badge-secondary';
                                                        @endphp

                                                        <span class="badge {{ $badgeClass }} p-2 text-capitalize"
                                                            style="font-size: 12px;">
                                                            {{ @App\Models\Order::STATUSES[@$subscription->status]['label'] ?? '' }}
                                                        </span>
                                                    </td>

                                                    <td class="col_1 no-export" style="">
                                                        <div class="dropdown">
                                                            <a href="{{ route('panel.admin.orders.show', secureToken($subscription->id)) }}"
                                                                class="btn btn-primary text-white">
                                                                View Details
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    @include('panel.admin.include.components.no_data_img.index')
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                @if (isset($supportTickets) && $supportTickets->count() > 0)
                    <div class="card">
                        {{-- Card Header for Title --}}
                        <div class="card-header">
                            <h5 class="fs-5 fw-bold text-dark mb-0">Pending Platform Ticket Queue</h5>
                        </div>

                        {{-- Card Body for the List of Tickets --}}
                        <div class="card-body p-0">
                            {{-- Check if there are tickets to display --}}
                            @forelse ($supportTickets as $supportTicket)
                                {{-- Single Ticket Item --}}
                                <div
                                    class="d-flex align-items-center justify-content-between p-3 border-bottom hover-bg-light">

                                    {{-- Left Section: Ticket Details (Number, Status, Subject, User, Time) --}}
                                    <div class="flex-grow-1 me-3">

                                        {{-- Row 1: Ticket Number and Status Badge --}}
                                        <div class="d-flex align-items-center mb-1" style="gap:1rem;">
                                            <h6 class="ticket-number text-dark fw-semibold mb-0">
                                                {{ $supportTicket->getPrefix() }}
                                            </h6>

                                            {{-- PHP Logic to determine status badge --}}
                                            @php
                                                $statusData =
                                                    \App\Models\SupportTicket::STATUSES[$supportTicket->status] ?? null;
                                                $statusLabel = $statusData['label'] ?? '';
                                                $statusColor = $statusData['color'] ?? 'warning';
                                            @endphp

                                            <span class="badge bg-{{ $statusColor }} text-white">
                                                {{ $statusLabel }}
                                            </span>
                                        </div>

                                        {{-- Row 2: Subject/Description --}}
                                        <p class="ticket-description text-dark fw-normal mb-1"
                                            style="font-size: 0.95rem;">
                                            {{ $supportTicket->subject }}
                                        </p>

                                        {{-- Row 3: User Details and Created Time --}}
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span class="text-muted d-flex align-items-center mr-3"
                                                style="font-size: 0.85rem;">
                                                <i class="fas fa-user-circle text-secondary mr-1"></i>
                                                {{ $supportTicket->user->full_name }}
                                                ({{ (UserRole($supportTicket->user->id)->display_name ?? 'User') === 'User' ? 'Customer' : UserRole($supportTicket->user->id)->display_name }})
                                            </span>

                                            <small class="text-muted d-flex align-items-center"
                                                style="font-size: 0.8rem;">
                                                <i class="fa-regular fa-clock text-secondary mr-1"></i>
                                                {{ $supportTicket->created_at->diffForHumans() }}
                                            </small>
                                        </div>

                                    </div>

                                    {{-- Right Section: View Link/Arrow --}}
                                    <div>
                                        <a href="{{ route('panel.admin.support-tickets.show', secureToken($supportTicket->id)) }}"
                                            class="btn btn-sm btn-light p-0 btn-icon">
                                            <i class="ik ik-chevron-right text-muted f-18"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                {{-- Message if no tickets are found --}}
                                <div class="p-3 text-center text-muted">
                                    No pending platform tickets found.
                                </div>
                            @endforelse
                        </div>
                    </div>

                @endif
            </div>

        </div>
    </div>
@endsection
