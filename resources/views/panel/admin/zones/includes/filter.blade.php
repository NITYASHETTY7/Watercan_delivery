<style>
    .select2-container {
        width: 100% !important;
    }
    .filter-scroll-area {
        max-height: calc(100vh - 120px); /* Adjust height based on header/footer size */
        overflow-y: auto;
        padding-bottom: 20px;
    }
    .pincode-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); /* Responsive 3-4 column grid */
        gap: 5px 10px;
    }
    .zone-card {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        margin-bottom: 15px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05); /* Light shadow */
        background-color: #fff;
    }
</style>
<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between align-items-center bg-light p-3 border-bottom">
            <h5 class="mt-0 mb-0 fw-bold text-dark"> <i class="ik ik-filter mr-1 text-primary"></i> @lang('ui.filter') </h5>
            <x-button type="button" class="close off-canvas text-dark" data-type="close">
                <span aria-hidden="true"><i class="ik ik-x fs-20"></i></span>
            </x-button>
        </div>

        <div class="card-body p-0" id="FilterForm">
            <div class="filter-scroll-area px-3 pt-3">
                @php
                    $selected_pincode_ids = $selected_pincode_ids ?? request()->get('zone_pincode_id') ?? []; 
                @endphp
                
                @if (@$branchZones->count() > 0)
                    @foreach (@$branchZones as $branchZone)
                        <div class="zone-card p-3">
                            <h6 class="mb-3 border-bottom pb-2 fw-bold text-secondary d-flex justify-content-between align-items-center"> 
                                <span>
                                    <i class="ik ik-globe mr-1 text-secondary"></i>
                                    {{ $branchZone->name }}
                                </span>
                                <small class="text-black fw-700 fw-normal"> ({{ $branchZone->getPrefix() }})</small>
                            </h6>
                            
                            @if ($branchZone->zonePincodes->count() > 0)
                                
                                <div class="pincode-grid">
                                    @foreach ($branchZone->zonePincodes as $zonePincode)
                                        <div class="form-check m-0">
                                            <input class="form-check-input zone-pincode-checkbox" type="checkbox" name="zone_pincode_id[]" 
                                                value="{{ $zonePincode->id }}" 
                                                id="pincode{{ $zonePincode->id }}"
                                                data-zone-id="{{ $branchZone->id }}"
                                                {{ in_array($zonePincode->id, $selected_pincode_ids) ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="pincode{{ $zonePincode->id }}">
                                                {{ $zonePincode->pincode }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted small text-center mb-0">No Pincodes available.</p>
                            @endif
                        </div>
                    @endforeach
                @else
                    <p class="text-center text-muted mt-5">
                        <i class="ik ik-alert-octagon fs-20 mr-1"></i> No Zones Found!
                    </p>
                @endif
            </div>
        </div>
        
        </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.check-all-zone').forEach(function(selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const zoneId = this.getAttribute('data-zone-id');
                const isChecked = this.checked;
                document.querySelectorAll(`.zone-pincode-checkbox[data-zone-id="${zoneId}"]`).forEach(function(pincodeCheckbox) {
                    pincodeCheckbox.checked = isChecked;
                });
            });
        });

        document.querySelectorAll('.zone-pincode-checkbox').forEach(function(pincodeCheckbox) {
            pincodeCheckbox.addEventListener('change', function() {
                const zoneId = this.getAttribute('data-zone-id');
                const totalCheckboxes = document.querySelectorAll(`.zone-pincode-checkbox[data-zone-id="${zoneId}"]`).length;
                const checkedCheckboxes = document.querySelectorAll(`.zone-pincode-checkbox[data-zone-id="${zoneId}"]:checked`).length;
                
                const selectAllCheckbox = document.querySelector(`#selectAllZone_${zoneId}`);
                if (selectAllCheckbox) {
                    // Check 'Select All' if all individual checkboxes are checked
                    selectAllCheckbox.checked = (totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
                }
            });
        });
        
        document.querySelectorAll('.check-all-zone').forEach(function(selectAllCheckbox) {
            selectAllCheckbox.dispatchEvent(new Event('change')); // Initial check updates the select all status based on pre-selected items
        });
    });
</script>