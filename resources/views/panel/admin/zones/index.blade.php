@extends('layouts.main')
@section('title', @$label)
@section('content')

    @push('head')
        {{-- INITIALIZE SHIMMER & INIT LOAD --}}
        <script>
            window.onload = function() {
                $('#ajax-container').show();
                fetchData("{!! getCurrentUrlWithParams() !!}");

                // Load Map Section Data
                loadMapSection();
            };
        </script>
        {{-- END INITIALIZE SHIMMER & INIT LOAD --}}
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label ?? '' }}</h5>
                            <span> @lang('ui.list_of') {{ @$label ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('panel.admin.branches.index') }}">Branches</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ @$label ?? '' }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-md-6 pr-0" id="zoneListColumn">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ @$label ?? '' }}</h3>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('panel.admin.zones.create', ['branch_id' => request()->get('branch_id')]) }}"
                                class="btn btn-sm btn-outline-primary mr-2" title="Add New Branch"><i class="fa fa-plus"
                                    aria-hidden="true"></i> @lang('ui.add') </a>
                            <form action="{{ route('panel.admin.zones.bulk-action') }}" method="POST" id="bulkAction"
                                class="">
                                @csrf
                                <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                                <div>
                                    <x-button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical fa-lg pl-1"></i></x-button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                        <x-button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these Branches?"
                                            data-action="delete" data-callback="bulkDeleteCallback"><i class="ik ik-trash">
                                            </i>
                                            @lang('ui.bulk_delete')
                                        </x-button>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.zones.load')
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" id="mapContainer">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ik ik-loader ik-lg text-primary rotate"></i>
                        <p class="mt-2">Loading Drivers Location...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- open street map library --}}
    <script src="{{ asset('panel/admin/plugins/js/leaflet.js') }}"></script>

    <script>
        function getSelectedPincodeIds() {
            let ids = [];
            $('#FilterForm input[name="zone_pincode_id[]"]:checked').each(function() {
                ids.push($(this).val());
            });
            return ids;
        }

        function loadMapSection() {
            const pincode_ids = getSelectedPincodeIds();
            
            $('#mapContainer').html(
                '<div class="card"><div class="card-body text-center py-5"><i class="ik ik-loader ik-lg text-primary rotate"></i><p class="mt-2">Loading Filtered Drivers Location...</p></div></div>'
            );

            $.ajax({
                url: "{{ route('panel.admin.zones.map-section') }}", 
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    branch_id: '{{ request()->get('branch_id') }}',
                    pincode_ids: pincode_ids
                },
                success: function(response) {
                    $('#mapContainer').html(response.html);
                    initializeMapScripts(response.users); 
                },
                error: function(xhr, status, error) {
                    console.error("Error loading map section:", error);
                    $('#mapContainer').html(
                        '<div class="alert alert-danger">Could not load map data. Please check the console for details.</div>');
                }
            });
        }

        $(document).on('change', '#FilterForm input[name="zone_pincode_id[]"]', function() {
            loadMapSection();
        });
    </script>
    
    <script>
        function initializeMapScripts(zoneUsers) {
            
            // Check if Leaflet is available and if the map container exists
            if (typeof L === 'undefined' || !document.getElementById('map')) {
                console.error("Leaflet library not loaded or map container not found.");
                return;
            }

            var map;
            let bounds = L.latLngBounds();
            let markerMap = {};

            window.redirectWithStatus = function(status) { // Make global or pass as closure if needed outside
                console.log("Redirecting with status:", status);
            }

            map = L.map('map', {
                zoomControl: true
            }).setView([22.9734, 78.6569], 5);

            var streetMap = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            var satelliteMap = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '&copy; Esri'
                });

            // --- Active/Inactive Button Styles
            let activeBtnStyle = `
            px-3 py-1.5 text-sm font-medium border border-blue transition duration-150 ease-in-out
            bg-blue text-white shadow-md
            `;
            // Style for the unselected/inactive button
            let inactiveBtnStyle = `
            px-3 py-1.5 text-sm font-medium border border-blue transition duration-150 ease-in-out
            bg-white text-blue-600
            `;
            // --- Custom Map/Satellite Toggle Control ---
            var MapToggleControl = L.Control.extend({
                options: {
                    position: 'topleft'
                },
                onAdd: function() {
                    var container = L.DomUtil.create('div',
                        'leaflet-control custom-map-toggle d-flex rounded-lg overflow-hidden');
                    // The Map button is active by default
                    container.innerHTML = `
            <button id="mapViewBtn"   class="${activeBtnStyle}">
                Map
            </button>
            <button id="satelliteViewBtn"  class="${inactiveBtnStyle}">
                Satellite
            </button>
            `;
                    return container;
                }
            });
            map.addControl(new MapToggleControl());

            L.DomEvent.disableClickPropagation(document.querySelector('.custom-map-toggle'));

            // --- Count Statuses and Custom Active/Inactive Toggle Control ---
            let activeCount = zoneUsers.filter(user => parseInt(user.status) === 1).length;
            let inactiveCount = zoneUsers.filter(user => parseInt(user.status) === 0).length;

            var ActiveInactiveControl = L.Control.extend({
                options: {
                    position: 'bottomleft'
                },
                onAdd: function() {
                    var container = L.DomUtil.create('div',
                        'leaflet-control active-inactive-toggle p-0 rounded-0 lh-xs d-flex gap-0');
                    container.innerHTML = `
                    <div class="status-item active-vehicle text-center border" onclick="redirectWithStatus('1')" style="cursor: pointer;">
                        <span id="activeCount" class="fs-17 fw-700 text-green">${activeCount}</span>
                        <p class="mb-0 fs-13">Active</p>
                    </div>
                    <div class="status-item inactive-vehicle text-center border" onclick="redirectWithStatus('0')" style="cursor: pointer;">
                        <span id="inactiveCount" class="fs-17 fw-700 text-red">${inactiveCount}</span>
                        <p class="mb-0 fs-13">Offline</p>
                    </div>
                `;
                    return container;
                }
            });
            map.addControl(new ActiveInactiveControl());

            // --- Truck Icon Function ---
            function getTruckIcon(status) {
                const iconFile = status === 1 ? 'driver-marker.png' : 'offline.png';
                return L.icon({
                    iconUrl: `/panel/admin/marker/${iconFile}`, // Make sure this path is correct!
                    iconSize: [45, 50],
                    iconAnchor: [22, 50],
                    popupAnchor: [0, -45]
                });
            }

            zoneUsers.forEach(user => {
                const coords = user.geo_coordinates;

                try {
                    // Added null/undefined check for geo_coordinates object itself
                    if (!coords || typeof coords !== 'object' || !coords.latitude || !coords.longitude) {
                        console.warn("Skipping user due to missing or invalid coordinates object:", user);
                        return;
                    }
                    const lat = parseFloat(coords.latitude);
                    const lng = parseFloat(coords.longitude);
                    const status = parseInt(user.status);

                    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) {
                        console.warn(
                            `Skipping user ${user.id} due to invalid GPS coordinates (Out of Range):`,
                            coords);
                        return;
                    }
                    const latLng = L.latLng(lat, lng);
                    const icon = getTruckIcon(status);

                    // Create Marker
                    const marker = L.marker(latLng, {
                        icon: icon
                    });
                    const vehicle = user.vehicle_details && typeof user.vehicle_details === 'object'
                        ? user.vehicle_details
                        : user.vehicle_details
                        ? JSON.parse(user.vehicle_details)
                        : null;

                    const popupContent = `
                        <div class="" style="min-width: 220px; border-radius: 12px;">
                            <a href="/admin/users/edit/${user.id}" class="d-flex align-items-center mb-2">
                                <img src="${user.avatar}" 
                                    alt="${user.first_name} avatar"
                                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 12px;">
                                <div>
                                    <h6 class="mb-0 text-primary fw-bold">${user.first_name} ${user.last_name}</h6>
                                    <div 
                                    class="text-dark fw-semibold text-decoration-none hover-underline">
                                        #DVR${user.id}
                                    </div>
                                </div>
                            </a>
                            ${
                                vehicle
                                    ? `
                                    <div class="border-top pt-2">
                                        <h6 class="text-secondary fw-semibold mb-1">Vehicle Details</h6>
                                        <div class="small">
                                            <div><span class="fw-bold">Name:</span> ${vehicle.vehicle_name || '-'}</div>
                                            <div><span class="fw-bold">Type:</span> ${vehicle.vehicle_type || '-'}</div>
                                            <div><span class="fw-bold">Number:</span> ${vehicle.vehicle_number || '-'}</div>
                                        </div>
                                    </div>
                                    `
                                    : ''
                            }
                        </div>
                    `;

                    // Bind tooltip for hover effect
                    marker.bindPopup(popupContent, {
                        maxWidth: 300,
                        className: 'leaflet-custom-popup'
                    });

                    // Add to map
                    marker.addTo(map);

                    // Extend map bounds to include this marker
                    bounds.extend(latLng);

                } catch (e) {
                    console.error("Error accessing user coordinates:", user, e);
                }
            });

            map.invalidateSize();

            if (zoneUsers.length > 0 && bounds.isValid()) {
                map.fitBounds(bounds, {
                    padding: [50, 50]
                });
            } else {
                map.setView([22.9734, 78.6569], 5);
            }

            // Map Toggle Handlers (using delegation since elements are dynamically loaded)
            $(document).on('click', '#mapContainer #mapViewBtn', function() {
                if (!map.hasLayer(streetMap)) {
                    map.removeLayer(satelliteMap);
                    streetMap.addTo(map);
                }
                $(this).attr('class', activeBtnStyle.trim());
                $('#satelliteViewBtn').attr('class', inactiveBtnStyle.trim());
            });

            $(document).on('click', '#mapContainer #satelliteViewBtn', function() {
                if (!map.hasLayer(satelliteMap)) {
                    map.removeLayer(streetMap);
                    satelliteMap.addTo(map);
                }
                $(this).attr('class', activeBtnStyle.trim());
                $('#mapViewBtn').attr('class', inactiveBtnStyle.trim());
            });

            // Fullscreen Handlers (using delegation)
            $(document).on('click', '#mapContainer #enterFullscreen', function() {
                $('#zoneListColumn').addClass('d-none');
                $('#mapContainer').removeClass('col-md-6').addClass('col-md-12');
                $('#map').addClass('fullscreen');
                $(this).hide();
                $('#exitFullscreen').show();
                map.invalidateSize();
            });

            $(document).on('click', '#mapContainer #exitFullscreen', function() {
                $('#zoneListColumn').removeClass('d-none');
                $('#mapContainer').removeClass('col-md-12').addClass('col-md-6');
                $('#map').removeClass('fullscreen');
                $(this).hide();
                $('#enterFullscreen').show();
                map.invalidateSize();
            });
        }
    </script>
    <script>
        $('.select2').select2();
    </script>
    <script>
        $('#reset').click(function() {
            var currentUrl = '{{ url()->full() }}';
            fetchData(currentUrl);
            window.history.pushState("", "", currentUrl);
            $('#TableForm')[0].reset();
            $('.select2').val('').trigger('change');
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>

    @include('panel.admin.include.bulk_script.index')
@endpush
