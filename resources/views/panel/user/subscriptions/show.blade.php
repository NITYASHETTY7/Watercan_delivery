@extends('layouts.main')
@section('title', __('Subscription Order'))
@section('content')
    @push('head')
        <style>
            body {
                padding-bottom: 0px !important;
            }
        </style>
    @endpush

    @php
        use App\Models\Order;

        $statusClasses = [
            Order::STATUS_PENDING => 'bg-yellow-100 text-yellow-700',
            Order::STATUS_ASSIGNED => 'bg-blue-100 text-blue-700',
            Order::STATUS_INROUTE => 'bg-orange-100 text-orange-700',
            Order::STATUS_DELIVERED => 'bg-green-100 text-green-700',
            Order::STATUS_CANCELLED => 'bg-red-100 text-red-700',
        ];

        $statusLabels = [
            Order::STATUS_PENDING => 'Pending',
            Order::STATUS_ASSIGNED => 'Assigned',
            Order::STATUS_INROUTE => 'In Route',
            Order::STATUS_DELIVERED => 'Delivered',
            Order::STATUS_CANCELLED => 'Cancelled',
        ];
    @endphp

    <div id="confirmCancelModal"
        class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden z-[999] flex items-center justify-center">
        <div class="bg-white rounded-2xl p-6 w-[20rem] shadow-xl border border-gray-100 text-center space-y-5">
            <h2 class="text-lg font-semibold text-gray-900">Cancel Subscription?</h2>
            <p class="text-sm text-gray-500 leading-snug">
                Your subscription will be stopped and further deliveries will no longer continue.
            </p>
            <div class="flex justify-between gap-3">
                <button id="cancelNo"
                    class="flex-1 py-2 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-100">
                    Continue
                </button>
                <button id="cancelYes" class="flex-1 py-2 rounded-lg bg-red-500 text-white font-medium hover:bg-red-600">
                    Cancel Now
                </button>
            </div>
        </div>
    </div>

    <header class="pt-4 px-5">
        <h1 class="text-xl font-semibold text-gray-900">
            Subscription ID: {{ $subscription->getPrefix() }}
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Manage and track your pending, completed, and cancelled subscriptions easily.
        </p>
    </header>

    <div class="p-4 space-y-4">

        <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb]">
            <div class="p-4 pt-2">
                <div class="flex justify-between items-start">
                    <div class="flex gap-3">
                        <div class="rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ $subscription->product->image_url ?? asset('user/assets/images/product-img.jpg') }}"
                                alt="Product" class="w-[7rem] h-16 object-contain mix-multiply" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 leading-tight">
                                {{ $subscription->product->name ?? 'Product' }}
                            </h3>
                            <p class="text-sm text-gray-500">Qty: {{ $subscription->orderItems->sum('qty') }}</p>
                            <p class="text-sm text-gray-500">
                                Branch: {{ $subscription->branch->name ?? 'N/A' }} |
                                Zone: {{ $subscription->zone->name ?? 'N/A' }} |
                                Pincode: {{ $subscription->zonePincode->pincode ?? 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-md {{ $statusClasses[$subscription->status] ?? 'bg-gray-100 text-gray-700' }}">
                        {{ $statusLabels[$subscription->status] ?? 'Unknown' }}
                    </span>
                </div>
            </div>

            <div class="bg-blue-50 text-sm rounded-b-xl px-4 py-2 flex items-center justify-between text-gray-700">
                <span class="font-medium flex items-center gap-1">
                    <i class="fi fi-rr-steering-wheel text-gray-600 text-sm"></i>
                    {{ $subscription->assignTo->full_name ?? 'Not Assigned' }}
                </span>
                <span class="text-gray-600">Frequency:
                    <span class="font-medium text-gray-800">
                        {{ $subscription->schedule_type == 1 ? 'Daily' : ($subscription->schedule_type == 2 ? 'Weekly' : 'Monthly') }}
                    </span>
                </span>
            </div>
        </div>

        @if (
            !in_array($subscription->status, [\App\Models\Order::STATUS_CANCELLED, \App\Models\Order::STATUS_DELIVERED]) &&
                $subscription->payment_status == \App\Models\Order::PAYMENT_STATUS_UNPAID)
            <section class="">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="text-lg font-semibold text-red-600 mb-1">
                        <i class="fas fa-info-circle"></i> Your last payment attempt failed
                    </h2>

                    <p class="text-sm text-gray-500">
                        The previous payment attempt failed. Please retry to proceed with your order.
                    </p>
                    <div class="text-start">
                        <a href="{{ route('panel.user.order.retry-payment', secureToken($subscription->id)) }}"
                            class="inline-flex items-center text-blue-500 text-sm font-semibold py-2">

                            <span><i class="fas fa-redo"></i> Retry Payment</span>
                        </a>
                    </div>
                </div>
            </section>
        @endif

        @php
            $totalDeliveryDays = calculateSubscriptionDeliveryDays(
                $subscription->start_date,
                $subscription->end_date,
                $subscription->schedule_type,
                $subscription->schedule_value,
            );
        @endphp


        <div class="text-center">
            <p class="text-sm text-gray-600 font-medium">
                <span class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}
                </span>
                –
                <span class="font-semibold text-gray-800">
                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
                </span>
                <br />
                ({{ $totalDeliveryDays }} {{ $totalDeliveryDays == 1 ? 'Delivery Day' : 'Delivery Days' }})

            </p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb] p-4 mt-3">
            <h3 class="font-semibold text-gray-900 text-base mb-2">Plan Summary</h3>

            <div class="text-sm text-gray-700 space-y-1">
                <div class="flex justify-between">
                    <span>Type</span>
                    <span>
                        {{ $subscription->schedule_type == 1 ? 'Daily' : ($subscription->schedule_type == 2 ? 'Weekly' : 'Monthly') }}
                    </span>
                </div>

                <div class="flex justify-between gap-10">
                    <span>Schedule</span>
                    <span class="text-right">
                        @php
                            $schedule = $subscription->schedule_value ?? [];
                            if (is_array($schedule)) {
                                $filtered_schedule = array_filter($schedule);
                            } else {
                                $filtered_schedule = $schedule;
                            }
                            $is_empty_schedule = empty($filtered_schedule);
                        @endphp

                        @if (!$is_empty_schedule)
                            @if (is_array($filtered_schedule))
                                {{ implode(', ', $filtered_schedule) }}
                            @else
                                {{ $filtered_schedule }}
                            @endif
                        @else
                            Every Day
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb] p-4 space-y-3 mt-4">
            <h3 class="font-semibold text-gray-900 text-base">Subscription Summary</h3>

            <div class="space-y-2 text-sm text-gray-700">
                <div class="flex justify-between">
                    <span>Price</span>
                    <span>{{ format_price($subscription->total, 2) }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Start Date</span>
                    <span>{{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}</span>
                </div>

                <div class="flex justify-between">
                    <span>End Date</span>
                    <span>{{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}</span>
                </div>

                <div class="flex justify-between">
                    <span>Driver Assigned</span>
                    <span>{{ $subscription->assignTo->full_name ?? 'Not Assigned' }}</span>
                </div>
            </div>
        </div>

        {{-- Order Logs Section Start --}}
        @if ($subscriptionOrders->count() > 0)
            <div id="logsTabs" class="sticky top-0 z-10 pt-4">
                <div class="flex justify-between bg-gray-100 rounded-xl p-1 text-sm font-medium">
                    <button data-tab="pending" class="tab-btn active flex-1 py-2 text-center rounded-lg">Pending</button>
                    <button data-tab="delivered" class="tab-btn flex-1 py-2 text-center rounded-lg">Delivered</button>
                    <button data-tab="cancelled" class="tab-btn flex-1 py-2 text-center rounded-lg">Cancelled</button>
                </div>
            </div>

            {{-- Added max-h-96 and overflow-y-scroll --}}
            <div id="logListContainer" class="max-h-96 overflow-y-scroll pt-2">
                <div id="logList" class="space-y-2">
                    @foreach ($subscriptionOrders as $order)
                        <div
                            class="log-card {{ $order->status == Order::STATUS_DELIVERED
                                ? 'delivered-tab'
                                : (in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_ASSIGNED, Order::STATUS_INROUTE])
                                    ? 'pending-tab'
                                    : ($order->status == Order::STATUS_CANCELLED
                                        ? 'cancelled-tab'
                                        : '')) }}">
                            {{-- We are NOT using the @include('panel.user.order.include.details', ...) here directly, 
                                but the content it renders (@include('panel.user.order.include.single', ...)) 
                                which is assumed to be inside the .log-card --}}
                            @include('panel.user.order.include.single', ['order' => $order])
                        </div>
                    @endforeach
                </div>

                {{-- Placeholder for the "No orders found" message --}}
                <div id="noOrdersMessage" class="hidden flex flex-col items-center justify-center min-h-[22rem] py-10">
                    <img src="{{ asset('user/assets/icons/no-order.png') }}" class="w-[80px] h-auto" alt="No Orders Icon">
                    <p class="text-center text-gray-800 font-normal text-[15px] py-2">No orders found for this status.</p>
                </div>
            </div>
        @endif
        {{-- Order Logs Section End --}}


        <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb] p-4">
            <div class="flex items-start justify-between flex-col">
                <div>
                    <h3 class="font-semibold text-gray-900 text-base mb-1">Need Help?</h3>
                    <p class="text-sm text-gray-600 leading-snug">
                        If you’re facing any issue with your subscription, you can raise a support ticket for quick
                        assistance.
                    </p>
                </div>
                <div class="mt-2">
                    @if (checkMobileViewActivated())
                        <a href="{{ route('panel.user.support-tickets.create', ['ticket_type_id' => secureToken($subscription->id), 'app_back' => true]) }}"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                            <i class="fa-solid fa-phone-volume text-base"></i>
                            Raise Ticket
                        </a>
                    @else
                        <a href="{{ route('panel.user.support-tickets.create', ['ticket_type_id' => secureToken($subscription->id)]) }}"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">
                            <i class="fa-solid fa-phone-volume text-base"></i>
                            Raise Ticket
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb] p-4">
            <h3 class="font-semibold text-gray-900 text-base mb-2">Delivery Address</h3>
            <p class="text-sm text-gray-600 leading-snug">{{ $subscription->to }}</p>
            <p class="text-sm text-gray-600">
                Branch: {{ $subscription->branch->name ?? 'N/A' }} |
                Zone: {{ $subscription->zone->name ?? 'N/A' }} |
                Pincode: {{ $subscription->zonePincode->pincode ?? 'N/A' }}
            </p>
        </div>




        <div class="grid grid-cols-1">
            {{-- && $subscription->payment_status == App\Models\Order::PAYMENT_STATUS_PAID  --}}
            @if (
                $subscription->status != App\Models\Order::STATUS_CANCELLED &&
                    now()->lt(\Carbon\Carbon::parse($subscription->end_date)))
                <button id="openConfirmCancel"
                    class="text-red-500 font-semibold py-5 text-sm rounded-md transition-colors flex justify-center items-center gap-2">
                    <i class="fas fa-times"></i>
                    Cancel Subscription
                </button>
            @endif
        </div>
    </div>

    <div id="overlay" class="fixed inset-0 bg-black/40 backdrop-blur-sm hidden"></div>

@endsection

@push('script')
    <script>
        // Function to handle tab clicks and filter logs
        function filterLogs(selectedTab) {
            let visibleCount = 0;
            const logCards = document.querySelectorAll(".log-card");
            const noOrdersMessage = document.getElementById("noOrdersMessage");

            logCards.forEach(card => {
                const isVisible = card.classList.contains(`${selectedTab}-tab`);
                card.style.display = isVisible ? "block" : "none";
                if (isVisible) {
                    visibleCount++;
                }
            });

            // Show/hide the "No Orders Found" message
            if (visibleCount === 0) {
                noOrdersMessage.classList.remove("hidden");
            } else {
                noOrdersMessage.classList.add("hidden");
            }
        }

        // Tab click event listeners
        document.querySelectorAll(".tab-btn").forEach(tab => {
            tab.addEventListener("click", () => {
                document.querySelectorAll(".tab-btn").forEach(t => t.classList.remove("active"));
                tab.classList.add("active");
                const selected = tab.dataset.tab;
                filterLogs(selected);
            });
        });

        // Modal Logic (Unchanged)
        document.getElementById("openConfirmCancel").addEventListener("click", () => {
            document.getElementById("confirmCancelModal").classList.remove("hidden");
        });
        document.getElementById("cancelNo").addEventListener("click", () => {
            document.getElementById("confirmCancelModal").classList.add("hidden");
        });
        document.getElementById("cancelYes").addEventListener("click", () => {
            document.getElementById("confirmCancelModal").classList.add("hidden");

            let url = "{{ route('panel.user.subscriptions.update-status', secureToken($subscription->id)) }}";
            let method = "GET";

            let data = {
                status: "{{ \App\Models\Order::STATUS_CANCELLED }}",
                _token: "{{ csrf_token() }}"
            };

            $.ajax({
                url: url,
                method: method,
                data: data,
                success: function(response) {
                    if (response.success) {
                        window.location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function(error) {
                    console.error("AJAX Error:", error);
                    alert("Something went wrong. Try again.");
                }
            });
        });


        // Initially show Pending tab and check if it has content
        document.addEventListener('DOMContentLoaded', () => {
            filterLogs("pending");
        });
    </script>
@endpush
