@extends('layouts.main')
@section('title', __('My Address'))
@section('content')

    <div class="">

        <div class="p-4 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">All Address</h2>
                <p class="text-sm text-gray-500">View and manage all your address.</p>
            </div>
        </div>

        <div class="px-4 pt-0 space-y-3" id="ticketListContainer">
            {{-- Initial tickets loaded via Blade --}}
            @include('panel.user.address.partials.address-list')
        </div>

        {{-- <div class="text-center mt-5 mb-24" id="loadMoreWrapper" >
            <button id="loadMoreBtn"
                class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm rounded-lg font-semibold shadow-sm hover:bg-blue-700 hover:text-white active:scale-[0.98] transition">
                Load More
            </button>
        </div> --}}
    </div>

    <div class="fixed bottom-5 right-3">
        <div id="addNewAddressBtn"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2H9v5a1 1 0 1 1-2 0V9H2a1 1 0 1 1 0-2h5V2a1 1 0 0 1 1-1z" />
            </svg>

            Add Address
        </div>
    </div>

 @include('panel.user.common.address.create')


@endsection



@push('script')
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places">
    </script>
    @include('panel.user.include.address.script')
    <script>
        let currentPage = 1;
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadMoreWrapper = document.getElementById('loadMoreWrapper');
        const ticketListContainer = document.getElementById('ticketListContainer');

        function toggleAccordion(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('svg');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        // Initialize accordion listeners for initially loaded tickets
        document.querySelectorAll('.accordion-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleAccordion(btn));
        });

        function fetchTickets(page) {
            // Set loading state
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';

            fetch(`{{ route('panel.user.support-tickets.index') }}?page=${page}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // Append new HTML
                    ticketListContainer.insertAdjacentHTML("beforeend", data.html);

                    // Update button visibility
                    if (data.hasMore) {
                        loadMoreWrapper.style.display = "block";
                    } else {
                        loadMoreWrapper.style.display = "none";
                    }

                    // Re-attach accordion listeners to the newly loaded content
                    document.querySelectorAll('#ticketListContainer .accordion-btn:not([data-listener-attached])')
                        .forEach(btn => {
                            btn.addEventListener('click', () => toggleAccordion(btn));
                            btn.setAttribute('data-listener-attached', 'true'); // Prevent attaching multiple times
                        });

                })
                .catch(err => console.error("Error loading tickets:", err))
                .finally(() => {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Load More';
                });
        }

        // Attach event listener for Load More button
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentPage++;
                fetchTickets(currentPage);
            });
        }
    </script>

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
                                pincodeMessage.textContent = 'Delivery is currently unavailable in your pin code, service is coming soon.';
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
