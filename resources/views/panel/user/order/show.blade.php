@extends('layouts.main')
@section('title', __('Order Tracking'))
@section('content')
    @push('head')
        <link rel="stylesheet" href="{{ asset('panel/user/plugins/base/leaflet-routing.css') }}" />
        <style>
            body {
                padding-bottom: 0px !important;
            }

            #map {
                height: 180px;
                width: 100%;
            }

            @keyframes pulse-slow {

                0%,
                100% {
                    opacity: 1;
                    transform: scale(1);
                }

                50% {
                    opacity: 0.6;
                    transform: scale(1.1);
                }
            }

            .animate-pulse-slow {
                animation: pulse-slow 1.5s ease-in-out infinite;
            }

            @keyframes dotPulse {
                0% {
                    transform: scale(1);
                    opacity: .4;
                }

                50% {
                    transform: scale(1.4);
                    opacity: 1;
                }

                100% {
                    transform: scale(1);
                    opacity: .4;
                }
            }

            .dot {
                animation: dotPulse 0.9s infinite ease-in-out;
            }

            .dot:nth-child(2) {
                animation-delay: 0.15s;
            }

            .dot:nth-child(3) {
                animation-delay: 0.3s;
            }

            .icon-cycle {
                position: relative;
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                /* Ensures icon size is manageable if not set by font-size */
                overflow: hidden;
            }

            .icon-cycle svg {
                width: 25px;
                height: 25px;
            }

            .icon-cycle .delivery-icon {
                opacity: 0;
                transform: scale(0.6);
                transition: opacity 0.6s ease, transform 0.6s ease;
                position: absolute;
            }

            .icon-cycle .delivery-icon[style*="block"] {
                opacity: 1;
                transform: scale(1);
                animation: softPulse 2s ease-in-out infinite;
            }

            @keyframes softPulse {
                0% {
                    transform: scale(1);
                }

                50% {
                    transform: scale(1.08);
                }

                100% {
                    transform: scale(1);
                }
            }
        </style>
    @endpush

    @php
        $headerBg = \App\Models\Order::STATUSES[$order->status]['bg_color'] ?? 'bg-gray-500';
        $statusText = \App\Models\Order::STATUSES[$order->status]['order_heading'] ?? 'Unknown Status';

        // --- Current Time ---
        $currentTime = \Carbon\Carbon::now();

        $deliveryStartOfDay = \Carbon\Carbon::parse($order->date)->startOfDay();

        $cancellationCutoff = $deliveryStartOfDay->copy()->subHours(18);

        $isLateCancellation = $currentTime->greaterThanOrEqualTo($cancellationCutoff);

        // Deduction amount
        $deductionAmount = 100;

        $redirectRoute = route('panel.user.order.index');

    @endphp

    <div class="max-w-3xl mx-auto">
        <!-- Order Header -->
        <header class="{{ $headerBg }} text-white text-center py-6 px-4">
            <p class="text-base font-semibold mb-2">{{ $order->getPrefix() ?? '#ORD7842' }}</p>
            <h1 class="text-2xl font-bold">{{ $statusText }}</h1>

            <div class="flex items-center justify-center gap-2">
                @if ($order->status == \App\Models\Order::STATUS_DELIVERED)
                    <span class="text-[14px] mt-1 rounded-md px-2 py-1 bg-[#ffffff14]">
                        Delivered on
                        {{ @$order->latestStatusUpdateUserLog ? @$order->latestStatusUpdateUserLog->created_at->format('d M Y, h:i A') : '-' }}
                    </span>
                @elseif ($order->status == \App\Models\Order::STATUS_CANCELLED)
                    <span class="text-[14px] mt-1 rounded-md px-2 py-1 bg-[#ffffff14] text-gray-300">
                        Cancelled on
                        {{ @$order->latestStatusUpdateUserLog ? @$order->latestStatusUpdateUserLog->created_at->format('d M Y, h:i A') : '-' }}
                    </span>
                @else
                    @php
                        $today = Carbon\Carbon::today();
                    @endphp

                    @if ($order->date)
                        @if (Carbon\Carbon::parse($order->date)->isToday())
                            <span class="text-[14px] mt-1 rounded-md px-2 py-1 bg-[#ffffff14]">
                                Arriving within 24 hrs
                            </span>
                        @elseif(Carbon\Carbon::parse($order->date)->greaterThan($today))
                            <span class="text-[14px] mt-1 rounded-md px-2 py-1 bg-[#ffffff14]">
                                {{ Carbon\Carbon::parse($order->date)->format('d M Y') }}
                            </span>
                        @else
                            <span class="text-danger">
                                Delayed
                            </span>
                        @endif

                        <span class="text-[14px] mt-1 rounded-md px-2 py-1 bg-[#ffffff14]">
                            <i class="fi fi-bs-refresh"></i>
                        </span>
                    @endif
                @endif
            </div>
        </header>



        <!-- Map -->
        <section class="">
            @if ($order->assign_to)
                <div class="rounded-md overflow-hidden relative">

                    <div id="map"></div>
                    <button id="openFullMap"
                        class="absolute bottom-3 right-3 z-[999] bg-white shadow-lg w-10 h-10 rounded-full flex items-center justify-center">
                        <i class="fa-solid fa-expand text-lg"></i>
                    </button>
                </div>
                <div class="mb-4 bg-[#fff3cd] border border-[#ffeeba] text-[#856404] text-sm px-3 py-1 flex items-center justify-center gap-2 shadow-sm"
                    role="alert">
                    <i class="fas fa-triangle-exclamation text-base text-yellow-600"></i>
                    <span>Last Updated At: <span
                            class="font-medium">{{ \Carbon\Carbon::parse($order->updated_at)->format('M d, Y H:i A') }}</span></span>
                </div>
            @endif

        </section>

        @if (in_array($order->status, [\App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_CANCELLED_BY_ADMIN]))
            @if (!empty($order->rejection_reason))
                <section class="px-4 pt-4">
                    <div class="mt-3 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg w-full">
                        <div class="flex items-start gap-2">

                            <div>
                                <p class="font-semibold"><i class="fas fa-ban mt-0.5"></i> Cancellation Reason</p>
                                <p class="mt-1">{{ $order->rejection_reason }}</p>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif



        <section class="px-4 pt-4">
            <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">
                    Order Summary
                </h2>

                <div class="mb-3">
                    @php
                        // GST rate (stored in settings, fallback 18)
                        $gstPercent = getSetting('gst_rate') ?? 18;

                        // Total tax amount from the order
                        $totalGstAmount = $order->tax_amount ?? 0;

                        // Split into CGST and SGST
                        $cgstPercent = $gstPercent / 2;
                        $sgstPercent = $gstPercent / 2;

                        $cgstAmount = $totalGstAmount / 2;
                        $sgstAmount = $totalGstAmount / 2;
                    @endphp

                    @foreach ($order->orderItems as $item)
                        @php
                            // Retrieve the actual product details from the item association
                            $product = $item->product;

                            // The tax percent is used here for display only
                            $taxPercent = $item->tax_percent ?? 18;
                        @endphp

                        <div class="flex justify-between items-start {{ !$loop->last ? 'mb-3' : '' }}">
                            <div>
                                <p class="font-semibold text-gray-900 text-base leading-snug">
                                    {{ $product->name ?? 'Product' }}
                                </p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    {{ $product->weight ?? 'N/A' }}L Can × {{ $item->qty ?? 1 }}
                                </p>
                            </div>
                            <div class="text-right">
                                {{-- Display the line item total price (Qty * Unit Price) --}}
                                <p class="text-base font-bold text-gray-900">
                                    {{ format_price($item->price) }}
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ format_price($item->rate) }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 my-3"></div>

                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">Subtotal (Excl. Tax)</span>
                    {{-- Use the stored sub_total from the Order model --}}
                    <span class="font-medium text-gray-800">{{ format_price($order->sub_total ?? 0) }}</span>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">CGST ({{ $cgstPercent }}%)</span>
                    <span class="font-medium text-gray-800">{{ format_price($cgstAmount) }}</span>
                </div>

                <div class="flex justify-between text-sm mb-1">
                    <span class="text-gray-700">SGST ({{ $sgstPercent }}%)</span>
                    <span class="font-medium text-gray-800">{{ format_price($sgstAmount) }}</span>
                </div>


                <div class="border-t border-gray-200 my-3"></div>

                <div class="flex justify-between items-center">
                    <span class="text-base font-semibold text-gray-900">Total Payable</span>
                    <span class="text-xl font-bold text-gray-700">{{ format_price($order->total) }}</span>
                </div>

                <p class="text-sm text-gray-500">
                    Payment Status:
                    @if ($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                        <span class="font-semibold text-green-600">Paid</span>
                    @else
                        <span class="font-semibold text-red-600">Unpaid</span>
                    @endif
                </p>
            </div>
        </section>


        @if (
            !in_array($order->status, [\App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_DELIVERED]) &&
                $order->payment_status == \App\Models\Order::PAYMENT_STATUS_UNPAID)
            <section class="px-4 pt-4">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-red-600 mb-1">
                        <i class="fas fa-info-circle"></i> Your last payment attempt failed
                    </h2>

                    <p class="text-sm text-gray-500">
                        The previous payment attempt failed. Please retry to proceed with your order.
                    </p>
                    <div class="text-start">
                        <a href="{{ route('panel.user.order.retry-payment', secureToken($order->id)) }}"
                            class="inline-flex items-center text-blue-500 text-sm font-semibold py-2">

                            <span><i class="fas fa-redo"></i> Retry Payment</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif





        <!-- Driver Info -->
        @if ($order->status != App\Models\Order::STATUS_CANCELLED)
            <section class="px-4 pt-4">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-gray-900 mb-3">
                        Delivery Vehicle
                    </h2>

                    @if ($order->assign_to)
                        @php
                            $driver = \App\Models\User::find($order->assign_to);
                        @endphp

                        @if ($driver)
                            <div class="flex items-start justify-between gap-2">
                                <!-- Left: Driver Info -->
                                <div class="flex items-start gap-3 min-w-50">
                                    <!-- Avatar -->
                                    <div class="relative">

                                        <img src="{{ asset('panel/admin/default/default-avatar.png') }}" alt="Driver"
                                            class="w-11 h-11 min-w-11 rounded-full object-cover border border-gray-200 shadow-sm" />

                                    </div>

                                    <!-- Info -->
                                    <div>
                                        <h4 class="text-[16px] font-semibold text-gray-900">{{ $driver->full_name }}</h4>
                                        @php $vehicle = $driver->vehicle_details ?? []; @endphp

                                        @if (!empty($vehicle))
                                            <div class="flex items-center gap-x-2 gap-y-1 text-sm text-gray-700 flex-wrap">
                                                @if (!empty($vehicle['vehicle_name']))
                                                    <span class="flex items-center gap-1">
                                                        <i class="fa-solid fa-car text-gray-500 text-[14px]"></i>
                                                        <span>{{ $vehicle['vehicle_name'] }}</span>
                                                    </span>
                                                @endif

                                                @if (!empty($vehicle['vehicle_type']))
                                                    <span class="w-[1px] h-4 bg-gray-300"></span>
                                                    <span class="flex items-center gap-1">
                                                        <span
                                                            title="Vehicle Type">{{ ucfirst($vehicle['vehicle_type']) }}</span>
                                                    </span>
                                                @endif

                                                @if (!empty($vehicle['vehicle_number']))
                                                    <span class="w-[1px] h-4 bg-gray-300"></span>
                                                    <span class="flex items-center gap-1 font-medium text-gray-800">
                                                        <span
                                                            title="Vehicle Number">{{ strtoupper($vehicle['vehicle_number']) }}</span>
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <!-- Right: Call Button -->
                                    @if (!empty($driver->phone))
                                        <a href="tel:{{ $driver->phone }}"
                                            class="flex items-center justify-center bg-gray-100 gap-2 text-blue-700 hover:bg-blue-100 transition-all rounded-full p-3 font-medium text-sm">
                                            <i class="fas fa-phone-volume text-[15px]"></i>
                                        </a>
                                    @endif
                                </div>

                            </div>
                        @else
                            <p class="text-sm text-gray-600">Driver details not available.</p>
                        @endif
                    @else
                        <div
                            class="flex items-center gap-2 bg-blue-50 border border-blue-200 
                                    rounded-2xl p-3 pb-5 shadow-sm">

                            <div
                                class="w-16 h-16 min-w-16 flex items-center justify-center 
                                    rounded-full bg-white shadow-lg border border-blue-100">

                                <div class="icon-cycle">

                                    <i class="fas fa-magnifying-glass text-blue-600 delivery-icon"
                                        style="display: block;"></i>
                                    <i class="fas fa-truck-fast text-blue-600 delivery-icon" style="display: none;"></i>

                                </div>

                            </div>

                            <!-- Text + Loader -->
                            <div class="flex flex-col justify-center w-full">
                                <p class="font-semibold text-gray-900 text-lg leading-tight mb-2">
                                    Finding the nearest delivery vehicle
                                </p>
                            </div>
                        </div>

                    @endif
                </div>
            </section>
        @endif


        <!-- Meta Information -->
        <!-- Meta Information -->
        <section class="px-4 pt-4">
            <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">
                    Order Details
                </h2>

                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-700 text-sm">Customer</span>
                        <span class="font-medium text-gray-900 text-sm">
                            {{ $order->user->full_name ?? 'N/A' }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-700 text-sm">Order For</span>
                        <span class="font-medium text-gray-900 text-sm">
                            {{ Carbon\Carbon::parse($order->date)->format('d M Y') }}
                        </span>
                    </div>


                    <div class="flex justify-between">
                        <span class="text-gray-700 text-sm">Ordered At</span>
                        <span class="font-medium text-gray-900 text-sm">
                            {{ $order->created_at ? $order->created_at->format('d M Y') : 'N/A' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-gray-700 text-sm mb-2">Delivery Address</p>
                        <div class="flex flex-wrap mt-1">
                            <p class="font-medium text-gray-900 leading-snug text-sm">
                                <i class="fi fi-bs-home-location text-blue-600 text-md me-1 mt-0.5"></i>
                                {{ $order->to ?? 'Address not available' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Help / Raise Ticket -->
        <section class="bg-white px-4 py-5 border-gray-200">
            <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb] p-4">
                <div class="flex items-start justify-between flex-col">
                    <div>
                        <h3 class="font-semibold text-gray-900 text-base mb-1">Need Help?</h3>
                        <p class="text-sm text-gray-600 leading-snug">
                            If you’re facing any issue with your Order, you can raise a support ticket for quick
                            assistance.
                        </p>
                    </div>
                    <div class="mt-2">
                        @if (checkMobileViewActivated())
                            <a href="{{ route('panel.user.support-tickets.create', ['ticket_type_id' => secureToken($order->id), 'app_back' => true]) }}"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                                <i class="fa-solid fa-phone-volume text-base"></i>
                                Raise Ticket
                            </a>
                        @else
                            <a href="{{ route('panel.user.support-tickets.create', ['ticket_type_id' => secureToken($order->id)]) }}"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                                <i class="fa-solid fa-phone-volume text-base"></i>
                                Raise Ticket
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </section>


        <section class="">
            <div class="grid grid-cols-1">
                @if ($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                    @if (
                        !in_array($order->status, [
                            \App\Models\Order::STATUS_CANCELLED,
                            App\Models\Order::STATUS_CANCELLED_BY_ADMIN,
                            \App\Models\Order::STATUS_DELIVERED,
                        ]))
                        <button id="cancelOrderBtn"
                            class="text-red-500 font-semibold py-5 text-sm rounded-md transition-colors flex justify-center items-center gap-2">
                            <i class="fas fa-times"></i>
                            Cancel Order
                        </button>
                    @endif
                @endif
            </div>
        </section>
    </div>

    <!-- Full Screen Map Modal -->
    <div id="fullScreenMapModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999]">
        <div class="relative w-full h-full">
            <span id="closeFullMap"
                class="absolute top-12 right-3 z-[999] bg-white text-black text-lg font-bold cursor-pointer shadow w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-compress"></i>
            </span>

            <div id="fullMap" class="w-full h-full"></div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    @include('panel.common.order.confirm-modal')
    @include('panel.common.order.confirm-action')
@endsection
@push('script')
    <!-- Leaflet Routing Machine -->
    <script src="{{ asset('panel/user/plugins/base/leaflet-routing-machine.js') }}"></script>
    <script src="{{ asset('panel/user/plugins/base/leaflet-curve.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function initIconCycle() {
                const icons = document.querySelectorAll('.icon-cycle .delivery-icon');

                if (icons.length < 2) {
                    console.error("Not enough icons found.");
                    return;
                }

                let currentIndex = 0;

                function cycleIcons() {
                    icons[currentIndex].style.display = "none";
                    currentIndex = (currentIndex + 1) % icons.length;
                    icons[currentIndex].style.display = "block";
                }

                setInterval(cycleIcons, 1000);
            }

            // Delay execution slightly so FontAwesome can convert <i> → <svg>
            setTimeout(initIconCycle, 100);
        });
    </script>

    <script>
        // Coordinates from backend
        const customer = [{{ $customerData['lat'] ?? 0 }}, {{ $customerData['lng'] ?? 0 }}];
        const driver = [{{ $driverData['lat'] ?? 0 }}, {{ $driverData['lng'] ?? 0 }}];

        // Initialize map centered between both points
        const map = L.map('map').setView([
            (customer[0] + driver[0]) / 2,
            (customer[1] + driver[1]) / 2
        ], 11);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Custom icons
        const customerIcon = L.icon({
            iconUrl: "{{ asset('user/assets/icons/default-marker.png') }}",
            iconSize: [28, 42],
            iconAnchor: [20, 40],
            popupAnchor: [0, -30],
        });

        const driverIcon = L.icon({
            iconUrl: "{{ asset('user/assets/icons/driver-marker.png') }}",
            iconSize: [30, 45],
            iconAnchor: [20, 40],
            popupAnchor: [0, -30],
        });


        // Add markers with popups
        const customerMarker = L.marker(customer, {
                icon: customerIcon
            })
            .addTo(map)
            .bindPopup("<b>Customer Location</b>");

        const driverMarker = L.marker(driver, {
                icon: driverIcon
            })
            .addTo(map)
            .bindPopup("<b>Driver Location</b>");

        // Add actual road route between points using Leaflet Routing Machine
        L.Routing.control({
            waypoints: [
                L.latLng(driver[0], driver[1]),
                L.latLng(customer[0], customer[1])
            ],
            routeWhileDragging: false,
            draggableWaypoints: false,
            addWaypoints: false,
            show: false,
            lineOptions: {
                styles: [{
                    color: 'red',
                    opacity: 0.8,
                    weight: 4
                }]
            },
            createMarker: function() {
                return null;
            } // prevent default markers
        }).addTo(map);

        // Fit bounds
        map.fitBounds(L.latLngBounds([customer, driver]));
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // --- PHP Value Passed to JS ---
            // True if current time is AFTER 18:00 (6 PM) today.
            const IS_LATE_CANCELLATION = {{ $isLateCancellation ? 'true' : 'false' }};
            const COMPENSATION_AMOUNT = 100; // Define compensation for clarity
            // -----------------------------


            const cancelBtn = $("#cancelOrderBtn");
            // Update variable names to match the new modal structure
            const modal = $("#confirmActionModal");
            const modalTitle = $("#modalTitle");
            const modalMessage = $("#modalMessage");
            const compensationNote = $("#compensationNote");
            const confirmYes = $("#confirmActionYes");
            const confirmNo = $("#confirmActionNo");

            // Function to handle the AJAX call
            function performCancellation() {
                const targetStatusId = {{ \App\Models\Order::STATUS_CANCELLED }};
                const url = "{{ route('panel.user.order.update-status', secureToken($order->id)) }}";

                // Hide modal and disable buttons during AJAX
                modal.addClass("hidden");
                confirmYes.prop('disabled', true).text('Cancelling...');
                confirmNo.prop('disabled', true);

                // Determine if we need to send the compensation flag (if your backend requires it)
                const requestData = {
                    status: targetStatusId,
                    // Pass the flag to the backend so it can handle the deposit deduction
                    is_late_cancellation: IS_LATE_CANCELLATION
                };

                $.ajax({
                    url: url,
                    method: "GET", // Assuming the backend uses GET/query params for status update
                    data: requestData,
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert(response.message || "Something went wrong!");
                            // Re-enable buttons on failure
                            confirmYes.prop('disabled', false).text('Yes, Cancel');
                            confirmNo.prop('disabled', false);
                        }
                    },
                    error: function(error) {
                        console.error("AJAX Error:", error);
                        alert("Server error! Try again.");
                        // Re-enable buttons on failure
                        confirmYes.prop('disabled', false).text('Yes, Cancel');
                        confirmNo.prop('disabled', false);
                    }
                });
            }


            // 1. Show modal listener (Check and set content here)
            cancelBtn.on("click", function() {

                if (IS_LATE_CANCELLATION) {
                    // LATE CANCELLATION (After 18:00)
                    modalTitle.text('Pre-Cancellation Fee Applied');
                    modalMessage.html(`
                    As per policy, this order is no longer eligible for free cancellation. A compensation fee of ₹${COMPENSATION_AMOUNT} will be deducted from your deposit.
                `);
                    compensationNote.removeClass('hidden');

                } else {
                    // NORMAL CANCELLATION (Before 18:00)
                    modalTitle.text('Cancel Order Confirmation');
                    modalMessage.text(
                        'Are you sure you want to cancel this order?'
                    );
                    compensationNote.addClass('hidden');
                }

                modal.removeClass("hidden");
            });

            // 2. Hide modal
            confirmNo.on("click", function() {
                modal.addClass("hidden");
            });

            // 3. Confirm Cancel - Call the AJAX function
            confirmYes.on("click", function() {
                performCancellation();
            });
        });
    </script>


    <script>
        document.getElementById("openFullMap")?.addEventListener("click", function() {
            const modal = document.getElementById("fullScreenMapModal");
            modal.classList.remove("hidden");

            setTimeout(() => {
                // Re-fetch customer/driver data (optional, but good practice if the underlying data could change)
                const customer = [{{ $customerData['lat'] ?? 0 }}, {{ $customerData['lng'] ?? 0 }}];
                const driver = [{{ $driverData['lat'] ?? 0 }}, {{ $driverData['lng'] ?? 0 }}];

                // Destroy existing map instance before creating a new one if it exists
                const fullMapElement = document.getElementById('fullMap');
                if (fullMapElement && fullMapElement._leaflet_id) {
                    fullMapElement._leaflet_map.remove();
                    fullMapElement.innerHTML = ''; // Clear the container
                }

                // Init Fullscreen Map
                const fullMap = L.map('fullMap').setView([(customer[0] + driver[0]) / 2,
                    (customer[1] + driver[1]) / 2
                ], 13);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(fullMap);

                const customerIcon = L.icon({
                    iconUrl: "{{ asset('user/assets/icons/default-marker.png') }}",
                    iconSize: [28, 42],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -30],
                });

                const driverIcon = L.icon({
                    iconUrl: "{{ asset('user/assets/icons/driver-marker.png') }}",
                    iconSize: [30, 45],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -30],
                });


                L.marker(customer, {
                    icon: customerIcon
                }).addTo(fullMap);
                L.marker(driver, {
                    icon: driverIcon
                }).addTo(fullMap);

                // Add Routing Control to the fullMap instance
                L.Routing.control({
                    waypoints: [
                        L.latLng(driver[0], driver[1]),
                        L.latLng(customer[0], customer[1])
                    ],
                    routeWhileDragging: false,
                    draggableWaypoints: false,
                    addWaypoints: false,
                    show: false,
                    lineOptions: {
                        styles: [{
                            color: 'red',
                            opacity: 0.8,
                            weight: 4
                        }]
                    },
                    createMarker: function() {
                        return null;
                    }
                }).addTo(fullMap);

                // Invalidate size and fit bounds
                fullMap.invalidateSize();
                fullMap.fitBounds(L.latLngBounds([customer, driver]));

            }, 200);

        });

        document.getElementById("closeFullMap")?.addEventListener("click", function() {
            const modal = document.getElementById("fullScreenMapModal");
            modal.classList.add("hidden");
            modal.style.display = '';
        });
    </script>
@endpush
