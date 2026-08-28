
<form class="ajaxForm" method="POST" action="{{ route('panel.admin.users.store') }}" autocomplete="off">
    @csrf
    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <x-input name="role" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
        value="{{ request()->get('role') }}" />
    <div class="row">
        <div class="col-md-7 mx-auto pr-0">
            @include('panel.admin.include.message')
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.personal_info') </h3>
                </div>
                <div class="card-body negative-margin">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="first_name" validation="common_name" tooltip="add_user_first_name" />
                                <x-input name="first_name" placeholder="{{ __('ui.enter') . ' ' . __('ui.first_name') }}" type="text" tooltip="add_user_first_name" regex="name" validation="common_name" value="{{ old('first_name') }}" />
                                <x-message name="first_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="last_name" validation="common_name" tooltip="add_user_last_name" />
                                <x-input name="last_name" placeholder="{{ __('ui.enter') . ' ' . __('ui.last_name') }}" type="text" tooltip="add_user_last_name" regex="name" validation="common_name" value="{{ old('last_name') }}" />
                                <x-message name="last_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="email" validation="empty" tooltip="add_user_email" />
                                <x-input name="email" placeholder="{{ __('ui.enter') . ' ' . __('ui.email') }}" type="email" tooltip="add_user_email" regex="email" validation="empty" value="{{ old('email') }}" />
                                <x-message name="email" :message="@$message" validation="empty" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="phone" validation="required" tooltip="add_user_phone" />
                                <x-input type="hidden" id="countryCodeInput" name="country_code" :value="''" validation="empty" />

                                <x-input name="phone" placeholder="{{ __('ui.enter') . ' ' . __('ui.phone') }}" 
                                type="phone" tooltip="add_user_phone" regex="phone" validation="phone_number" value="{{ old('phone') }}" />

                                <x-message name="phone" :message="@$message" validation="required" />
                            </div>
                        </div>

                        @if(checkRequestKey('role'))
                            <x-input name="role" id="role_name" placeholder="" type="hidden" tooltip="" regex=""
                            validation="empty" value="{{ request()->get('role') ?? '' }}" />
                        @else
                            <div class="col-md-6">
                                <div class="form-group">
                                    <x-label name="assign_role" validation="required" tooltip="add_role_name" />
                                    <x-select name="role" value="{{ $role->id ?? old('role') }}" label="Role"
                                        optionName="display_name" class="select2" :arr="@$roles" validation="required"
                                        id="roleId" valueName="id" />
                                </div>
                            </div>
                        @endif

                        @php
                            $now = now()->format('Y-m-d');
                        @endphp
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="dob" validation="required" tooltip="add_user_dob" />
                                <x-date regex="dob" :max="$now" validation="required" type="date" value="{{ old('dob') }}" name="dob" id="dob" placeholder="Select your date" />
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
                                @endphp
                                <div class="form-group">
                                    <x-label name="gender" validation="common_name" tooltip="add_user_gender" />
                                    <x-radio name="gender" type="radio" value="male" :arr="$gender_arr" validation="empty" />
                                    <x-message name="gender" :message="@$message" validation="empty" />
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-group d-none">
                                <x-label name="status" tooltip="add_user_status" validation="empty" />
                                <x-input regex="" tooltip="add_user_status" validation="empty" type="checkbox" value="1" name="status" id="status" placeholder="Select Status" checked />
                                <x-message name="status" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(request()->has('role') && request()->get('role') == 'Driver')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.assign') @lang('ui.zone') @lang('ui.pincode') </h3>
                    </div>
                    <div class="card-body negative-margin">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="select_pincode" validation="empty" />
                                    <select name="pincodes[]" id="pincode" class="form-control select2" multiple>
                                        <option value="" disabled>Select Pincode</option>
                                        @foreach ($pincodes as $zonePincode)
                                        <option value="{{ $zonePincode->id }}">{{ $zonePincode->pincode }} | {{ @$zonePincode->zone->name ?? '' }} | {{ @$zonePincode->branch->name ?? '' }} | {{ $zonePincode->getPrefix() }}</option>
                                        @endforeach
                                    </select>
                                    <x-message name="vehicle_name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <div class="col-md-5 mx-auto">
            @include('panel.admin.include.message')
            <div class="card mb-3">
                 <div class="card-header d-flex justify-content-between">
                    <h3 class="mb-0"> @lang('ui.security') </h3>
                </div>
                <div class="card-body negative-margin">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <x-label name="set_password" validation="required" tooltip="add_user_password" />
                                    </div>
                                    <x-button type="button"
                                        class="btn btn-link p-0 m-0 generate_pass">@lang('ui.generate_password')
                                    </x-button>
                                </div>
                                <div class="input-group mb-3">
                                    <x-input name="password" placeholder="{{ __('ui.enter') . ' ' . __('ui.password') }}" type="password" tooltip="add_user_password" regex="password" validation="required" value="{{ old('password') }}" />

                                    <div class="input-group-append">
                                        <span class="input-group-text password-eye"
                                            style="cursor: pointer; position: absolute; right: 0px;"
                                            onclick="togglePasswordVisibility()">
                                            <i class="ik ik-eye text-color-black" id="togglePassword"></i>
                                        </span>
                                    </div>
                                </div>
                                <x-message name="password" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if(request()->has('role') && request()->get('role') == 'Driver')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.vehicle_details') </h3>
                    </div>
                    <div class="card-body negative-margin">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_name" validation="empty" />
                                    <x-input name="vehicle_name"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_name') }}" type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_name }}" />
                                    <x-message name="vehicle_name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_number" validation="empty" />
                                    <x-input name="vehicle_number"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_number') }}" type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_number }}" />
                                    <x-message name="vehicle_number" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="vehicle_type" validation="empty" />
                                    <x-input name="vehicle_type"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.vehicle_type') }}" type="text" regex="name" validation="empty"
                                        value="{{ @$user->vehicle_type }}" />
                                    <x-message name="vehicle_type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if(request()->has('role') && request()->get('role') == 'User')
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3> @lang('ui.account_type_details') </h3>
                    </div>
                    <div class="card-body negative-margin">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    @foreach (App\Models\User::ACCOUNT_TYPES as $accountKey => $accountType)
                                        <label class="mr-2">
                                            <input type="radio" name="account_type" class="accountType" @if($accountKey == 1) checked @endif value="{{ $accountKey }}"> {{ @$accountType['label'] }}
                                        </label>
                                    @endforeach
                                    <x-message name="account_type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 accountTypeLabels d-none">
                                <div class="form-group">
                                    <x-label name="company_name" validation="empty" />
                                    <x-input name="company_name"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.company_name') }}" type="text" regex="name" validation="empty"
                                        value="{{ @$user->company_name }}" />
                                    <x-message name="company_name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 accountTypeLabels d-none">
                                <div class="form-group">
                                    <x-label name="gst_number" validation="empty" />
                                    <x-input name="gst_number"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.gst_number') }}" type="text" regex="name" validation="empty"
                                        value="{{ @$user->gst_number }}" />
                                    <x-message name="gst_number" :message="@$message" validation="empty" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-button class="btn btn-primary floating-btn ajax-btn" type="submit">
        @lang('ui.create') {{ request()->get('role') ?? '' }}
    </x-button>
</form>

@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('select.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}

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

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#phone");
            const countryCodeInput = document.querySelector("#countryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
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
    {{-- END COUNTRYCODE SELECTOR INIT --}}

    {{-- START AJAX FORM INIT --}}

    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var route = form.attr('action');
            var method = form.attr('method');
            var data = new FormData(this);
            var role = $('#role_name').val();
            var redirectUrl = "{{ url('admin/users') }}" + '?role=' + role;
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            $('#togglePassword').click(function() {

                var input = $('#password');
                var icon = $(this);

                if (input.attr('type') === 'password') {
                    input.attr('type', 'text');
                    icon.removeClass('ik-eye').addClass('ik-eye-off');
                } else {
                    input.attr('type', 'password');
                    icon.removeClass('ik-eye-off').addClass('ik-eye');
                }
            });

            $(document).ready(function() {

                $('#state, #country, #city').css('width', '100%').select2();

                function getStates(countryId = 101) {
                    $.ajax({
                        url: '{{ route('world.get-states') }}',
                        method: 'GET',
                        data: {
                            country_id: countryId
                        },
                        success: function(res) {
                            $('#state').html(res).css('width', '100%').select2();
                        }
                    })
                }

                getStates(101);

                function getCities(stateId = 101) {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(res) {
                            $('#city').html(res).css('width', '100%').select2();
                        }
                    })
                }

                $('#country').on('change', function(e) {
                    getStates($(this).val());
                })

                $('#state').on('change', function(e) {
                    getCities($(this).val());
                })

            });

            var pass = "";
            $('.generate_pass').on('click', function() {
                var length = 8; // Minimum length required by the pattern
                var chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*";
                var hasDigit = false;
                var hasLowercase = false;
                var hasUppercase = false;
                var hasSpecialChar = false;

                while (pass.length < length || !(hasDigit && hasLowercase && hasUppercase &&
                        hasSpecialChar)) {
                    pass = "";
                    hasDigit = false;
                    hasLowercase = false;
                    hasUppercase = false;
                    hasSpecialChar = false;

                    for (var x = 0; x < length; x++) {
                        var i = Math.floor(Math.random() * chars.length);
                        pass += chars.charAt(i);
                    }

                    for (var i = 0; i < pass.length; i++) {
                        if (/[0-9]/.test(pass[i])) hasDigit = true;
                        else if (/[a-z]/.test(pass[i])) hasLowercase = true;
                        else if (/[A-Z]/.test(pass[i])) hasUppercase = true;
                        else if (/[!@#$%^&*]/.test(pass[i])) hasSpecialChar = true;
                    }
                }

                $('#password').val(pass);
            });
            $('#password').val(pass);
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
