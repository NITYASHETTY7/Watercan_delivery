<div class="card">
    <div class="card-header justify-content-between">
        <h6 class="mb-0">Drivers Location</h6>
        <div>
            {{-- Fullscreen Toggle Button (Enter) --}}
            <button id="enterFullscreen" class="btn btn-outline-secondary btn-icon rounded-circle mr-1"
                title="Full Screen">
                <i class="ik ik-maximize-2 ik-lg"></i>
            </button>
            {{-- Fullscreen Toggle Button (Exit) --}}
            <button id="exitFullscreen" class="btn btn-outline-secondary btn-icon rounded-circle mr-1"
                style="display:none;" title="Exit Full Screen">
                <i class="ik ik-minimize-2 ik-lg"></i>
            </button>
            {{-- Filter Button (Conditional) --}}
            @if (getSetting('toggling_user_management_table_filter', @$master_setting))
                <x-button type="button"
                    class="off-canvas btn btn-outline-secondary btn-icon rounded-circle"
                    title="Filter Options">
                    <i class="ik ik-filter ik-lg"></i>
                </x-button>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="flex-grow-1 pe-2">
            <div class="border rounded-1">
                <div id="map" class="rounded-1 map-height" style="height: 79vh; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

@include('panel.admin.zones.includes.filter', [
    'branchZones' => $branchZones,
    'selected_pincode_ids' => $selected_pincode_ids 
])