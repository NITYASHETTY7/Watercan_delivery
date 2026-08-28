@extends('layouts.main')
@section('title', __('ui.profile'))
@section('content')
    @push('head')
    @endpush

    <style>
        .croppie-container .cr-boundary {
            width: 300px;
            height: 300px;
            margin: auto;
            overflow: hidden;
            position: relative;
        }

        .center {
            position: absolute;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .iti--inline-dropdown .iti__dropdown-content {
            z-index: 9 !important;
        }

        .ik-camera-custom {
            width: 30px !important;
            height: 30px !important;
            padding: 8px !important;
            line-height: 1 !important;
            top: 0 !important;
            right: 0 !important;
        }
    </style>

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-file-text bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.profile') </h5>
                            <span> @lang('ui.update_profile') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>

                            <li class="breadcrumb-item" aria-current="page"> @lang('ui.profile') </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="mx-auto position-relative" style="width: 150px; height: 150px;">
                                <img src="{{ $user && $user->avatar ? $user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                                    class="rounded-circle admin-avatar-profile" />
                                @if ($user->avatar && strpos($user->avatar, 'ui-avatars.com/api'))
                                    <x-button class="btn btn-dark rounded-circle position-absolute ik-camera-custom"
                                        data-toggle="modal" data-target="#updateProfileImageModal">
                                        <i class="ik ik-camera"></i>
                                    </x-button>
                                @else
                                    <x-button class="btn btn-danger rounded-circle position-absolute ik-camera-custom">
                                        <a href="{{ route('panel.admin.profile.remove.profile-img', secureToken($user->id)) }}"
                                            class="delete-item" data-msg="Sure You want to remove this image">
                                            <i class="ik ik-trash text-white"></i>
                                        </a>
                                    </x-button>
                                @endif
                            </div>
                            <h5 class="mb-0 mt-3">
                                {{ Str::limit($user->full_name, 20) }}
                                @if ($user->is_verified == 1)
                                    <strong class="mr-1"><i class="ik ik-check-circle"></i></strong>
                                @endif
                            </h5>
                            <span class="text-muted" title="@lang('ui.role_name')">{{ $user->role_name }}</span>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="card-body">
                        <small class="text-muted d-block"> @lang('ui.email_address') </small>
                        <div class="d-flex justify-content-between">
                            <h6 style="overflow-wrap: anywhere;">
                                <span>
                                    <i class="ik ik-mail mr-1"></i>
                                    <span id="copyemail" class="text-color-white">{{ $user->email ?? '' }}</span>
                                </span>
                            </h6>
                            <span class="text-copy" title="@lang('ui.copy')" data-clipboard-target="#copyemail">
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block pt-10"> @lang('ui.phone_number') </small>
                        <div class="d-flex justify-content-between">
                            <h6><span><a class="text-color-white"
                                        href="tel:{{ $user->country_code ?? '' }} {{ $user->phone ?? '' }}"
                                        id="copyphone"><i class="ik ik-phone mr-1"></i>+{{ $user->country_code ?? '' }}
                                        {{ $user->phone ?? '' }}</a></span>
                            </h6>
                            <span class="text-copy" title="@lang('ui.copy')" data-clipboard-target="#copyphone" tile>
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a data-active="setting"
                                class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'setting') || !request()->has('active')) active @endif"
                                data-type="setting" id="pills-setting-tab" data-toggle="pill" href="#previous-month"
                                role="tab" aria-controls="pills-setting" aria-selected="false"> @lang('ui.setting') </a>
                        </li>
                        <li class="nav-item">
                            <a data-active="account"
                                class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'account') active @endif"
                                data-type="account" id="pills-timeline-tab" data-toggle="pill" href="#current-month"
                                role="tab" aria-controls="pills-timeline" aria-selected="true"> @lang('ui.change_password') </a>
                        </li>
                        @if ($master_permissions->contains('mfa_view_rp'))
                            @if (getSetting('mfa_activation') == 1)
                                <li class="nav-item">
                                    <a data-active="security"
                                        class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'security') active @endif"
                                        data-type="security" id="pills-security-tab" data-toggle="pill" href="#security"
                                        role="tab" aria-controls="pills-timeline" aria-selected="true">
                                        @lang('ui.mfa') </a>
                                </li>
                            @endif
                        @endif

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade @if ((request()->has('active') && request()->get('active') == 'setting') || !request()->has('active')) show active @endif"
                            id="previous-month" role="tabpanel" aria-labelledby="pills-setting-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.profile.update', secureToken($user->id)) }}"
                                    method="POST" class="form-horizontal">
                                    @csrf
                                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="profile" />
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="first_name" validation="common_name" tooltip="" />
                                                <x-input name="first_name" id="first_name"
                                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.first_name') }}"
                                                    type="text" tooltip="add_user_first_name" regex="name"
                                                    validation="common_name" value="{{ @$user->first_name }}" />
                                                <x-message name="first_name" :message="@$message" validation="common_name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="last_name" validation="common_name" tooltip="" />
                                                <x-input name="last_name" id="last_name"
                                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.last_name') }}"
                                                    type="text" tooltip="" regex="name"
                                                    validation="common_name" value="{{ $user->last_name }}" />
                                                <x-message name="last_name" :message="@$message" validation="common_name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">

                                                <x-label name="email" validation="common_email" tooltip="" />
                                                <x-input name="email" id="email"
                                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.email') }}"
                                                    type="email" tooltip="" regex="email"
                                                    validation="common_email" value="{{ $user->email }}" />
                                                <x-message name="email" :message="@$message" validation="common_email" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="contact_number" validation="common_phone_number" tooltip="" />
                                                <div class="input-group">
                                                    <x-input type="hidden" id="countryCodeInput" name="country_code" value="" validation="country_code" />
                                                    <x-input type="tel" class="form-control" id="phone"
                                                        name="phone" value="{{ $user->fullPhone() }}"
                                                        validation="phone_number" />
                                                </div>
                                            </div>
                                        </div>

                                        @php
                                            $now = now()->format('Y-m-d');
                                        @endphp

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <x-label name="time_zone" validation="required" tooltip="" />
                                                <x-select name="timezone" value="{{ $user->timezone }}" label="Status"
                                                    optionName="" valueName="optionValue" class="select2"
                                                    :arr="@$timezones" validation="required" id="timezone" />

                                            </div>
                                        </div>
                                    </div>
                                    <x-button type="submit" class="btn btn-primary">@lang('ui.update')</x-button>
                                </form>
                            </div>
                        </div>

                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'account') show active @endif"
                            id="current-month" role="tabpanel" aria-labelledby="pills-timeline-tab">
                            <div class="card-body">
                                <form class="row"
                                    action="{{ route('panel.admin.profile.update.password', secureToken($user->id)) }}"
                                    method="POST">
                                    @csrf
                                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="password" />
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <x-label name="new_password" validation="new_password" tooltip="" />
                                                <x-input name="password" id="password" placeholder="{{ __('ui.enter') . ' ' . __('ui.password') }}" type="password" tooltip="" regex="" validation="new_password" value="" />
                                                <x-message name="Password" :message="@$message" validation="new_password" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <x-label name="confirm_password" validation="confirm_password" tooltip="" />
                                        <x-input name="confirm_password" id="confirm_password" placeholder="{{ __('ui.confirm_password') }}" type="password" tooltip="" regex="" validation="confirm_password" value="" />
                                        <x-message name="Password" :message="@$message" validation="confirm_password" />
                                    </div>
                                    <div class="col-md-12">
                                        <x-button class="btn btn-primary" type="submit">@lang('ui.update')</x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        @if (getSetting('mfa_activation') == 1)
                            <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'security') show active @endif"
                                id="security" role="tabpanel" aria-labelledby="pills-security-tab">
                                <form action="{{ route('mfa-store') }}" method="post">
                                    @csrf
                                    <x-input name="secret_key" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="{{ @$secret ?? '' }}" />
                                    @if (auth()->user()->google2fa_secret == null)
                                        <div class="card-body text-center">
                                            <h6 class="fw-700 mb-0">@lang('ui.mfa')</h6>
                                            <div>
                                                {!! @$QR_Image ?? '' !!}
                                                <hr>

                                            </div>
                                            <div class="text-center text-muted w-75 mx-auto mb-4">
                                                @lang('ui.mfa_setup_instruction')
                                                <br>
                                                @lang('ui.use') <b><a
                                                        href="https://safety.google/authentication/">@lang('ui.google_authenticator')</a></b>
                                                @lang('ui.app_for_continuing')
                                            </div>

                                            <x-button class="btn btn-primary" type="submit">@lang('ui.scanned_qr')</x-button>
                                        </div>
                                    @else
                                        <div class="card-body text-center">
                                            <h6 class="fw-700 mb-0">@lang('ui.two_factor_authentication')</h6>
                                            <p class="text-muted mb-4">@lang('ui.two_fa_enabled')</p>
                                            <a href="{{ route('mfa-enabled') }}"
                                                class="btn btn-danger">@lang('ui.scan_again')</a>
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel/admin/profile/include/profile_modal/index')

@endsection

@push('script')
    {{-- INCLUDE CLIPBOARD COPY CDN --}}
    <script src="{{ asset($master_root_directory . 'plugins/clipboard/clipboard.min.js') }}"></script>
    {{-- END INCLUDE CLIPBOARD COPY CDN --}}

    {{-- INCLUDE CROPPIE COPY CDN --}}
    <script src="{{ asset($master_root_directory . 'plugins/datedropper/croppie.min.js') }}"></script>
    {{-- END INCLUDE CROPPIE COPY CDN --}}

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
    {{-- END COUNTRYCODE SELECTOR INIT --}}

    {{-- START JS HELEPR INIT --}}
    <script>
        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            updateURL('active', active);
        });

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

        // Country, City, State Code
        $('#state, #country, #city').css('width', '100%').select2();

        getStates(101);
        $('#country').on('change', function(e) {
            getStates($(this).val());
        })

        $('#state').on('change', function(e) {
            getCities($(this).val());
        })

        function getStateAsync(countryId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('world.get-states') }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#state').html(data);
                        $('.state').html(data);
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    },
                })
            })
        }

        function getCityAsync(stateId) {
            if (stateId != "") {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#city').html(data);
                            $('.city').html(data);
                            resolve(data)
                        },
                        error: function(error) {
                            reject(error)
                        },
                    })
                })
            }
        }

        $(document).ready(function() {
            var country = "{{ $user->country_id }}";
            var state = "{{ $user->state_id }}";
            var city = "{{ $user->city_id }}";
            if (state) {
                getStateAsync(country).then(function(data) {
                    $('#state').val(state).change();
                    $('#state').trigger('change');
                });
            }
            if (city) {
                $('#state').on('change', function() {
                    if (state == $(this).val()) {
                        getCityAsync(state).then(function(data) {
                            $('#city').val(city).change();
                            $('#city').trigger('change');
                        });
                    }
                });
            }
        });
    </script>
    {{-- END HELPER JS INIT --}}

    {{-- START IMAGE PREVIEW JS INIT --}}
    <script>
        const avatar = document.getElementById('avatar');
        const imagePreview = document.getElementById('imagePreview');
        const croppedImageDataInput = document.getElementById('croppedImageData');
        const croppieContainer = document.querySelector('.demo');

        let croppieInstance = null;

        // When the input field for selecting an image changes
        avatar.onchange = evt => {
            const [file] = avatar.files;
            if (file) {
                // Show the selected image in the preview
                imagePreview.src = URL.createObjectURL(file);
                // Initialize Croppie on the `.demo` element
                croppieInstance = new Croppie(croppieContainer, {
                    enableExif: true,
                    viewport: {
                        width: 200,
                        height: 200,
                        type: 'circle'
                    },
                    boundary: {
                        width: 300,
                        height: 300
                    }
                });

                // Bind the selected image to Croppie
                croppieInstance.bind({
                    url: URL.createObjectURL(file),
                });
            }
        };

        // Capture cropped image data when the form is submitted
        document.querySelector('#updateProfileImageModal').onsubmit = () => {
            if (croppieInstance) {
                croppieInstance.result('base64').then(function(result) {
                    // Set the cropped image data to the hidden input
                    croppedImageDataInput.value = result;
                });
            }
        };
    </script>
    {{-- END IMAGE PREVIEW JS INIT --}}

    {{-- COPY TEXT CODE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var clipboard = new ClipboardJS('.text-copy');

            clipboard.on('success', function(e) {
                console.log('Copied:', e.text);
                e.clearSelection();

                // Add 'Copied' message temporarily
                var originalTitle = e.trigger.title; // Save the original title
                e.trigger.innerHTML = 'Copied!';

                setTimeout(function() {
                    e.trigger.innerHTML = '<i class="ik ik-copy"></i>'; // Restore original content
                    e.trigger.title = originalTitle; // Restore the title
                }, 1000); // Show "Copied!" for 1 second
            });

            clipboard.on('error', function(e) {
                console.error('Error copying:', e);
            });
        });
    </script>
    {{-- END COPY TEXT CODE --}}
@endpush
