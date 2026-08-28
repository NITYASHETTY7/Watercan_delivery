@extends('layouts.main')
@section('title', __('Thankyou'))
@section('content')

    @php
        if ($order->type == \App\Models\Order::TYPE_EXPRESS) {
            $trackRoute = route('panel.user.order.show', [secureToken($order->id), 'app_back' => true]);
        } else {
            $trackRoute = route('panel.user.subscriptions.show', [secureToken($order->id), 'app_back' => true]);
        }

        // Get the redirection route for the back button
        $redirectRoute = route('panel.user.order.index',['app_back' => true]);
    @endphp



    <div class="flex flex-col items-center justify-between min-h-[92vh] p-4">
        <div class="flex-1 flex flex-col justify-center items-center">
            <div class="flex justify-center mb-4">
                <img src="{{ asset('user/assets/images/checkout-check.png') }}" alt="" class="w-[78%]"
                    style="mix-blend-mode: multiply;" />
            </div>

            <h2 class="text-xl font-semibold text-gray-900 text-center">
                Your Order has been accepted
            </h2>
            <p class="text-gray-600 mt-0 text-center">
                Your item has been placed and is on its way to being processed
            </p>
        </div>

        <div class="w-full max-w-md text-center">
            <div class="flex items-center justify-center">
                <span class="text-sm text-gray-600 font-medium">Order ID: </span>
                <span class="text-gray-800 text-sm font-semibold ms-1">
                    {{ $order->getPrefix() ?? '#ORD7842' }}
                </span>
            </div>

            <div class="text-[14px] text-gray-600 mt-1">
                Expected delivery by
                <span class="font-medium text-gray-900">
                    @if ($expectedDeliveryDate instanceof \Carbon\Carbon)
                        {{ $expectedDeliveryDate->format('F j, Y') }}
                    @else
                        {{ $expectedDeliveryDate }}
                    @endif
                </span>
            </div>

            <div class="mt-6">
                <a href="{{ $trackRoute }}" class="block w-full bg-blue-500 text-white font-semibold py-2 rounded-md">
                    Track Order
                </a>
            </div>
        </div>
    </div>

@endsection
@push('script')
    <script>
        // Set the redirection URL from the Blade variable
        const redirectRoute = "{{ $redirectRoute }}";
        history.replaceState(null, document.title, location.href);
        window.onpopstate = function () {
            window.location.replace(redirectRoute);
        };


        // Order mode toggle
        const orderModeLabels = document.querySelectorAll(".radio-label");
        const orderModeRadios = document.querySelectorAll(
            'input[name="orderMode"]'
        );
        const buyNowDate = document.getElementById("buyNowDate");
        const subscriptionFields = document.getElementById("subscriptionFields");

        orderModeRadios.forEach((radio) => {
            radio.addEventListener("change", () => {
                // Remove active class from all
                orderModeLabels.forEach((label) => label.classList.remove("active"));

                // Add active to selected
                const parentLabel = radio.closest(".radio-label");
                parentLabel.classList.add("active");

                // Show/hide sections
                if (radio.value === "subscription") {
                    buyNowDate.classList.add("hidden");
                    subscriptionFields.classList.remove("hidden");
                } else {
                    buyNowDate.classList.remove("hidden");
                    subscriptionFields.classList.add("hidden");
                }
            });
        });

        // Frequency toggle
        const frequencyRadios = document.querySelectorAll(
            'input[name="frequency"]'
        );
        const frequencyOptions = document.getElementById("frequencyOptions");
        const weeklyOptions = document.getElementById("weeklyOptions");
        const monthlyOptions = document.getElementById("monthlyOptions");

        frequencyRadios.forEach((radio) => {
            radio.addEventListener("change", () => {
                frequencyOptions.classList.remove("hidden");
                weeklyOptions.classList.toggle("hidden", radio.value !== "weekly");
                monthlyOptions.classList.toggle("hidden", radio.value !== "monthly");
            });
        });

        // Generate monthly date buttons dynamically
        const monthlyGrid = document.querySelector("#monthlyOptions .grid");
        for (let i = 1; i <= 31; i++) {
            const dateEl = document.createElement("span");
            dateEl.textContent = i;
            dateEl.className =
                "date-btn w-8 h-8 flex items-center justify-center rounded-full border text-sm font-medium text-gray-700 cursor-pointer transition";
            monthlyGrid.appendChild(dateEl);
        }

        // Day/date button selection toggle
        document.addEventListener("click", (e) => {
            if (
                e.target.classList.contains("day-btn") ||
                e.target.classList.contains("date-btn")
            ) {
                e.target.classList.toggle("bg-blue-600");
                e.target.classList.toggle("text-white");
            }
        });

        document.querySelectorAll("input[name='frequency']").forEach((radio) => {
            radio.addEventListener("change", (e) => {
                document
                    .querySelectorAll("#frequencyGroup .frequency-radio")
                    .forEach((label) => label.classList.remove("active"));
                e.target.closest(".frequency-radio").classList.add("active");
            });
        });
    </script>

@endpush