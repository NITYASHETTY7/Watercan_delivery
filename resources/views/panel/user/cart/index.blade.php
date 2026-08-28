@extends('layouts.main')
@section('title', __('My Cart'))
@section('content')
    @push('head')
        <style>
            /* Hide arrows for Chrome, Safari, Edge, and Opera */
            input[type=number]::-webkit-outer-spin-button,
            input[type=number]::-webkit-inner-spin-button {
                -webkit-appearance: none;
                margin: 0;
            }

            /* Hide arrows for Firefox */
            input[type=number] {
                -moz-appearance: textfield;
            }

            @keyframes shake {

                0%,
                100% {
                    transform: translateX(0);
                }

                20%,
                60% {
                    transform: translateX(-5px);
                }

                40%,
                80% {
                    transform: translateX(5px);
                }
            }

            .animate-shake {
                animation: shake 0.4s;
            }
        </style>
    @endpush
    <div class="min-h-screen max-w-md mx-auto md:max-w-2xl">
        <!-- Delivery Type -->
        <section class="p-4">
            <div
                class="bg-white border-custom rounded-xl shadow-sm flex items-center gap-3 px-4 py-3 hover:shadow-md transition">
                <i class="fi fi-rr-stopwatch text-2xl text-blue-500"></i>


                <div>
                    <div class="font-semibold text-gray-900">Express Delivery Available</div>
                    <div class="text-sm text-gray-500 leading-4">Delivery within 24 hours</div>
                </div>
            </div>

        </section>

        <!-- Cart Item -->
        <section class="px-4 pb-4">
            <div
                class="bg-white border border-[#dbdbdb] rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 p-4 ">
                @foreach ($carts as $cart)
                    @php
                        $user = auth()->user();

                        // 1. Identify if this is the main product
                        $isFirstProduct = $cart->type_id == 1;

                        // 2. Get the Global Settings for Min Qty
                        $minQtyB2C = getSetting('min_qty_b2c') ?? 1;
                        $minQtyB2B = getSetting('min_qty_b2b') ?? 1;

                        // 3. Determine the required Minimum for this specific row
                        if ($isFirstProduct) {
                            // Apply B2B or B2C logic
                            $requiredMin =
                                $user->account_type == App\Models\User::ACCOUNT_TYPE_BUSINESS ? $minQtyB2B : $minQtyB2C;
                            $minQty = $requiredMin;
                        } else {
                            // For other products, minimum is 0
                            $minQty = 0;
                        }

                        // 4. Set the current input value
                        // We use $cart->qty (the specific item in the loop), not a new DB query
                        $currentQty = $cart->qty ?? 0;

                        // Ensure the display value isn't lower than the calculated minimum
                        $displayQty = $currentQty >= $minQty ? $currentQty : $minQty;
                        if ($isFirstProduct) {
                            $productImage = asset('user/assets/images/product-img.jpg');
                        } else {
                            $productImage = asset('user/assets/images/product-img.jpg');
                        }

                    @endphp

                    <div class="flex gap-4 items-start group relative mb-5">
                        <div class="rounded-xl bg-gray-100 flex-shrink-0 flex items-center justify-center overflow-hidden">
                            <img src="{{ $productImage }}" alt="Product Image"
                                class="object-cover w-24 h-24 group-hover:scale-105 transition-transform duration-300"
                                style="object-fit: contain;mix-blend-mode: multiply;" />
                        </div>

                        <div class="flex flex-col justify-between flex-1 min-w-0">

                            <div class="flex justify-between items-start gap-3">
                                <div class="flex flex-col">
                                    <h3 class="text-base font-semibold text-gray-900 leading-snug truncate">
                                        {{ @$cart->product->name ?? 'Water Can' }}
                                    </h3>
                                    <p class="text-sm text-gray-500 mt-0.5">
                                        {{ @$cart->product->weight ?? '20' }}L Can
                                    </p>
                                </div>

                                <div class="text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="text-lg font-bold text-gray-900">
                                            {{ format_price(@$cart->product->price) }}
                                        </span>
                                        @if ($cart->product && $cart->product->base_price > 1)
                                            <span class="text-xs text-gray-400 line-through mt-0.5">
                                                {{ format_price(@$cart->product->base_price) }}
                                            </span>
                                        @endif
                                        <span class="text-xs text-gray-500 mt-0.5">
                                            per can
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div
                                    class="flex items-center border border-gray-300 rounded-full overflow-hidden bg-gray-50 shadow-sm">

                                    <button type="button"
                                        class="decBtn px-3 py-1 text-[18px] text-gray-700 bg-gray-100 hover:bg-gray-200 active:scale-95 transition"
                                        data-id="{{ $cart->id }}">
                                        −
                                    </button>

                                    <input type="number" id="qty_{{ $cart->id }}" min="{{ $minQty }}"
                                        max="999" value="{{ $displayQty }}"
                                        class="w-12 text-center bg-transparent outline-none border-0 py-1 text-sm font-medium text-gray-900" />

                                    <button type="button"
                                        class="incBtn px-3 py-1 text-[18px] text-gray-700 bg-gray-100 hover:bg-gray-200 active:scale-95 transition"
                                        data-id="{{ $cart->id }}">
                                        +
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if ($isFirstProduct && auth()->user()->account_type == App\Models\User::ACCOUNT_TYPE_BUSINESS)
                            <div
                                class="absolute bottom-0 left-2 bg-blue-100 text-blue-700 text-xs font-medium px-2 py-0.5 rounded-full shadow-sm">
                                Min <strong>{{ $minQtyB2B }} Cans</strong>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </section>

        <section class="px-4">
            <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-gray-900 text-lg">Billing Details</h2>
                </div>

                @php
                    $grandTotal = 0;
                    $gstPercent = getSetting('gst_rate') ?? 18;
                    foreach ($carts as $item) {
                        $lineTotal = $item->total ?? $item->price * $item->qty;
                        $grandTotal += $lineTotal;
                    }
                    $totalGstAmount = $grandTotal - $grandTotal / (1 + $gstPercent / 100);

                    $cgstPercent = $gstPercent / 2;
                    $sgstPercent = $gstPercent / 2;

                    $cgstAmount = $totalGstAmount / 2;
                    $sgstAmount = $totalGstAmount / 2;

                    $itemTotalExclTax = $grandTotal - $totalGstAmount;
                @endphp

                <div class="space-y-3 text-sm text-gray-700">
                    <div class="flex justify-between">
                        <span>Item Total (Excl. GST)</span>
                        <span id="billItemTotal">
                            {{ format_price($itemTotalExclTax) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>CGST ({{ $cgstPercent }}%)</span>
                        <span id="billCGST">
                            {{ format_price($cgstAmount) }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>SGST ({{ $sgstPercent }}%)</span>
                        <span id="billSGST">
                            {{ format_price($sgstAmount) }}
                        </span>
                    </div>

                    <div class="border-t border-gray-200 my-3"></div>

                    <div class="flex justify-between font-semibold text-gray-900 text-base">
                        <span>Total (Incl. GST)</span>
                        <span id="billTotal">
                            {{ format_price($grandTotal) }}
                        </span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Address Selection -->

        <section class="px-4 pt-4"style="padding-bottom:80px;">
            <div class="bg-white border-custom rounded-xl shadow-sm p-4">
                <label class="block text-md font-semibold text-gray-900 mb-2">Deliver To</label>
                <div class="relative w-full">
                    <button id="dropdownButton"
                        class="w-full border border-gray-300 rounded-lg px-3 py-3 flex justify-between items-center text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 transition">
                        <span id="selectedAddress" class="block max-w-[90%] text-sm text-left text-gray-800 break-all">

                            @php
                                // Fetch address using cart->address_id if exists
                                $selectedAddress = null;

                                if ($cart->address_id) {
                                    $selectedAddress = $userAddresses->where('id', $cart->address_id)->first();
                                }

                                // If cart does not have address, fallback to first user address
                                if (!$selectedAddress && $userAddresses->isNotEmpty()) {
                                    $selectedAddress = $userAddresses->first();
                                }
                            @endphp

                            {{-- Display formatted address --}}
                            {{ $selectedAddress ? formatUserAddress($selectedAddress) : 'Select Address' }}

                        </span>
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div id="dropdownMenu"
                        class="absolute hidden z-10 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">

                        <ul class="text-sm text-gray-700">
                            @foreach ($userAddresses as $address)
                                @php
                                    $details = $address->details;
                                    $typeName = ($details['type'] ?? 0) == 0 ? 'Home' : 'Office';
                                @endphp

                                <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer truncate address-item"
                                    data-id="{{ $address->id }}">
                                    {{ formatUserAddress($address) }}
                                </li>
                            @endforeach

                            <li id="addNewAddressBtn"
                                class="px-4 py-2 text-blue-600 font-medium hover:bg-blue-50 cursor-pointer flex items-center gap-2 border-t border-gray-100">
                                <i class="fi fi-tr-plus-small text-base"></i> Add New Address
                            </li>
                        </ul>

                    </div>
                </div>
            </div>
        </section>

        {{-- Error Message Container for Address --}}
        <div id="addressErrorContainer" class="max-w-md mx-auto md:max-w-2xl px-4 mt-2 hidden">
            <p id="addressErrorMessage" class="text-sm font-medium text-red-600 ps-2">
                <i class="fas fa-exclamation-circle mr-1"></i> Delivery address is required to proceed.
            </p>
        </div>



    </div>
    <form action="{{ route('panel.user.checkout.store') }}" method="POST">
        @csrf
        <input type="hidden"name="sso_token" value="{{ request()->get('sso_token') }}">
        @php
            $initialAddressId = $userAddresses->isNotEmpty() ? $userAddresses->first()->id : '';
        @endphp

        <input type="hidden" id="addressIdInput" name="address_id" value="{{ $initialAddressId }}">
        <!-- Bottom Bar -->
        <div class="fixed left-0 right-0 bottom-0 bg-white/95 border-t border-gray-200 p-4 backdrop-blur-sm">
            <div class="max-w-md mx-auto md:max-w-2xl flex items-center gap-4">
                <div>
                    <div class="text-sm text-gray-500">Total</div>
                    <div class="text-lg font-semibold text-gray-900" id="footerPrice">
                        {{ format_price($carts->sum('total')) }}
                    </div>
                </div>

                <button type="submit" id="proceedBtn"
                    class="ml-auto inline-flex items-center gap-2 bg-blue-500 text-white font-medium px-5 py-2 rounded-lg shadow-md hover:shadow-lg hover:opacity-95 transition">
                    <span>Proceed</span>
                    <i class="fi fi-bs-arrow-alt-right leading-4"></i>
                </button>
            </div>
        </div>
    </form>

    @include('panel.user.common.address.create')


@endsection
@push('script')
    {{-- This  script is used for Create Address  Page --}}
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places">
    </script>
    @include('panel.user.include.address.script')
    <!-- Dropdown Script -->
    <script>
        const btn = document.getElementById("dropdownButton");
        const menu = document.getElementById("dropdownMenu");
        const selected = document.getElementById("selectedAddress");
        const hiddenInput = document.getElementById("addressIdInput");
        const addAddressBtn = document.getElementById("addNewAddressBtn");

        // Toggle dropdown
        btn.addEventListener("click", () => menu.classList.toggle("hidden"));

        // Handle address click
        document.querySelectorAll(".address-item").forEach((item) => {
            item.addEventListener("click", () => {
                const addressId = item.getAttribute("data-id");

                // --- AJAX CALL ONLY ---
                fetch(`{{ route('panel.user.cart.updateAddress') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            addressId,
                            sso_token: "{{ request()->get('sso_token') }}"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            console.log("Address updated successfully");
                        } else {
                            alert("Something went wrong updating the cart.");
                        }
                    })
                    .catch(() => alert("Error updating cart."));

                // --- KEEP ORIGINAL BEHAVIOR ---
                hiddenInput.value = addressId;
                selected.textContent = item.textContent.trim();
                menu.classList.add("hidden");
            });
        });

        // Hide dropdown when clicking outside
        document.addEventListener("click", (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.add("hidden");
            }
        });

        // Open Add Address Modal
        addAddressBtn?.addEventListener("click", () => {
            document.getElementById("addAddressModal").classList.remove("hidden");
            menu.classList.add("hidden");
        });
    </script>



    {{-- Add Address Scripts --}}
    <script>
        const modal = document.getElementById("addAddressModal");
        const openBtn = document.getElementById("addNewAddressBtn");
        const closeBtn = document.getElementById("closeModalBtn");

        openBtn.addEventListener("click", () => {
            modal.classList.remove("hidden");
            document.getElementById("dropdownMenu").classList.add("hidden");
        });

        closeBtn.addEventListener("click", () => {
            modal.classList.add("hidden");
        });

        // Close modal on outside click
        modal.addEventListener("click", (e) => {
            if (e.target === modal) modal.classList.add("hidden");
        });
    </script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', () => {
            const incBtn = document.getElementById('incBtn');
            const decBtn = document.getElementById('decBtn');
            const qtyInput = document.getElementById('qty');
            const billItemTotal = document.getElementById('billItemTotal');
            const billGST = document.getElementById('billGST');
            const billTotal = document.getElementById('billTotal');
            const footerPrice = document.getElementById('footerPrice');

            const minQty = parseInt(qtyInput.min);

            function updateCart(qty) {
                fetch(`{{ route('panel.user.cart.updateQuantity') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            qty,
                            sso_token: "{{ request()->get('sso_token') }}"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            qtyInput.value = data.qty;
                            billItemTotal.innerHTML = data.item_total;
                            billGST.innerHTML = data.gst;
                            billTotal.innerHTML = data.total;
                            footerPrice.innerHTML =
                                `${data.footer_total}`;
                        } else {
                            alert('Something went wrong updating the cart.');
                        }
                    })
                    .catch(() => alert('Error updating cart.'));
            }

            incBtn.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value);
                qty++;
                updateCart(qty);
            });

            decBtn.addEventListener('click', () => {
                let qty = parseInt(qtyInput.value);
                if (qty > minQty) {
                    qty--;
                    updateCart(qty);
                }
            });

            qtyInput.addEventListener('change', () => {
                let qty = parseInt(qtyInput.value);
                if (isNaN(qty) || qty < minQty) qty = minQty;
                updateCart(qty);
            });
        });
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Select global bill elements
            const billItemTotal = document.getElementById('billItemTotal');
            const billCGST = document.getElementById('billCGST');
            const billSGST = document.getElementById('billSGST');
            const billTotal = document.getElementById('billTotal');
            const footerPrice = document.getElementById('footerPrice');

            // Function to send AJAX request
            function updateCart(cartId, qty, qtyInput) {
                fetch(`{{ route('panel.user.cart.updateQuantity') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            cart_id: cartId, // Send specific cart ID
                            qty: qty,
                            sso_token: "{{ request()->get('sso_token') }}"
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            // Update the specific input value
                            qtyInput.value = data.qty;

                            // Update Global Bill Totals
                            if (billItemTotal) billItemTotal.innerHTML = data.bill_item_total;
                            if (billCGST) billCGST.innerHTML = data.bill_cgst;
                            if (billSGST) billSGST.innerHTML = data.bill_sgst;
                            if (billTotal) billTotal.innerHTML = data.bill_total;
                            if (footerPrice) footerPrice.innerHTML = data.footer_total;
                        } else {
                            alert(data.error || 'Something went wrong updating the cart.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Error updating cart.');
                    });
            }

            // Handle Increment Buttons
            document.querySelectorAll('.incBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.getAttribute('data-id');
                    const qtyInput = document.getElementById(`qty_${cartId}`);

                    let qty = parseInt(qtyInput.value);
                    let max = parseInt(qtyInput.max) || 999;

                    if (qty < max) {
                        qty++;
                        updateCart(cartId, qty, qtyInput);
                    }
                });
            });

            // Handle Decrement Buttons
            document.querySelectorAll('.decBtn').forEach(button => {
                button.addEventListener('click', function() {
                    const cartId = this.getAttribute('data-id');
                    const qtyInput = document.getElementById(`qty_${cartId}`);
                    const minQty = parseInt(qtyInput.min) || 0;

                    let qty = parseInt(qtyInput.value);

                    if (qty > minQty) {
                        qty--;
                        updateCart(cartId, qty, qtyInput);
                    }
                });
            });

            // Handle Manual Input Change
            document.querySelectorAll('input[type="number"]').forEach(input => {
                input.addEventListener('change', function() {
                    // Ensure the ID matches our pattern qty_{id}
                    if (this.id.startsWith('qty_')) {
                        const cartId = this.id.split('_')[1];
                        const minQty = parseInt(this.min) || 0;
                        let qty = parseInt(this.value);

                        if (isNaN(qty) || qty < minQty) qty = minQty;

                        updateCart(cartId, qty, this);
                    }
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pincodeInput = document.querySelector('input[name="pincode"]');
            const saveBtn = document.querySelector('#addAddressModal button[type="submit"]');
            const pincodeMessage = document.createElement('p');

            pincodeMessage.classList.add('text-sm', 'mt-1', 'font-medium');
            pincodeInput.insertAdjacentElement('afterend', pincodeMessage);

            pincodeInput.addEventListener('input', function() {
                const pincode = this.value.trim();

                // Only trigger when length is 6 digits
                if (pincode.length === 6) {
                    fetch(`{{ route('panel.user.address.checkPincode') }}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                pincode,
                                sso_token: "{{ request()->get('sso_token') }}"
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.exists) {
                                pincodeMessage.textContent = 'Delivery available in this pin code';
                                pincodeMessage.classList.remove('text-red-600');
                                pincodeMessage.classList.add('text-green-600');
                                // saveBtn.disabled = false;
                            } else {
                                pincodeMessage.textContent =
                                    'Delivery is currently unavailable in your pin code, service is coming soon.';
                                pincodeMessage.classList.remove('text-green-600');
                                pincodeMessage.classList.add('text-red-600');
                                // saveBtn.disabled = true;
                            }
                        })
                        .catch(() => {
                            pincodeMessage.textContent = 'Error checking pin code.';
                            pincodeMessage.classList.remove('text-green-600');
                            pincodeMessage.classList.add('text-red-600');
                            // saveBtn.disabled = true;
                        });
                } else {
                    pincodeMessage.textContent = '';
                    saveBtn.disabled = false;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[action="{{ route('panel.user.checkout.store') }}"]');
            const addressInput = document.getElementById('addressIdInput');
            const addressErrorContainer = document.getElementById('addressErrorContainer');
            const proceedBtn = document.getElementById('proceedBtn');

            form.addEventListener('submit', (e) => {
                // Hide previous error
                addressErrorContainer.classList.add('hidden');

                // Check if address_id is missing
                if (!addressInput.value) {
                    e.preventDefault(); // Stop form submission
                    addressErrorContainer.classList.remove('hidden');

                    // Smooth scroll to the error message
                    addressErrorContainer.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });

                    // Add a small shake animation for better visibility
                    addressErrorContainer.classList.add('animate-shake');
                    setTimeout(() => {
                        addressErrorContainer.classList.remove('animate-shake');
                    }, 500);
                }
            });
        });
    </script>
    <script>
        let map, marker, autocomplete;

        function initMap() {
            // Default location
            const defaultLocation = {
                lat: 12.9716,
                lng: 77.5946
            };

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 14,
            });

            marker = new google.maps.Marker({
                map: map,
                position: defaultLocation,
                draggable: true,
            });

            // Drag marker → update lat/lng
            google.maps.event.addListener(marker, "dragend", function() {
                const pos = marker.getPosition();
                updateCoordinates(pos.lat(), pos.lng());
            });

            // Autocomplete
            autocomplete = new google.maps.places.Autocomplete(
                document.getElementById("google_address"), {
                    types: ["geocode"]
                }
            );

            autocomplete.addListener("place_changed", function() {
                const place = autocomplete.getPlace();
                if (!place.geometry) return;

                const location = place.geometry.location;

                map.setCenter(location);
                marker.setPosition(location);

                updateCoordinates(location.lat(), location.lng());

                // Fill address
                document.getElementById("address_1").value = place.formatted_address;

                // Extract pincode
                const pin = place.address_components.find(c => c.types.includes("postal_code"));
                document.getElementById("pincode").value = pin ? pin.long_name : "";
            });
        }

        function updateCoordinates(lat, lng) {
            document.getElementById("latitude").value = lat;
            document.getElementById("longitude").value = lng;
        }

        window.onload = initMap;
    </script>
@endpush
