
<form class="forms-sample ajaxForm" method="POST"
    action="{{ route('panel.admin.users.update', secureToken($user->id)) }}">
    <div class="row">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @csrf

        <x-input name="id" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
            value="{{ $user->id ?? '' }}" />
        <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
            value="update" />

        <div class="col-md-7 mx-auto pr-0">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.personal_details') </h3>
                </div>
                <div class="card-body negative-margin">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="first_name" validation="common_name" tooltip="add_user_first_name" />

                                <x-input name="first_name"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.first_name') }}" type="text"
                                    tooltip="add_user_first_name" regex="name" validation="common_name"
                                    value="{{ @$user->first_name }}" />
                                <x-message name="first_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="last_name" validation="common_name" tooltip="add_user_last_name" />
                                <x-input name="last_name" placeholder="{{ __('ui.enter') . ' ' . __('ui.last_name') }}"
                                    type="text" tooltip="add_user_last_name" regex="name" validation="common_name"
                                    value="{{ $user->last_name }}" />
                                <x-message name="last_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="email" validation="empty" tooltip="add_user_email" />
                                <x-input name="email" placeholder="{{ __('ui.enter') . ' ' . __('ui.email') }}"
                                    type="email" tooltip="add_user_email" regex="email" validation="empty"
                                    value="{{ $user->email }}" />
                                <x-message name="email" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="phone" validation="required" tooltip="add_user_phone" />

                                <div class="input-group">
                                    <x-input type="hidden" id="countryCodeInput" name="country_code" :value="''"
                                        validation="empty" />

                                    <x-input type="phone" class="form-control" id="phone" name="phone"
                                        :value="$user->fullPhone()" validation="phone_number" />
                                    <x-message name="phone" :message="@$message" validation="required" />

                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="dob" validation="required" tooltip="add_user_dob" />
                                <x-date regex="dob" max="{{ now()->format('Y-m-d') }}" validation="required"
                                    type="date" value="{{ $user->dob }}" name="dob" id="dob"
                                    placeholder="Select your date" />
                                <x-message name="dob" :message="@$message" validation="empty" />
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                @php
                                    $gender_arr = [
                                        ['value' => 'male', 'name' => 'male'],
                                        ['value' => 'female', 'name' => 'female'],
                                    ];
                                    $selectedOption = old('gender', $user->gender ?? 'male');
                                @endphp
                                <div class="form-group">
                                    <x-label name="gender" validation="common_name" tooltip="add_user_gender" />
                                    <x-radio name="gender" type="radio" valueName="id" :value="$selectedOption"
                                        :arr="@$gender_arr" :selected="$selectedOption" validation="empty" />
                                    <x-message name="gender" :message="@$message" validation="common_name" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">

                            <div class="form-group">
                                <x-label name="assign_role" validation="required" tooltip="add_user_role" />
                                <x-select name="role" value="{{ $user_role->id }}" label="Role"
                                    optionName="display_name" class="select2" :arr="@$roles"
                                    validation="required" id="roleId" valueName="id" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="status" tooltip="status" />
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="userStatusSwitch"
                                        name="status" value="1" {{ @$user->status == 1 ? 'checked' : '' }}>
                                    <label class="form-check-label" for="userStatusSwitch">
                                        {{ @$user->status == 1 ? 'Active' : 'Inactive' }}
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            @if (UserRole($user->id)['name'] == 'driver')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.assign') @lang('ui.zone') @lang('ui.pincode') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="select_pincode" validation="empty" />
                                    <select name="pincodes[]" id="pincode" class="form-control select2" multiple>
                                        <option value="" disabled>Select Pincode</option>
                                        @foreach ($pincodes as $zonePincode)
                                            <option value="{{ $zonePincode->id }}"
                                                @if (in_array($zonePincode->id, @$user->zonePincodeUsers->pluck('zone_pincode_id')->toArray())) selected @endif>
                                                {{ $zonePincode->pincode }} | {{ @$zonePincode->zone->name ?? '' }} |
                                                {{ @$zonePincode->branch->name ?? '' }} |
                                                {{ $zonePincode->getPrefix() }}</option>
                                        @endforeach
                                    </select>
                                    <x-message name="pincode" :message="@$message" validation="empty" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-5">
            @if (UserRole($user->id)['name'] == 'driver')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.vehicle_details') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_name" validation="empty" />

                                    <x-input name="vehicle_name"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_name') }}"
                                        type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_details['vehicle_name'] ?? '' }}" />
                                    <x-message name="vehicle_name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_number" validation="empty" />

                                    <x-input name="vehicle_number"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_number') }}"
                                        type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_details['vehicle_number'] ?? '' }}" />
                                    <x-message name="vehicle_number" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_type" validation="empty" />

                                    <x-input name="vehicle_type"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_type') }}"
                                        type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_details['vehicle_type'] ?? '' }}" />
                                    <x-message name="vehicle_type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (UserRole($user->id)['name'] == 'user')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.account_type_details') </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                               <div class="form-group">
                                    <div class="d-flex flex-wrap gap-3"> {{-- Wrapper to handle spacing between radio groups --}}
                                        @foreach (App\Models\User::ACCOUNT_TYPES as $accountKey => $accountType)
                                            <label class="d-inline-flex align-items-center mr-3 cursor-pointer" style="font-weight: normal;">
                                                <input type="radio" 
                                                    name="account_type" 
                                                    class="accountType mr-2"
                                                    @if ($user->account_type == $accountKey) checked @endif
                                                    value="{{ $accountKey }}">
                                                <span class="ml-0">{{ @$accountType['label'] }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <x-message name="account_type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 accountTypeLabels @if ($user->account_type == App\Models\User::ACCOUNT_TYPE_INDIVIDUAL) d-none @endif">
                                <div class="form-group">
                                    <x-label name="company_name" validation="empty" />
                                    <x-input name="company_name"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.company_name') }}"
                                        type="text" regex="name" validation="empty"
                                        value="{{ @$user->business_payload['company_name'] ?? '' }}" />
                                    <x-message name="company_name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 accountTypeLabels @if ($user->account_type == App\Models\User::ACCOUNT_TYPE_INDIVIDUAL) d-none @endif">
                                <div class="form-group">
                                    <x-label name="gst_number" validation="empty" />
                                    <x-input name="gst_number"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.gst_number') }}" type="text"
                                        regex="name" validation="empty"
                                        value="{{ @$user->business_payload['gst_number'] ?? '' }}" />
                                    <x-message name="gst_number" :message="@$message" validation="empty" value="{{ @$user->business_payload['gst_number'] ?? '' }}"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <x-button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('ui.save_update')
    </x-button>
</form>
@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('select.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}


    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            let route = $(this).attr('action');
            let method = $(this).attr('method');
            let data = new FormData(this);
            let response = postData(method, route, 'json', data, 'handleUserCallback', null, 1, null, 'not-reload');
        })

        function handleUserCallback(response) {
            if (response != 'undefined' && response.status == 'success') {
                var redirectUrl = "{{ url('admin/users') }}" + '?role=' + response.role;
                window.location.href = redirectUrl;
            }
        }
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                utilsScript: "{{ asset($master_root_directory . 'plugins/country-code/utils.js') }}",
            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                countryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countryChange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countryChange');
                input.dispatchEvent(event);
            }, 300);
        });
    </script>

    <script>
        $(document).on('change', '.accountType', function() {
            var val = $(this).val();

            if (val == 2) {
                // Show extra fields
                $('.accountTypeLabels').removeClass('d-none');

                // Make inputs required
                $('.accountTypeLabels').find('input').attr('required', true);

                // Add red asterisk to label if not already present
                $('.accountTypeLabels').find('label').each(function() {
                    if (!$(this).find('.text-danger').length) {
                        $(this).append('<span class="text-danger"> *</span>');
                    }
                });

            } else {
                // Hide extra fields
                $('.accountTypeLabels').addClass('d-none');

                // Remove required attribute
                $('.accountTypeLabels').find('input').removeAttr('required');

                // Remove red asterisk from label
                $('.accountTypeLabels').find('.text-danger').remove();
            }
        });
    </script>

    {{-- END COUNTRYCODE SELECTOR INIT --}}
@endpush
