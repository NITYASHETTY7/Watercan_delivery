<script>
    // Placeholder IDs (REPLACE with actual IDs from your database)
    const DEFAULT_COUNTRY_ID = 101; // Assuming India ID
    const DEFAULT_STATE_ID = 4026;   // Assuming Karnataka ID
    const DEFAULT_CITY_ID = 57933;   // Assuming Bengaluru ID

    // Function to initialize/re-initialize Select2 on a dropdown
    function initializeSelect2(selector) {
        // Destroy existing Select2 instance if it exists to avoid conflicts
        if ($(selector).data('select2')) {
            $(selector).select2('destroy');
        }
        
        $(selector).select2({
            width: '100%', // Crucial for maintaining the width
        });

        // Add back any class/style attributes that might be removed by select2 destruction
        $(selector).addClass('w-full mt-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-blue-200 focus:outline-none');
    }

    function getStates(countryId, defaultStateId = null) {
        $.ajax({
            url: "{{ route('world.get-states') }}",
            method: 'GET',
            data: { country_id: countryId },
            success: function(res) {
                // 'res' should contain <option> tags for states
                $('#state').html(res);
                
                // Set default selected state if ID is provided
                if (defaultStateId) {
                    $('#state').val(defaultStateId).trigger('change'); 
                }

                initializeSelect2('#state'); // Re-initialize Select2 to fix UI
                
                // If a default state was set, trigger city load
                if (defaultStateId) {
                    // Check if the state dropdown actually loaded the option before calling getCities
                    if($('#state').val() == defaultStateId) {
                        getCities(defaultStateId, DEFAULT_CITY_ID);
                    } else {
                        // If state load failed (e.g., option wasn't returned), reset cities
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
                // 'res' should contain <option> tags for cities
                $('#city').html(res);

                // Set default selected city if ID is provided
                if (defaultCityId) {
                    $('#city').val(defaultCityId); 
                }

                initializeSelect2('#city'); // Re-initialize Select2 to fix UI
            }
        });
    }

    // --- Event Listeners ---

    // When country changes
    $('#country').on('change', function() {
        const countryId = $(this).val();
        getStates(countryId); // Pass only countryId, no default state/city after user interaction
    });

    // When state changes
    $('#state').on('change', function() {
        const stateId = $(this).val();
        if(stateId) {
            getCities(stateId); // Pass only stateId, no default city after user interaction
        } else {
            $('#city').html('<option value="">Select City</option>');
            initializeSelect2('#city');
        }
    });

    // --- Initial Load ---

    $(document).ready(function() {
        // 1. Set default Country (India) on the plain select element
        $('#country').val(DEFAULT_COUNTRY_ID); 
        initializeSelect2('#country');
        getStates(DEFAULT_COUNTRY_ID, DEFAULT_STATE_ID);
    });
</script>