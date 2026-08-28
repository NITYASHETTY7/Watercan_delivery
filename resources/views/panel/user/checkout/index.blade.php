@extends('layouts.main')
@section('title', __('Checkout'))
@section('content')

    @php

        $pincode = $userAddress->details['pincode'] ?? null;
        $branchZone = getBranchZoneByPincode($pincode);
        $formattedAddress = formatUserAddress($userAddress);
        $isDeliveryAvailable = checkPincodeExists($pincode);

    @endphp

    <div>
        <div class="p-4 space-y-5 max-w-xl mx-auto">
            <!-- Delivery Details -->
            <div class="section-card">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3">
                        <h2 class="font-semibold text-gray-900 text-lg flex items-center gap-2">
                            Delivery Details
                        </h2>
                        <span class="text-blue-500 text-[14px] font-semibold"><a
                                href="{{ route('panel.user.cart.index', ['sso_token' => request()->get('sso_token')]) }}">Change</a></span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex flex-wrap items-start">
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 break-words">
                                    <i class="fi fi-bs-home-location text-blue-600 text-md me-1 mt-0.5"></i>
                                    {!! $formattedAddress !!}
                                </p>
                                @if (!$isDeliveryAvailable)
                                    <p class="text-red-600 mt-1 fw-semibold">
                                        Delivery is currently unavailable for pincode {{ $pincode }}, service is coming soon.
                                    </p>
                                @else
                                    <p class="text-gray-600 mt-1">
                                        Branch: {{ @$branchZone['branch_name'] }} | Zone: {{ @$branchZone['zone_name'] }} |
                                        Pincode: {{ @$pincode }}
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="section-card flex justify-between items-center">
                <h2 class="font-semibold text-gray-900 flex items-center">
                    <i class="fi fi-sr-truck-clock text-blue-600"></i> Delivered By
                </h2>
                <span class="px-3 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded-md">
                    <i class="fas fa-clock"></i>
                    Within 24 Hrs
                </span>
            </div>

            <!-- Product Info -->
            <div class="section-card">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-900 mb-4 text-lg flex items-center gap-2">
                        Product Info
                    </h2>

                    {{-- Loop through each cart item to display its details --}}
                    @foreach ($carts as $cart)
                        @php
                            $isFirstProduct = $cart->type_id == 1;

                            if ($isFirstProduct) {
                                $productImage = asset('user/assets/images/product-img.jpg');
                            } else {
                                $productImage = asset('user/assets/images/product-img.jpg');
                            }

                        @endphp
                        <div class="flex items-start gap-4 {{ !$loop->last ? 'mb-4 pb-4 border-b border-gray-100' : '' }}">
                            <img src="{{ $productImage }}" alt="Product"
                                class="w-20 h-20 rounded-xl object-contain border border-gray-200 flex-shrink-0" />
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 leading-tight truncate">
                                    {{ $cart->product->name }} ({{ $cart->product->weight }}L Can)
                                </p>
                                <p class="text-sm text-gray-500 mt-0.5">
                                    Qty: {{ $cart->qty }} {{ $cart->qty == 1 ? 'Can' : 'Cans' }}
                                </p>

                                @php
                                    $basePrice = $cart->product->base_price ?? 0;
                                    $currentPrice = $cart->product->price ?? 0; // Use product price for unit display
                                    $discountPercent = 0;

                                    if ($basePrice > 0 && $currentPrice < $basePrice) {
                                        $discountPercent = round((($basePrice - $currentPrice) / $basePrice) * 100);
                                    }
                                @endphp

                                <div class="mt-2 flex items-center gap-2">
                                    {{-- Display total price for this line item --}}
                                    <span class="text-base font-semibold text-gray-900 flex items-center">
                                        {{ format_price($cart->total) }}
                                    </span>

                                    @if ($discountPercent > 0)
                                        <span class="text-xs text-gray-400 line-through flex items-center">
                                            {{ format_price($basePrice * $cart->qty) }}
                                        </span>
                                        <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-md">
                                            Save {{ $discountPercent }}%
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Order Summary -->
            <div class="section-card">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-900 mb-3 text-lg flex items-center gap-2">
                        Order Summary
                    </h2>

                    @php
                        // Initialize totals
                        $grandTotal = 0;
                        $gstPercent = getSetting('gst_rate') ?? 18;

                        foreach ($carts as $item) {
                            $lineTotal = $item->total ?? $item->price * $item->qty;
                            $grandTotal += $lineTotal;
                        }

                        // GST back-calculation (inclusive pricing)
                        $totalGstAmount = $grandTotal - $grandTotal / (1 + $gstPercent / 100);

                        $cgstPercent = $gstPercent / 2;
                        $sgstPercent = $gstPercent / 2;

                        $cgstAmount = $totalGstAmount / 2;
                        $sgstAmount = $totalGstAmount / 2;

                        $itemTotalExclTax = $grandTotal - $totalGstAmount;

                        // Format helpers
                        $formattedItemTotal = format_price($itemTotalExclTax);
                        $formattedCgst = format_price($cgstAmount);
                        $formattedSgst = format_price($sgstAmount);
                        $formattedGrandTotal = format_price($grandTotal);
                    @endphp

                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex justify-between">
                            <span>Item Subtotal (Excl. GST)</span>
                            <span>{{ $formattedItemTotal }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>CGST ({{ $cgstPercent }}%)</span>
                            <span>{{ $formattedCgst }}</span>
                        </div>

                        <div class="flex justify-between">
                            <span>SGST ({{ $sgstPercent }}%)</span>
                            <span>{{ $formattedSgst }}</span>
                        </div>

                    </div>

                    <div class="border-t border-gray-200 my-3"></div>

                    <div class="flex justify-between items-center text-base font-semibold text-gray-900">
                        <span>
                            Grand Total
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fi fi-bs-file-invoice"></i>
                                Inclusive of all taxes and delivery charges.
                            </p>
                        </span>
                        <span>{{ $formattedGrandTotal }}</span>
                    </div>
                </div>
            </div>


            <!-- Order Mode Section -->
            <div class="section-card bg-white"style="padding-bottom:50px;">
                <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                    <h2 class="font-semibold text-gray-900 mb-4 text-lg flex items-center">Order Mode</h2>

                    <!-- Mode Selection -->
                    <div class="flex items-center gap-3 mb-5 flex-wrap">
                        <label
                            class="radio-label order-mode active cursor-pointer flex items-center gap-2 px-4 py-2 border rounded-lg text-sm font-medium text-gray-700">
                            <input type="radio" name="orderMode" value="buyNow" class="hidden" checked />
                            <i class="fi fi-bs-shopping-bag text-blue-600 text-base leading-4"></i>
                            Buy Now
                        </label>
                        <label
                            class="radio-label order-mode cursor-pointer flex items-center gap-2 px-4 py-2 border rounded-lg text-sm font-medium text-gray-700">
                            <input type="radio" name="orderMode" value="subscription" class="hidden" />
                            <i class="fi fi-bs-refresh text-blue-600 text-base leading-4"></i>
                            Subscription
                        </label>
                    </div>

                    <!-- Buy Now Date Picker -->
                    <div id="buyNowDate" class="mb-5">
                        <label class="block text-sm font-medium text-gray-800 mb-1">Choose Date</label>
                        <input type="date" id="buyNowDateInput"value="{{ now()->format('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-white outline-none" />
                    </div>

                    <!-- Subscription Section -->
                    <div id="subscriptionFields" class="hidden space-y-5">

                        <!-- Date Range -->
                        <div class="flex flex-col sm:flex-row justify-between gap-3 mt-3">
                            <div class="w-full sm:w-1/2">
                                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" id="startDate" name="start_date"
                                    class="border rounded-lg px-3 py-2 w-full bg-white">
                            </div>
                            <div class="w-full sm:w-1/2">
                                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                <input type="date" id="endDate" name="end_date"
                                    class="border rounded-lg px-3 py-2 w-full bg-white">
                            </div>
                        </div>

                        <!-- Frequency Selection -->
                        <div>
                            <label class="block font-medium text-sm text-gray-800 mb-2">Frequency</label>
                            <div class="flex flex-wrap gap-3" id="frequencyGroup">
                                <label
                                    class="radio-label cursor-pointer frequency-radio px-4 py-2 border rounded-lg text-sm font-medium text-gray-700">
                                    <input type="radio" name="frequency" value="daily" class="hidden" />
                                    Daily
                                </label>
                                <label
                                    class="radio-label cursor-pointer frequency-radio px-4 py-2 border rounded-lg text-sm font-medium text-gray-700">
                                    <input type="radio" name="frequency" value="weekly" class="hidden" />
                                    Weekly
                                </label>
                                <label
                                    class="radio-label cursor-pointer frequency-radio px-4 py-2 border rounded-lg text-sm font-medium text-gray-700">
                                    <input type="radio" name="frequency" value="monthly" class="hidden" />
                                    Monthly
                                </label>
                            </div>
                        </div>

                        <!-- Weekly / Monthly Options -->
                        <div id="frequencyOptions" class="hidden space-y-4 min-h-[50px]">
                            <!-- Weekly Days -->
                            @php
                                $days = [
                                    ['key' => 'Mon', 'label' => 'M'],
                                    ['key' => 'Tue', 'label' => 'T'], // Tuesday
                                    ['key' => 'Wed', 'label' => 'W'],
                                    ['key' => 'Thurs', 'label' => 'T'], // Thursday
                                    ['key' => 'Fri', 'label' => 'F'],
                                    ['key' => 'Sat', 'label' => 'S'], // Saturday
                                    ['key' => 'Sun', 'label' => 'S'], // Sunday
                                ];
                            @endphp

                            <div id="weeklyOptions" class="hidden">
                                <p class="font-medium text-gray-700 mb-2">Select Days</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($days as $day)
                                        <span
                                            class="day-btn w-8 h-8 flex items-center justify-center rounded-full border text-sm font-medium text-gray-700 cursor-pointer transition"
                                            data-key="{{ $day['key'] }}">{{ $day['label'] }}</span>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Monthly Dates -->
                            <div id="monthlyOptions" class="hidden">
                                <p class="font-medium text-gray-700 mb-2">Select Dates</p>
                                <div class="grid grid-cols-7 gap-2"></div>
                            </div>
                        </div>
                    </div>

                    <div id="subscriptionRequired" class="hidden text-red-500 text-sm mt-2 text-center">
                        <p class="mb-0 text-[13px] text-left">Please complete subscription details before continuing.</p>
                    </div>

                </div>
            </div>

            <!-- Delivered By -->
        </div>
        <form id="checkoutForm" action="{{ route('panel.user.order.store') }}" method="POST">
            @csrf

            <input type="hidden" name="order_mode" id="orderModeInput" value="buyNow">
            <input type="hidden" name="start_date" id="startDateInputHidden">
            <input type="hidden" name="end_date" id="endDateInputHidden">
            <input type="hidden" name="schedule_type" id="scheduleTypeHidden">
            <input type="hidden" name="schedule_value" id="scheduleValueHidden">
            <input type="hidden" name="buy_now_date" id="buyNowDateHidden">
            <input type="hidden" name="calculated_total" id="calculatedTotalInput">
            <input type="hidden" name="sso_token" value="{{ request()->get('sso_token') }}">
            <input type="hidden" name="address_id" value="{{ $userAddress->id }}">

            <!-- Fixed Footer -->
            <div class="fixed bottom-0 left-0 right-0 bg-white/95 border-t p-4">
                <div class="max-w-xl mx-auto flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">Total</p>
                        <p class="text-xl font-semibold text-gray-900" id="totalPrice">
                            {{ format_price($carts->sum('total')) }}
                        </p>
                    </div>
                    <button type="submit" @if (!$isDeliveryAvailable) disabled @endif
                        class="flex items-center gap-2 bg-blue-500 text-white font-medium px-5 py-2 rounded-lg shadow-md 
                            hover:shadow-lg hover:opacity-95 transition
                            disabled:bg-blue-300 disabled:text-gray-100 disabled:cursor-not-allowed disabled:shadow-none">
                        <span>Pay Now</span>
                        <i class="fi fi-bs-arrow-alt-right leading-4"></i>
                    </button>
                </div>
            </div>
        </form>


    </div>
@endsection
@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {


            // 1. Get Today's Date Object (Start of Day, Local Time)
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // 2. Calculate Tomorrow's Date Object
            const tomorrowDateObj = new Date(today);
            tomorrowDateObj.setDate(today.getDate() + 1);

            // 3. Helper function to format Date object into YYYY-MM-DD string (Local Time)
            const formatDate = (date) => {
                const year = date.getFullYear();
                // Month is 0-indexed, so add 1. Pad with leading zero if needed.
                const month = String(date.getMonth() + 1).padStart(2, '0');
                // Pad with leading zero if needed.
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            };

            const tomorrowStr = formatDate(tomorrowDateObj); // This will be 2025-12-09 (Tomorrow)
            const todayStr = formatDate(today); // This will be 2025-12-08 (Today)


            const startDateInput = document.getElementById("startDate");
            const endDateInput = document.getElementById("endDate");
            const buyNowDateInput = document.getElementById("buyNowDateInput");

            if (startDateInput) {
                // Set min start date to tomorrow's date string
                startDateInput.setAttribute("min", tomorrowStr);
            }


            // Set min date for all date inputs
            [endDateInput, buyNowDateInput].forEach(el => {
                if (el) el.setAttribute("min", todayStr);
            });

            // Default start date = today
            if (startDateInput) startDateInput.value = today;

            // === Start Date Validation ===
            startDateInput?.addEventListener("change", function() {
                const startDate = new Date(this.value);
                const now = new Date();
                now.setHours(0, 0, 0, 0);

                if (startDate < now) {
                    this.value = "";
                    showInlineError("subscriptionRequired", "Start date cannot be before today.");
                    return;
                }

                if (endDateInput.value) {
                    const endDate = new Date(endDateInput.value);
                    // CHECK: End date cannot be before OR the same as start date
                    if (endDate <= startDate) { // <--- MODIFIED to use <=
                        endDateInput.value = "";
                        showInlineError("subscriptionRequired",
                            "End date cannot be on or before start date."); // <-- Updated error message
                    }
                }

                calculateTotal();
            });

            // === End Date Validation ===
            endDateInput?.addEventListener("change", function() {
                if (!startDateInput.value) {
                    showInlineError("subscriptionRequired", "Please select start date first.");
                    this.value = "";
                    return;
                }

                const startDate = new Date(startDateInput.value);
                const endDate = new Date(this.value);

                if (endDate <= startDate) { // <--- MODIFIED to use <=
                    this.value = "";
                    showInlineError("subscriptionRequired",
                        "End date cannot be on or before start date."); // <-- Updated error message
                }

                calculateTotal();
            });

            // ===== Order mode toggle =====
            const orderModeLabels = document.querySelectorAll(".order-mode");
            const orderModeRadios = document.querySelectorAll('input[name="orderMode"]');
            const buyNowDate = document.getElementById("buyNowDate");
            const subscriptionFields = document.getElementById("subscriptionFields");

            orderModeRadios.forEach(radio => {
                radio.addEventListener("change", () => {
                    orderModeLabels.forEach(label => label.classList.remove("active"));
                    radio.closest(".order-mode").classList.add("active");

                    document.getElementById("orderModeInput").value = radio.value;

                    if (radio.value === "subscription") {
                        buyNowDate.classList.add("hidden");
                        subscriptionFields.classList.remove("hidden");
                    } else {
                        buyNowDate.classList.remove("hidden");
                        subscriptionFields.classList.add("hidden");
                    }

                    calculateTotal();
                });
            });

            // ===== Frequency toggle =====
            const frequencyRadios = document.querySelectorAll('input[name="frequency"]');
            const frequencyOptions = document.getElementById("frequencyOptions");
            const weeklyOptions = document.getElementById("weeklyOptions");
            const monthlyOptions = document.getElementById("monthlyOptions");

            frequencyRadios.forEach(radio => {
                radio.addEventListener("change", () => {
                    // remove active from all
                    document.querySelectorAll(".frequency-radio").forEach(l => l.classList.remove(
                        "active"));
                    radio.closest(".frequency-radio").classList.add("active");

                    frequencyOptions.classList.remove("hidden");
                    weeklyOptions.classList.toggle("hidden", radio.value !== "weekly");
                    monthlyOptions.classList.toggle("hidden", radio.value !== "monthly");
                    calculateTotal();
                });
            });

            // === Generate monthly buttons dynamically ===
            const monthlyGrid = document.querySelector("#monthlyOptions .grid");
            for (let i = 1; i <= 31; i++) {
                const dateEl = document.createElement("span");
                dateEl.textContent = i;
                dateEl.className =
                    "date-btn w-8 h-8 flex items-center justify-center rounded-full border text-sm font-medium text-gray-700 cursor-pointer transition";
                monthlyGrid.appendChild(dateEl);
            }

            // === Toggle selected day/date ===
            document.addEventListener("click", e => {
                if (e.target.classList.contains("day-btn") || e.target.classList.contains("date-btn")) {
                    e.target.classList.toggle("bg-blue-600");
                    e.target.classList.toggle("text-white");
                    calculateTotal();
                }
            });

            // === Update hidden buy now date ===
            buyNowDateInput.addEventListener("change", e => {
                document.getElementById("buyNowDateHidden").value = e.target.value;
            });

            function handleBuyNowTotal() {
                const buyDate = buyNowDateInput.value;

                // If date not selected, reset price
                if (!buyDate) {
                    resetTotal();
                    return;
                }

                $.ajax({
                    url: "{{ route('panel.user.checkout.calculateTotal') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_mode: "buyNow",
                        date: buyDate,
                        sso_token: "{{ request()->get('sso_token') }}",
                    },
                    success: function(response) {
                        $("#totalPrice").text(response.formatted_total);
                        $("#calculatedTotalInput").val(response.total);
                    },
                });
            }



            // === Calculate total - MODIFIED FOR DATE VALIDATION/UNSELECTION ===
            function calculateTotal() {
                const orderMode = document.getElementById("orderModeInput").value;
                if (orderMode === "buyNow") {
                    handleBuyNowTotal();
                    return; // Skip for buyNow mode
                }

                const frequency = document.querySelector("input[name='frequency']:checked")?.value || null;
                const startDate = startDateInput.value;
                console.log(startDate);
                const endDate = endDateInput.value;

                // Clear any previous error messages related to subscription dates/days
                const messageDiv = document.getElementById("subscriptionRequired");
                if (messageDiv) messageDiv.classList.add("hidden");


                let weeklyDaysElements = [...document.querySelectorAll(".day-btn.bg-blue-600")];
                let monthlyDatesElements = [...document.querySelectorAll(".date-btn.bg-blue-600")];

                let weeklyDays = weeklyDaysElements.map(d => d.dataset.key);
                let monthlyDates = monthlyDatesElements.map(d => d.textContent);

                if (startDate && endDate && frequency === "monthly" && monthlyDates.length > 0) {
                    const [sy, sm, sd] = startDate.split("-").map(Number);
                    const [ey, em, ed] = endDate.split("-").map(Number);

                    const start = new Date(sy, sm - 1, sd);
                    const end = new Date(ey, em - 1, ed);

                    let invalidMonthlyDates = [];
                    let newMonthlyDatesElements = [];

                    // Filter monthly dates that are outside the range's months
                    for (const dateEl of monthlyDatesElements) {
                        const date = parseInt(dateEl.textContent);

                        let isValid = false;
                        let currentMonth = new Date(start.getFullYear(), start.getMonth(), 1);

                        // Check every month in the range
                        while (currentMonth <= end) {
                            const year = currentMonth.getFullYear();
                            const month = currentMonth.getMonth() + 1;

                            // Create the full date for the selected day in this month
                            const dayInMonth = new Date(year, month - 1, date);

                            // Check if it's a real day (e.g., date 31 in Feb) and falls within the start/end bounds
                            if (dayInMonth.getMonth() + 1 === month && dayInMonth >= start && dayInMonth <= end) {
                                isValid = true;
                                break;
                            }

                            // Move to the next month
                            currentMonth.setMonth(currentMonth.getMonth() + 1, 1);
                        }

                        if (isValid) {
                            newMonthlyDatesElements.push(dateEl);
                        } else {

                            invalidMonthlyDates.push(date);
                            // Unselect the invalid date button
                            dateEl.classList.remove("bg-blue-600");
                            dateEl.classList.remove("text-white");
                        }
                    }



                    if (invalidMonthlyDates.length > 0) {
                        showInlineError("subscriptionRequired",
                            `Selected dates are invalid. Adjust the start and end dates.`);
                    }

                    // Update the array of selected dates after unselecting invalid ones
                    monthlyDates = newMonthlyDatesElements.map(d => d.textContent);
                }

                if (startDate && endDate && startDate === endDate) {
                    showInlineError(
                        "subscriptionRequired",
                        "Start date and end date cannot be the same. Please select a valid date range."
                    );

                    // stop further calculation
                    return;
                }


                // Note: You can apply similar filtering logic for weeklyDays if you want to unselect days 
                // that don't occur in the range (e.g., if the range is a single day that isn't selected).



                // Update hidden fields with potentially filtered/unselected values
                document.getElementById("startDateInputHidden").value = startDate;
                document.getElementById("endDateInputHidden").value = endDate;
                document.getElementById("scheduleValueHidden").value =
                    frequency === 'weekly' ? weeklyDays.join(',') :
                    frequency === 'monthly' ? monthlyDates.join(',') : '';

                let scheduleType = null;
                if (frequency === "daily") scheduleType = 1;
                else if (frequency === "weekly") scheduleType = 2;
                else if (frequency === "monthly") scheduleType = 3;
                document.getElementById("scheduleTypeHidden").value = scheduleType;

                // Only calculate total if minimum required fields are present (and frequency isn't 'daily' which doesn't need weekly/monthly data)
                if (!startDate || !endDate || !frequency) return;
                if (frequency === "weekly" && weeklyDays.length === 0) return;
                if (frequency === "monthly" && monthlyDates.length === 0) return;


                // Perform AJAX call to calculate total based on the valid, currently selected dates
                $.ajax({
                    url: "{{ route('panel.user.checkout.calculateTotal') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        frequency,
                        start_date: startDate,
                        sso_token: "{{ request()->get('sso_token') }}",
                        end_date: endDate,
                        weekly_days: weeklyDays,
                        monthly_dates: monthlyDates // This now only contains valid dates
                    },
                    success: function(response) {
                        $("#totalPrice").text(response.formatted_total);
                        $("#calculatedTotalInput").val(response.total);
                    },
                });
            }

            // === Inline error helper ===
            function showInlineError(elementId, message) {
                const msgDiv = document.getElementById(elementId);

                if (msgDiv) {
                    msgDiv.innerHTML = `
                        <div class="flex items-center gap-2 px-3 mt-5 py-1 rounded-md bg-red-100 border border-red-300 shadow-sm animate-fade-in">
                            <span class="text-red-600">
                                <!-- Heroicons Exclamation Circle -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 9v3m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                                </svg>
                            </span>
                            <p class="text-sm text-red-800 leading-5 mb-0">
                                ${message}
                            </p>
                        </div>
                    `;

                    msgDiv.classList.remove("hidden");

                    // Smooth scroll to error
                    msgDiv.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });

                    // Optional: fade-out after 5 seconds
                    // setTimeout(() => {
                    //     msgDiv.classList.add("hidden");
                    // }, 8000);
                }
            }




            // === Form submit validation - MODIFIED to ensure calculateTotal runs first ===
            document.getElementById("checkoutForm").addEventListener("submit", function(e) {
                e.preventDefault();
                const orderMode = document.getElementById("orderModeInput").value;
                const messageDiv = document.getElementById("subscriptionRequired");
                if (messageDiv) messageDiv.classList.add("hidden");

                if (orderMode === "buyNow") {
                    const buyDate = buyNowDateInput.value;
                    if (!buyDate) {
                        showInlineError("subscriptionRequired", "Please select a date before continuing.");
                        return;
                    }
                    document.getElementById("buyNowDateHidden").value = buyDate;
                    this.submit();
                    return;
                }

                // Call calculateTotal here to ensure any invalid dates are unselected
                // and the hidden fields are updated with the final valid list before final validation
                calculateTotal();

                // === Subscription validation ===
                const startDate = startDateInput.value;
                const endDate = endDateInput.value;
                const frequency = document.querySelector("input[name='frequency']:checked")?.value;
                // Fetch the elements again after calculateTotal might have unselected some
                const weeklyDaysElements = [...document.querySelectorAll(".day-btn.bg-blue-600")];
                const monthlyDatesElements = [...document.querySelectorAll(".date-btn.bg-blue-600")];

                if (!startDate || !endDate) {
                    showInlineError("subscriptionRequired", "Please select start and end dates.");
                    return;
                }

                if (!frequency) {
                    showInlineError("subscriptionRequired",
                        "Please select a frequency (Daily, Weekly, or Monthly).");
                    return;
                }

                if (frequency === "weekly" && weeklyDaysElements.length === 0) {
                    showInlineError("subscriptionRequired",
                        "Please select at least one day for weekly subscription.");
                    return;
                }

                if (frequency === "monthly" && monthlyDatesElements.length === 0) {
                    showInlineError("subscriptionRequired",
                        "Please select at least one date for monthly subscription.");
                    return;
                }

                // --- NEW VALIDATION: Check if selected schedule is valid for date range ---
                // NOTE: This section remains largely the same, but now it checks against the *filtered* list.
                // However, since calculateTotal() already filters the list and updates the UI,
                // we only need to ensure that the remaining list isn't empty (which is covered above).
                // The original complex range check logic is still good practice to ensure at least one
                // delivery *instance* exists, which is more robust than just checking for selected dates/days.

                if (startDate && endDate) {

                    const start = new Date(startDate);
                    const end = new Date(endDate);

                    const diffDays = Math.ceil((end - start) / (1000 * 60 * 60 * 24)) +
                        1; // Total days in range inclusive

                    if (diffDays <=
                        1
                    ) { // diffDays = total inclusive days. <= 1 means 1 day total (start=end) or less (invalid)
                        showInlineError("subscriptionRequired",
                            "The subscription end date must be after the start date.");
                        return;
                    }

                    if (frequency === "weekly") {
                        // Re-fetch days after calculateTotal
                        const weeklyDays = weeklyDaysElements.map(d => d.dataset.key);
                        let hasValidDay = false;
                        let currentDay = new Date(start);
                        while (currentDay <= end) {
                            const dayKeyMap = ['Sun', 'Mon', 'Tue', 'Wed', 'Thurs', 'Fri', 'Sat'];
                            const currentDayKey = dayKeyMap[currentDay.getDay()];

                            if (weeklyDays.includes(currentDayKey)) {
                                hasValidDay = true;
                                break;
                            }
                            currentDay.setDate(currentDay.getDate() + 1);
                        }

                        if (!hasValidDay) {
                            showInlineError("subscriptionRequired",
                                "No delivery day (based on selected weekly days) falls within the selected date range."
                            );
                            return;
                        }
                    }

                    if (frequency === "monthly") {
                        // Re-fetch dates after calculateTotal
                        const dates = monthlyDatesElements.map(d => parseInt(d.textContent));
                        let hasValidDate = false;
                        let currentMonth = new Date(start.getFullYear(), start.getMonth(),
                            1); // Start of first month

                        while (currentMonth <= end) {
                            const year = currentMonth.getFullYear();
                            const month = currentMonth.getMonth() + 1; // 1-based month

                            for (const date of dates) {
                                // Check if the selected date is a valid day in the current month
                                const dayInMonth = new Date(year, month - 1, date);

                                // Check if it's within the start/end bounds and is a real day
                                if (dayInMonth.getMonth() + 1 === month && dayInMonth >= start &&
                                    dayInMonth <= end) {
                                    hasValidDate = true;
                                    break;
                                }
                            }

                            if (hasValidDate) break;

                            // Move to the next month
                            currentMonth.setMonth(currentMonth.getMonth() + 1, 1);
                        }

                        if (!hasValidDate) {
                            showInlineError("subscriptionRequired",
                                "No delivery date (based on selected monthly dates) falls within the selected date range."
                            );
                            return;
                        }
                    }
                }
                // --- END NEW VALIDATION ---

                this.submit();
            });

            // Trigger total calculation initially for daily mode
            calculateTotal();
        });
    </script>
@endpush
