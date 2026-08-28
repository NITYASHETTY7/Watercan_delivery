@extends('layouts.main')
@section('title', __('Edit Address'))
@section('content')

    @push('head')
        <style>
            .priority-option input:checked+span {
                font-weight: 600;
            }
        </style>
    @endpush

    <div class="">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <header class="pt-4 px-5">
                <h1 class="text-xl font-semibold text-gray-900">Update Your Address</h1>
                <p class="text-sm text-gray-500">
                    Update your address details below.
                </p>
            </header>

            <div class="p-4 space-y-5">

                <form id="editAddressForm" action="{{ route('panel.user.address.update', $userAddress->id) }}" method="POST"
                    class="space-y-3">
                    @csrf

                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Type</label>
                        <select name="type" class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            required>
                            <option value="0" {{ ($userAddress->details['type'] ?? 0) == 0 ? 'selected' : '' }}>Home
                            </option>
                            <option value="1" {{ ($userAddress->details['type'] ?? 0) == 1 ? 'selected' : '' }}>Office
                            </option>
                        </select>
                    </div>

                    <!-- Search Address -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Search Address</label>
                        <input id="mapSearchInput" type="text"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            placeholder="Search location on map" />
                    </div>

                    <!-- Google Map -->
                    <div id="map" class="w-full h-64 rounded-lg border"></div>

                    <!-- Address Line 1 -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Line 1</label>
                        <input type="text" name="address_1" id="address_1"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            value="{{ $userAddress->details['address_1'] ?? '' }}" required />
                    </div>

                    <!-- Address Line 2 -->
                    <div>
                        <label class="text-sm font-medium text-gray-700">Address Line 2</label>
                        <input type="text" name="address_2" id="address_2"
                            class="w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                            value="{{ $userAddress->details['address_2'] ?? '' }}" />
                    </div>

                    <!-- Pincode -->
                    <div>
                        <label class="text-sm font-medium text-gray-700 mb-2">Pincode</label>
                        <input type="text" name="pincode" maxlength="6" id="pincodeInput"
                            value="{{ $userAddress->details['pincode'] ?? '' }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm" required />
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="latitude" id="latitude"
                        value="{{ $userAddress->details['latitude'] ?? '' }}">
                    <input type="hidden" name="longitude" id="longitude"
                        value="{{ $userAddress->details['longitude'] ?? '' }}">

                    <div id="pincodeMessageContainer" class="h-10"></div>

                    <div class="pt-3">
                        <button type="submit" id="updateAddressBtn"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-lg">
                            Update Address
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection

@push('script')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places">
    </script>
    {{-- This  script is used for Edit Address  Page --}}
    @include('panel.user.include.address.edit')

    <script>
        // --- Cascading Dropdown Logic ---
        $(document).ready(function() {
            // Retrieve IDs directly from the JSON details attribute
            let countryId = "{{ $userAddress->details['country_id'] ?? '' }}";
            let stateId = "{{ $userAddress->details['state_id'] ?? '' }}";
            let cityId = "{{ $userAddress->details['city_id'] ?? '' }}";

            // 1. Select Country and trigger change to initialize Select2
            $('#country').val(countryId).trigger('change');

            // 2. Load States, passing the default State ID and City ID to trigger the cascade
            getStates(countryId, stateId, cityId);
        });

        // --- Pincode Availability and Format Check Logic ---
        document.addEventListener('DOMContentLoaded', () => {
            const pincodeInput = document.getElementById('pincodeInput');
            const updateAddressBtn = document.getElementById('updateAddressBtn');
            const messageContainer = document.getElementById('pincodeMessageContainer');

            // Create the message element
            const pincodeMessage = document.createElement('p');
            pincodeMessage.classList.add('text-sm', 'font-medium');
            messageContainer.appendChild(pincodeMessage);


            // Regex for Indian Pincode: 6 digits, must not start with 0
            const PincodeRegex = /^[1-9][0-9]{5}$/;

            function checkPincodeAvailability(pincode) {
                // 1. First, check Pincode format validation (Client-Side)
                if (!PincodeRegex.test(pincode)) {
                    pincodeMessage.textContent = 'Pincode must be 6 digits and cannot start with 0.';
                    pincodeMessage.classList.remove('text-green-600');
                    pincodeMessage.classList.add('text-red-600');
                    updateAddressBtn.disabled = true;
                    return; // Stop execution if format is invalid
                }

                // 2. Pincode Format is valid, now check availability (AJAX)

                // Initial state while fetching
                pincodeMessage.textContent = 'Checking availability...';
                pincodeMessage.classList.remove('text-red-600', 'text-green-600');
                updateAddressBtn.disabled = true;

                fetch(`{{ route('panel.user.address.checkPincode') }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            pincode
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.exists) {
                            pincodeMessage.textContent = 'Delivery available in this pin code.';
                            pincodeMessage.classList.remove('text-red-600');
                            pincodeMessage.classList.add('text-green-600');
                            // updateAddressBtn.disabled = false;
                        } else {
                            pincodeMessage.textContent = 'Delivery is currently unavailable in your pin code, service is coming soon.';
                            pincodeMessage.classList.remove('text-green-600');
                            pincodeMessage.classList.add('text-red-600');
                            // updateAddressBtn.disabled = true;
                        }
                    })
                    .catch(() => {
                        pincodeMessage.textContent = 'Error checking pin code. Try again.';
                        pincodeMessage.classList.remove('text-green-600');
                        pincodeMessage.classList.add('text-red-600');
                        // updateAddressBtn.disabled = true;
                    });
            }

            // 1. Initial check when the page loads (for the pre-filled pincode)
            const initialPincode = pincodeInput.value.trim();
            if (initialPincode.length > 0) { // Check if there is any value to validate/check
                checkPincodeAvailability(initialPincode);
            } else {
                // If the field is empty, disable the button to enforce filling it
                updateAddressBtn.disabled = true;
                pincodeMessage.textContent = 'Please enter a 6-digit Pincode.';
                pincodeMessage.classList.add('text-red-600');
            }


            // 2. Continuous check on user input
            pincodeInput.addEventListener('input', function() {
                checkPincodeAvailability(this.value.trim());
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
                document.getElementById("mapSearchInput"), {
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
