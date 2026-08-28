@extends('layouts.main')
@section('title', __('Order Tracking'))

@push('head')
    <link rel="stylesheet" href="{{ asset('panel/driver/plugins/base/leaflet-routing.css') }}" />

    <style>
        #map {
            height: 180px;
            width: 100%;
        }

        .btn-highlight {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.35);
        }
    </style>
@endpush

@php
    $expectedDeliveryDate = calculateExpectedDeliveryDate($order->id);
@endphp

@section('content')
    {{-- ... (Your PHP variables and header section remain here) ... --}}
    @php
        $headerBg = \App\Models\Order::STATUSES[$order->status]['bg_color'] ?? 'bg-gray-500';
        $statusText = \App\Models\Order::STATUSES[$order->status]['order_heading'] ?? 'Unknown Status';

        $user = $order->user;
        $address = $order->address ?? null;
        $product = $order->product ?? null;

        $customerLat = $address->latitude ?? 28.6139;
        $customerLng = $address->longitude ?? 77.209;

        $driverLat = $order->driver->latitude ?? 28.5355;
        $driverLng = $order->driver->longitude ?? 77.391;
    @endphp
    <div class="max-w-3xl mx-auto pb-5">
        <header class="{{ $headerBg }} text-white text-center py-6 px-4">
            {{-- ... (Header Content) ... --}}
            <p class="text-base font-semibold mb-2">{{ $order->getPrefix() }}</p>
            <h1 class="text-2xl font-bold">{{ $statusText }}</h1>

            <div class="flex items-center justify-center gap-2 mt-2">
                @if ($order->status == \App\Models\Order::STATUS_DELIVERED)
                    <span class="text-[14px] rounded-md px-2 py-1 bg-[#ffffff14]">
                        Delivered on
                        {{ @$order->latestStatusUpdateUserLog ? @$order->latestStatusUpdateUserLog->created_at->format('d M Y, h:i A') : '-' }}
                    </span>
                @elseif ($order->status == \App\Models\Order::STATUS_CANCELLED)
                    <span class="text-[14px] rounded-md px-2 py-1 bg-[#ffffff14] text-red-300">
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
        {{-- ... (Rest of your content sections) ... --}}
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


        <div class="max-w-3xl mx-auto">
            <section class="mt-2 grid md:grid-cols-2 gap-4">
                <div class="space-y-4 mx-3">
                    <div class="bg-white rounded-xl border border-[#dbdbdb] shadow-sm p-4">
                        <h2 class="font-semibold text-gray-900 text-lg mb-2">
                            Customer Information
                        </h2>

                        <div class="space-y-4 text-sm">
                            <div class="flex items-start gap-3">
                                <i class="fi fi-tr-id-card text-gray-900 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-gray-500 text-[13px]">Name</p>
                                    <p class="font-medium text-gray-900 text-[15px]">
                                        {{ $user->full_name ?? 'Unknown User' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fi fi-tr-phone-call text-gray-900 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-gray-500 text-[13px]">Phone</p>
                                    <p class="font-medium text-gray-900 text-[15px]">
                                        <a href="tel:{{ $user->phone ?? '' }}" class="text-blue-600 hover:underline">
                                            {{ $user->phone ?? 'N/A' }}
                                        </a>
                                    </p>
                                </div>
                            </div>



                            <div class="flex items-start gap-3">
                                <i class="fi fi-tr-envelope text-gray-900 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-gray-500 text-[13px]">Email</p>
                                    <p class="font-medium text-gray-900 text-[15px]">
                                        <a href="mailto:{{ $user->email ?? '' }}" class="text-blue-600 hover:underline">
                                            {{ $user->email ?? 'N/A' }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <i class="fi fi-tr-marker text-gray-900 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-gray-500 text-[13px]">Address</p>
                                    <p class="font-medium text-gray-900 leading-snug text-[15px]">
                                        {{ $order->to ?? 'Address not available' }}
                                    </p>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-[#dbdbdb] shadow-sm p-4">
                        <h2 class="font-semibold text-gray-900 text-lg mb-4">
                            Order Summary
                        </h2>

                        <div class="space-y-3 mb-3">
                            @foreach ($order->orderItems as $item)
                                @php
                                    // Ensure item is loaded correctly (assuming 'item' is the morph relationship to Product)
                                    $product = $item->product;
                                @endphp

                                {{-- Display details for each product line item --}}
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-gray-900 text-base leading-snug">
                                            {{ $product->name ?? 'Product Not Found' }}
                                        </p>
                                        <p class="text-sm text-gray-500 mt-0.5">
                                            {{-- Assuming weight is on the product model --}}
                                            {{ $product->weight ?? 'N/A' }}L Can × {{ $item->qty ?? 1 }}
                                        </p>
                                    </div>
                                    {{-- Price display (Optional for driver side, but useful for verification) --}}
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">
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

                        <div class="text-sm text-gray-700 space-y-1">

                            <div class="flex justify-between">
                                <span>Order ID:</span>
                                <span class="font-medium text-gray-900">{{ $order->getPrefix() ?? '#ORD0000' }}</span>
                            </div>

                            {{-- <div class="flex justify-between">
                                <span>Status:</span>
                                <span class="font-medium text-gray-900">
                                    {{ \App\Models\Order::STATUSES[$order->status]['label'] ?? 'Unknown' }}
                                </span>
                            </div> --}}
                        </div>
                    </div>

                </div>

                <section class="bg-white rounded-xl border border-[#dbdbdb] shadow-sm p-4 mx-3">
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
                                <a href="{{ route('panel.driver.support-tickets.create', ['ticket_type_id' => secureToken($order->id), 'app_back' => true]) }}"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                                    <i class="fa-solid fa-phone-volume text-base"></i>
                                    Raise Ticket
                                </a>
                            @else
                                <a href="{{ route('panel.driver.support-tickets.create', ['ticket_type_id' => secureToken($order->id)]) }}"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                                    <i class="fa-solid fa-phone-volume text-base"></i>
                                    Raise Ticket
                                </a>
                            @endif
                        </div>
                    </div>
                </section>


                <div class="space-y-4 mx-3">
                    <div class="bg-white rounded-xl border border-[#dbdbdb] shadow-sm p-4">
                        <h2 class="font-semibold text-gray-900 text-lg mb-2">
                            Meta Information
                        </h2>
                        <div class="space-y-3 text-sm">
                            <div>
                                <p class="text-gray-500">Order Placed</p>
                                <p class="font-medium text-gray-900">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Estimated Delivery</p>
                                <p class="font-medium text-gray-900">

                                     @if ($expectedDeliveryDate instanceof \Carbon\Carbon)
                                        {{ $expectedDeliveryDate->format('d M Y') }}
                                    @else
                                        {{ $expectedDeliveryDate }}
                                    @endif
                                    

                                    {{-- {{ $order->created_at->copy()->addDay()->format('d M Y, h:i A') }} --}}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="shadow-sm space-y-3">
                        @if ($order->status == \App\Models\Order::STATUS_ASSIGNED)
                            <button id="markInRouteBtn" data-status="{{ App\Models\Order::STATUS_INROUTE }}"
                                class="w-full bg-green-600 text-white font-semibold py-2 text-sm rounded-md transition flex justify-center items-center">
                                Mark as In Route
                            </button>
                        @endif

                        @if ($order->status == \App\Models\Order::STATUS_INROUTE)
                            <div class="bg-white p-4 rounded-xl border border-blue-200">
                                <form id="deliveryConfirmationForm" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="sso_token" value="{{ request()->get('sso_token') }}">

                                    <div class="mb-4">

                                        <label for="delivery_challan" class="block text-sm font-medium text-gray-700 mb-1">
                                            Upload Delivery Challan (DC) <span class="text-red-500">*</span>
                                        </label>

                                        <!-- NORMAL FILE INPUT - Flutter WebView Compatible -->
                                        <div class="mb-3">
                                            <input type="file" name="delivery_challan" id="delivery_challan"
                                                accept=".jpeg,.png,.jpg"
                                                class="block w-full text-sm text-gray-700 border border-gray-300 rounded-md p-2 cursor-pointer focus:ring-blue-500" />
                                        </div>

                                        <p id="selectedFileName" class="mt-2 text-sm text-blue-600 hidden font-semibold">
                                        </p>
                                        <p id="dcFileError" class="mt-1 text-xs text-red-500 hidden"></p>

                                        <p class="mt-1 text-xs text-gray-500">
                                            Max size: 5 MB. Formats allowed: JPG, JPEG, PNG.
                                        </p>
                                    </div>

                                    <button type="submit" id="confirmDeliveryBtn"
                                        data-status="{{ App\Models\Order::STATUS_DELIVERED }}"
                                        class="btn-highlight w-full flex justify-center items-center gap-2 text-white font-semibold py-2.5 mt-2 rounded-md transition-opacity opacity-50 cursor-not-allowed"
                                        disabled>
                                        Confirm Delivery
                                    </button>
                                </form>
                            </div>


                            @if ($order->status != App\Models\Order::STATUS_CANCELLED)
                                <div class="text-center">
                                    <button id="cancelOrderBtn" data-status="{{ App\Models\Order::STATUS_CANCELLED }}"
                                        class="text-red-500 w-full font-semibold py-2.5 text-sm rounded-md transition-colors flex justify-center items-center gap-2">
                                        <i class="fas fa-times"></i>
                                        Cancel Order
                                    </button>
                                </div>
                            @endif
                        @endif

                    </div>
                </div>
            </section>
        </div>
    </div>

    <div id="fullScreenMapModal" class="fixed inset-0 bg-black/80 hidden items-center justify-center z-[9999]">
        <div class="relative w-full h-full">
            <span id="closeFullMap"
                class="absolute top-12 right-3 z-[999] bg-white text-black text-lg font-bold cursor-pointer shadow w-10 h-10 rounded-full flex items-center justify-center">
                <i class="fa-solid fa-compress"></i>
            </span>

            <div id="fullMap" class="w-full h-full"></div>
        </div>
    </div>


    <div id="confirmActionModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[9999] flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-[20rem] shadow-xl border border-gray-100 text-center space-y-4">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            <p id="modalMessage" class="text-gray-600 text-sm"></p>
            <div class="flex justify-center gap-3 pt-2">
                <button id="confirmActionYes"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm px-8 py-2 rounded-md transition-colors">Yes</button>
                <button id="confirmActionNo"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm px-8 py-2 rounded-md transition-colors">No</button>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script src="{{ asset('panel/driver/plugins/base/leaflet-routing-machine.js') }}"></script>
    <script src="{{ asset('panel/driver/plugins/base/leaflet-curve.js') }}"></script>

    <script>
        // --- Map Variables and Initialization (Omitted for brevity, assumed unchanged) ---
        const customer = [{{ $customerData['lat'] ?? 0 }}, {{ $customerData['lng'] ?? 0 }}];
        const driver = [{{ $driverData['lat'] ?? 0 }}, {{ $driverData['lng'] ?? 0 }}];

        const isValidCustomer = customer[0] !== 0 && customer[1] !== 0;
        const isValidDriver = driver[0] !== 0 && driver[1] !== 0;

        const initialCenter = [
            (isValidCustomer && isValidDriver) ? (customer[0] + driver[0]) / 2 :
            20.5937,
            (isValidCustomer && isValidDriver) ? (customer[1] + driver[1]) / 2 : 78.9629
        ];
        const initialZoom = (isValidCustomer && isValidDriver) ? 11 : 5;

        const map = L.map('map').setView(initialCenter, initialZoom);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const customerIcon = L.icon({
            iconUrl: "{{ asset('user/assets/icons/default-marker.png') }}",
            iconSize: [28, 42],
            iconAnchor: [14, 40],
            popupAnchor: [0, -30],
        });

        const driverIcon = L.icon({
            iconUrl: "{{ asset('user/assets/icons/driver-marker.png') }}",
            iconSize: [30, 45],
            iconAnchor: [15, 43],
            popupAnchor: [0, -30],
        });

        let waypoints = [];
        let bounds = [];

        if (isValidCustomer) {
            L.marker(customer, {
                    icon: customerIcon
                })
                .addTo(map)
                .bindPopup("<b>Customer Location</b>");
            waypoints.push(L.latLng(customer[0], customer[1]));
            bounds.push(customer);
        }

        if (isValidDriver) {
            L.marker(driver, {
                    icon: driverIcon
                })
                .addTo(map)
                .bindPopup("<b>Driver Location</b>");
            waypoints.unshift(L.latLng(driver[0], driver[1]));
            bounds.push(driver);
        }

        if (isValidCustomer && isValidDriver) {
            L.Routing.control({
                waypoints: waypoints,
                routeWhileDragging: false,
                draggableWaypoints: false,
                addWaypoints: false,
                show: false,
                lineOptions: {
                    styles: [{
                        color: '#FF4500',
                        opacity: 0.9,
                        weight: 5
                    }]
                },
                createMarker: function() {
                    return null;
                }
            }).addTo(map);
        }

        if (bounds.length > 0) {
            map.fitBounds(L.latLngBounds(bounds), {
                padding: [50, 50]
            });
        }

        // --- Global Form/Modal Elements ---
        const fileInput = document.getElementById('delivery_challan');
        const challanForm = document.getElementById('deliveryConfirmationForm');
        const confirmDeliveryBtn = document.getElementById('confirmDeliveryBtn');
        const selectedFileName = document.getElementById('selectedFileName');
        const dcFileError = document.getElementById('dcFileError');

        // Single Modal elements for ALL actions (In Route, Cancel, Delivery)
        // NOTE: These MUST be present in the HTML for any confirmation action to work.
        const modal = document.getElementById("confirmActionModal");
        const modalTitle = document.getElementById("modalTitle");
        const modalMessage = document.getElementById("modalMessage");
        const confirmActionYes = document.getElementById("confirmActionYes");
        const confirmActionNo = document.getElementById("confirmActionNo");

        // --- Fullscreen Map Logic (Omitted for brevity, assumed unchanged) ---
        document.getElementById("openFullMap")?.addEventListener("click", function() {
            const modal = document.getElementById("fullScreenMapModal");
            modal.classList.remove("hidden");

            setTimeout(() => {
                const customer = [{{ $customerData['lat'] ?? 0 }}, {{ $customerData['lng'] ?? 0 }}];
                const driver = [{{ $driverData['lat'] ?? 0 }}, {{ $driverData['lng'] ?? 0 }}];

                const fullMapElement = document.getElementById('fullMap');
                if (fullMapElement && fullMapElement._leaflet_id) {
                    fullMapElement._leaflet_map.remove();
                    fullMapElement.innerHTML = '';
                }

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

                fullMap.invalidateSize();
                fullMap.fitBounds(L.latLngBounds([customer, driver]));

            }, 200);

        });

        document.getElementById("closeFullMap")?.addEventListener("click", function() {
            const modal = document.getElementById("fullScreenMapModal");
            modal.classList.add("hidden");
            modal.style.display = '';
        });

        // Helper function to reset the modal button colors
        function resetModalButtonStyles() {
            if (confirmActionYes) {
                confirmActionYes.classList.remove('bg-red-600', 'hover:bg-red-700');
                confirmActionYes.classList.add('bg-blue-600', 'hover:bg-blue-700');
                confirmActionYes.textContent = 'Yes';
            }
        }

        // --- NEW FUNCTION: HANDLE DELIVERY CHALLAN UPLOAD ---
        async function updateDeliveryStatus(formData, buttonId) {
            const button = document.getElementById(buttonId);
            const originalButtonText = button ? button.innerHTML : '';

            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Confirming Delivery...';
            }

            try {
                const url = "{{ route('panel.driver.order.update-delivery-challan', secureToken($order->id)) }}";

                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    alert(data.message || "Failed to confirm delivery and upload challan.");
                    if (button) {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.innerHTML = originalButtonText;
                        resetModalButtonStyles();
                    }
                }
            } catch (error) {
                console.error("Error confirming delivery:", error);
                alert("An error occurred during delivery confirmation.");
                if (button) {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                    button.innerHTML = originalButtonText;
                    resetModalButtonStyles();
                }
            }
        }


        // --- UPDATED FUNCTION: HANDLE SIMPLE STATUS UPDATE (In Route, Cancel) ---
        async function updateOrderStatus(statusId, buttonId, isCancel = false) {
            const button = document.getElementById(buttonId);
            const originalButtonText = button ? button.innerHTML : '';

            if (button) {
                button.disabled = true;
                button.classList.add('opacity-50', 'cursor-not-allowed');
                button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Updating...';
            }

            try {
                const finalStatusId = isCancel ? {{ \App\Models\Order::STATUS_CANCELLED }} : statusId;

                const url = "{{ route('panel.driver.order.update-status', secureToken($order->id)) }}" +
                    `?status=${finalStatusId}`;

                let fetchOptions = {
                    method: 'POST',
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({})
                };

                const response = await fetch(url, fetchOptions);

                const data = await response.json();

                if (data.success) {
                    setTimeout(() => {
                        location.reload();
                    }, 500);
                } else {
                    alert(data.message || "Failed to update order status.");
                    if (button) {
                        button.disabled = false;
                        button.classList.remove('opacity-50', 'cursor-not-allowed');
                        button.innerHTML = originalButtonText;
                        resetModalButtonStyles();
                    }
                }
            } catch (error) {
                console.error("Error updating status:", error);
                alert("An error occurred during the status update.");
                if (button) {
                    button.disabled = false;
                    button.classList.remove('opacity-50', 'cursor-not-allowed');
                    button.innerHTML = originalButtonText;
                    resetModalButtonStyles();
                }
            }
        }

        // --- Challan Validation (Added null checks) ---
        function checkFileValidity() {
            if (!fileInput || !confirmDeliveryBtn || !selectedFileName || !dcFileError) {
                return false;
            }

            const file = fileInput.files[0];
            const maxSizeInBytes = 5 * 1024 * 1024;

            if (!file) {
                selectedFileName.classList.add('hidden');
                confirmDeliveryBtn.disabled = true;
                confirmDeliveryBtn.classList.add('opacity-50', 'cursor-not-allowed');
                dcFileError.classList.add('hidden');
                selectedFileName.textContent = '';
                return false;
            }

            // --- Check extension instead of MIME (Flutter WebView fix)
            const fileName = file.name.toLowerCase();
            const extension = fileName.split('.').pop();
            const allowedExtensions = ['jpg', 'jpeg', 'png'];

            if (!allowedExtensions.includes(extension)) {
                dcFileError.textContent = 'Invalid file format. Only JPG, JPEG, PNG are allowed.';
                dcFileError.classList.remove('hidden');
                confirmDeliveryBtn.disabled = true;
                confirmDeliveryBtn.classList.add('opacity-50', 'cursor-not-allowed');
                selectedFileName.classList.add('hidden');
                return false;
            }

            // --- Size check (Flutter returns size = 0)
            if (file.size > 0 && file.size > maxSizeInBytes) {
                dcFileError.textContent = 'File size exceeds the 5 MB limit.';
                dcFileError.classList.remove('hidden');
                confirmDeliveryBtn.disabled = true;
                confirmDeliveryBtn.classList.add('opacity-50', 'cursor-not-allowed');
                selectedFileName.classList.add('hidden');
                return false;
            }

            // Success
            dcFileError.classList.add('hidden');
            selectedFileName.textContent = `File Selected: ${file.name}`;
            selectedFileName.classList.remove('hidden');

            confirmDeliveryBtn.disabled = false;
            confirmDeliveryBtn.classList.remove('opacity-50', 'cursor-not-allowed');

            return true;
        }



        if (fileInput) {
            fileInput.addEventListener('change', checkFileValidity);
            checkFileValidity();
        }

        document.addEventListener("DOMContentLoaded", function() {

            // --- In Route Action ---
            document.getElementById("markInRouteBtn")?.addEventListener("click", (e) => {
                e.preventDefault();

                // CRITICAL CHECK: Ensure modal exists for this action
                if (!modal || !confirmActionYes || !modalTitle || !modalMessage) {
                    return console.error("Confirmation modal missing for 'In Route' action.");
                }

                resetModalButtonStyles();
                const statusId = e.currentTarget.dataset.status;

                modalTitle.textContent = 'Hands up!';
                modalMessage.textContent = 'Are you sure you want to mark this order as "In Route"?';

                confirmActionYes.onclick = () => {
                    modal.classList.add("hidden");
                    updateOrderStatus(statusId, 'markInRouteBtn');
                };
                confirmActionNo.onclick = () => modal.classList.add("hidden");

                modal.classList.remove("hidden");
            });

            // --- Cancel Order Action (Unified to use global modal variables) ---
            document.getElementById("cancelOrderBtn")?.addEventListener("click", (e) => {
                e.preventDefault();

                // CRITICAL CHECK: Ensure modal exists for this action
                if (!modal || !confirmActionYes || !modalTitle || !modalMessage) {
                    return console.error("Confirmation modal missing for 'Cancel' action.");
                }

                const statusId = e.currentTarget.dataset.status;

                modalTitle.textContent = 'Confirm Cancellation';
                modalMessage.textContent =
                    'Are you absolutely sure you want to cancel this order? This action cannot be undone.';

                // Apply Red styles for Cancel confirmation
                confirmActionYes.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                confirmActionYes.classList.add('bg-red-600', 'hover:bg-red-700');
                confirmActionYes.textContent = 'Yes, Cancel';

                confirmActionYes.onclick = () => {
                    modal.classList.add("hidden");
                    updateOrderStatus(statusId, 'cancelOrderBtn', true);
                };
                confirmActionNo.onclick = () => {
                    modal.classList.add("hidden");
                    resetModalButtonStyles();
                };

                modal.classList.remove("hidden");
            });

            // --- DELIVERY FORM SUBMISSION HANDLER ---
            challanForm?.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!checkFileValidity()) {
                    return;
                }

                // CRITICAL CHECK: Ensure modal exists before using it
                if (!modal || !confirmActionYes || !modalTitle || !modalMessage) {
                    console.error("Confirmation modal elements not found for Delivery action.");
                    alert("Cannot confirm delivery: The confirmation box is missing.");
                    return;
                }

                resetModalButtonStyles();
                const buttonId = 'confirmDeliveryBtn';

                modalTitle.textContent = 'Confirm Delivery';
                modalMessage.textContent =
                    'Are you sure you want to confirm delivery for this order and upload the challan?';

                confirmActionYes.onclick = () => {
                    modal.classList.add("hidden");

                    const formData = new FormData(challanForm);
                    updateDeliveryStatus(formData, buttonId);
                };
                confirmActionNo.onclick = () => modal.classList.add("hidden");

                // Show the modal
                modal.classList.remove("hidden");
            });


        });
    </script>
@endpush
