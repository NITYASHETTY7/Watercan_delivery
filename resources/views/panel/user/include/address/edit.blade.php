<script>
    const DEFAULT_COUNTRY_ID = 101; 
    const DEFAULT_STATE_ID = 4026;   

    function initializeSelect2(selector) {
        if ($(selector).data('select2')) {
            $(selector).select2('destroy');
        }
        
        $(selector).select2({
            width: '100%', // Crucial for maintaining the width
        });

        $(selector).addClass('w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-200 focus:outline-none');
    }
    function getStates(countryId, defaultStateId = null, defaultCityId = null) {
        if (!countryId) return;

        $.ajax({
            url: "{{ route('world.get-states') }}",
            method: 'GET',
            data: { country_id: countryId },
            success: function(res) {
                $('#state').html(res);
                
                // Set default selected state if ID is provided
                if (defaultStateId) {
                    // IMPORTANT: Use .val() and .trigger('change') for Select2 to update UI
                    $('#state').val(defaultStateId).trigger('change'); 
                }

                initializeSelect2('#state');
                
                // If a default state was set, trigger city load with the default city ID
                if (defaultStateId && defaultCityId) {
                    // Check if the state dropdown actually loaded the option before calling getCities
                    if($('#state').val() == defaultStateId) {
                        getCities(defaultStateId, defaultCityId); 
                    } else {
                        // Fallback: If state couldn't be set, reset cities
                        $('#city').html('<option value="">Select City</option>');
                        initializeSelect2('#city');
                    }
                } else {
                    $('#city').html('<option value="">Select City</option>'); // reset cities
                    initializeSelect2('#city');
                }
            }
        });
    }
    function getCities(stateId, defaultCityId = null) {
        if (!stateId) return;
        $.ajax({
            url: "{{ route('world.get-cities') }}",
            method: 'GET',
            data: { state_id: stateId },
            success: function(res) {
                $('#city').html(res);

                // Set default selected city if ID is provided
                if (defaultCityId) {
                    $('#city').val(defaultCityId).trigger('change'); 
                }

                initializeSelect2('#city');
            }
        });
    }

    // --- Event Listeners ---

    // When country changes (User interaction)
    $('#country').on('change', function() {
        const countryId = $(this).val();
        // User interaction: only pass country ID, state/city should be reset
        getStates(countryId); 
    });

    // When state changes (User interaction or programmatic change from getStates)
    $('#state').on('change', function() {
        const stateId = $(this).val();
        if(stateId) {
            // User interaction: only pass state ID, city should be reset
            getCities(stateId); 
        } else {
            $('#city').html('<option value="">Select City</option>');
            initializeSelect2('#city');
        }
    });

    $(document).ready(function() {
        if ($('#country').val() === '') {
            $('#country').val(DEFAULT_COUNTRY_ID); 
            initializeSelect2('#country');
            getStates(DEFAULT_COUNTRY_ID, DEFAULT_STATE_ID);
        }
        
        initializeSelect2('#country');
    });
</script>