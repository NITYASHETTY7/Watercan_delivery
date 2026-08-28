@extends('layouts.main')
@section('title', @$user->getPrefix() . ' ' . __('ui.user') . ' ' . __('ui.show'))
@section('content')

    @push('head')
        <style>
            .dt-button.dropdown-item.buttons-columnVisibility.active {
                background: #322d2d !important;
            }

            .center {
                position: absolute;
                left: 50%;
                transform: translate(-50%, -50%);
            }

            .footer-margin-l {
                margin-left: 16rem;
            }

            .footer-margin-r {
                margin-right: 1rem;
            }

            .ik-camera-custom {
                width: 30px !important;
                height: 30px !important;
                padding: 8px !important;
                line-height: 1 !important;
                top: 0 !important;
                right: 0 !important;
            }

            .bg-transform {
                background: transparent !important;
            }

            .select2-container {
                width: 100% !important;
            }
        </style>
    @endpush
    @php
        use App\Models\User;

        $accountType =
            User::ACCOUNT_TYPES[$user->account_type ?? User::ACCOUNT_TYPE_INDIVIDUAL] ??
            User::ACCOUNT_TYPES[User::ACCOUNT_TYPE_INDIVIDUAL];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ Str::limit(@$user->full_name, 20) }}</h5>
                            <span> @lang('ui.user') @lang('ui.profile') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.users.index') }}"> @lang('ui.user') </a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">{{ $user->getPrefix() }}</li>
                            <li class="breadcrumb-item" aria-current="page"> @lang('ui.show') </li>
                            <li class="breadcrumb-item fw-600" aria-current="page"> @lang('ui.profile') </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        @include('panel.admin.include.message')

        <div class="row">
            <div class="col-lg-4 col-md-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <div class="d-flex">
                                <div class="profile-show-custom mx-auto position-relative">
                                    <img src="{{ @$user && @$user->avatar ? @$user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                                        class="rounded-circle" style="min-height: 150px; max-height: 150px; max-width: 150px; min-width: 150px; object-fit: cover;"width="150" />
                                        @if(UserRole(@$user->id)->display_name == 'Driver')
                                        
                                    <x-button class="btn btn-dark rounded-circle position-absolute ik-camera-custom"
                                        data-toggle="modal" data-target="#updateProfileImageModal"><i
                                            class="ik ik-camera"></i></x-button>
                                            @endif
                                </div>
                            </div>
                            <h5 class="mb-0 mt-3">
                                {{ Str::limit(@$user->full_name, 20 ?? '--') }}
                                @if (@$user->is_verified == 1)
                                    <strong class="mr-1"><i class="ik ik-check-circle"></i></strong>
                                @endif
                            </h5>
                            @if(UserRole(@$user->id)->display_name == "User") 
                            <p class="text-sm text-gray-600">
                                <span
                                    class="badge text-white bg-{{$accountType['color']}}
                                        ">
                                    {{ $accountType['label'] }}
                                </span>
                            </p>
                            @endif
                            <span class="text-muted fw-600" style="align-items: center; flex-wrap: wrap;">
                                @lang('ui.role'):
                                {{ UserRole(@$user->id)->display_name ?? '--' }} |
                                {{ @$user->getPrefix() ?? '' }}
                            </span>
                            <div>
                                <a href="{{ route('panel.admin.users.edit', secureToken($user->id)) }}"
                                    class="btn btn-link">
                                    <span title="@lang('ui.edit') @lang('ui.user')"><i class="fa fa-edit"></i></span>
                                    @lang('ui.edit')
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="mb-0">
                    <div class="card-body">
                        <small class="text-muted d-block"> @lang('ui.email_address') </small>
                        <div class="d-flex justify-content-between">
                            <h6 style="overflow-wrap: anywhere;"><span><i class="ik ik-mail mr-1"></i><a
                                        class="text-color-white" href="mailto:{{ @$user->email ?? '--' }}"
                                        id="copyemail">{{ @$user->email ?? '--' }}</a></span></h6>
                            <span class="text-copy" title="@lang('ui.copy')" data-clipboard-target="#copyemail">
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block pt-10"> @lang('ui.phone_number') </small>
                        <div class="d-flex justify-content-between">
                            <h6>
                                <span>
                                    @if (!empty($user->country_code))
                                        <a class="text-color-white"
                                            href="tel:{{ $user->country_code }}{{ $user->phone ?? '--' }}" id="copyphone">
                                            <i class="ik ik-phone mr-1"></i>
                                            +{{ $user->country_code }} {{ $user->phone ?? '--' }}
                                        </a>
                                    @else
                                        <a class="text-color-white" href="tel:{{ $user->phone ?? '--' }}" id="copyphone">
                                            <i class="ik ik-phone mr-1"></i>
                                            {{ $user->phone ?? '--' }}
                                        </a>
                                    @endif
                                </span>
                            </h6>
                            <span class="text-copy" title="@lang('ui.copy')" data-clipboard-target="#copyphone" tile>
                                <i class="ik ik-copy"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block"> @lang('ui.dob') </small>
                        <div class="d-flex justify-content-between">
                            <h6 style="overflow-wrap: anywhere;"><span><i class="ik ik-mail mr-1"></i><a
                                        class="text-color-white" href="mailto:{{ @$user->dob ?? '--' }}"
                                        id="copyemail">{{ @$user->dob ?? '--' }}</a></span></h6>
                            </span>
                        </div>
                        @if ($user->userSubscription && $user->userSubscription->subscription)
                            <small class="text-muted d-block pt-10">@lang('ui.user_subscription')</small>

                            <div class="d-flex justify-content-between">
                                <h6>
                                    <span>
                                        <a href="#" class="text-color-white">
                                            <!-- Replace '#' with actual subscription link if available -->
                                            <i class="fal fa-dollar-sign mr-1"></i>
                                            {{ @$user->userSubscription->subscription->name ?? 'N/A' }}
                                        </a><br>
                                        <span>
                                            <small
                                                class="text-muted d-block pt-10">{{ @$user->userSubscription->from_date ?? 'N/A' }}
                                                -
                                                {{ @$user->userSubscription->to_date ?? 'N/A' }}</small>

                                        </span>
                                    </span>
                                </h6>
                            </div>
                        @endif
                        @if (UserRole(@$user->id)->display_name == 'User')
                            <small class="text-muted d-block pt-10"> @lang('ui.member_since')</small>
                            <h6 class="mb-1">
                                {{ @$user->formatted_created_at ? \Carbon\Carbon::parse($user->formatted_created_at)->format('d F, Y') : '--' }}
                            </h6>
                        @endif

                        <div>
                            @if (getSetting('toggling_dac_activation', @$master_setting) == 1)
                                <small class="text-muted d-block pt-10"> @lang('ui.delegate_access_code') </small>
                                <div class="input-group mb-3">
                                    <x-input id="password" type="password" autocomplete="off"
                                        class="form-control @error('password') is-invalid @enderror" minlength="4"
                                        name="password" value="{{ @$user->delegate_access }}" placeholder="Enter Password"
                                        required style="border: 0px" validation="empty" />
                                    <div class="input-group-append">
                                        <span class="input-group-text"
                                            style="background-color: white; margin-right: 20px !important; border: 0px;">
                                            <i class="ik ik-eye text-color-black" id="togglePassword"></i>
                                        </span>
                                        <span class="input-group-text"
                                            style=" color:black; background-color: white;  margin-right: -10px !important; border: 0px; cursor: pointer;"
                                            title="Copy" data-value="{{ $user->delegate_access }}"
                                            onclick="copyToClipboard(this);">
                                            <i class="ik ik-copy text-color-black"></i>
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-md-7">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-password-tab" data-toggle="pill" href="#password-tab"
                                role="tab" aria-controls="pills-password" aria-selected="true">
                                @lang('ui.change_password')
                            </a>
                        </li>
                        @if ($userKyc)
                            <li class="nav-item">
                                <a class="nav-link" id="pills-kyc-tab" data-toggle="pill" href="#kyc-tab"
                                    role="tab" aria-controls="pills-kyc" aria-selected="true">
                                    @lang('ui.ekyc')
                                </a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="password-tab" role="tabpanel"
                            aria-labelledby="pills-password-tab">
                            @include('panel.admin.users.includes.change_password.index')
                        </div>
                        @if ($userKyc)
                            <div class="tab-pane fade" id="kyc-tab" role="tabpanel" aria-labelledby="pills-kyc-tab">
                                <div class="">
                                    <div class="card-header d-flex justify-content-between">
                                        <h3 class="mb-0"><i class="fa fa-credit-card-alt"></i>
                                            @lang('ui.kyc_details') </h3>
                                    </div>
                                    <div class="card-body">
                                        <table class="table mt-2">
                                            <tbody>
                                                <tr>
                                                    <td class="p-1">@lang('ui.driving_licence')</td>
                                                    <td class="p-1 text-right">
                                                        <a href="{{ asset($userKyc->driving_licence) }}" download>
                                                            <i class="ik ik-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr>
                                                {{-- <tr>
                                                    <td class="p-1">@lang('ui.signature')</td>
                                                    <td class="p-1 text-right">
                                                        <a href="{{ asset($userKyc->signature) }}" download>
                                                            <i class="ik ik-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr class="bpayout-none">

                                                    <td class="p-1">@lang('ui.seal')</td>
                                                    <td class="p-1 text-right">
                                                        <a href="{{ asset($userKyc->seal) }}" download>
                                                            <i class="ik ik-download"></i> Download
                                                        </a>
                                                    </td>
                                                </tr> --}}
                                            </tbody>
                                        </table>

                                        @if ($userKyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL)
                                            <div>
                                                <form
                                                    action="{{ route('panel.admin.user-kyc.update-status', secureToken($userKyc->id)) }}"
                                                    method="post" class="ajaxForm" class="mt-4">
                                                    @csrf
                                                    <x-input type="hidden" name="request_with" value="update-status"
                                                        validation="empty" />
                                                    @php
                                                        $radio_arr = [
                                                            ['name' => 'approve_mark', 'value' => 1],
                                                            ['name' => 'reject_request', 'value' => 2],
                                                        ];
                                                    @endphp
                                                    <x-radio name="status" value="{{ @$userKyc->status }}"
                                                        :arr="$radio_arr" class="updateStatusBtn" validation="empty"
                                                        tooltip="" data-custom="example" />

                                                    <div class="form-group d-none txn-wrap mt-2">
                                                        <x-label name="enter_remark" validation="empty" />
                                                        <x-textarea name="confirmation_remark"
                                                            placeholder="{{ __('ui.enter_remark_here') }}" type="text"
                                                            regex="alpha_numeric" :value="old('confirmation_remark')" validation="empty" />
                                                    </div>
                                                    <div class="form-group d-none remark-wrap mt-2">
                                                        <x-label name="enter_rejection_reason" validation="required" />
                                                        <x-textarea name="rejection_remark" id="remarkBox"
                                                            class="form-control"
                                                            placeholder="{{ __('ui.enter_reason_here') }}"
                                                            regex="alpha_numeric" :value="old('remark')" label="Remarks"
                                                            validation="empty" />
                                                    </div>

                                                    <hr>
                                                    <div id="show-btn" class="d-none">
                                                        <div class="mt-3 d-flex justify-content-between">
                                                            <div class="text-danger mt-2">
                                                                <i class="ik ik-info"></i>
                                                                @lang('ui.rollback')
                                                            </div>
                                                            <x-button class="btn btn-primary confirm-form-btn"
                                                                type="submit">{{ __('ui.confirm_action') }}
                                                            </x-button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        @elseif($userKyc->status == \App\Models\UserKyc::STATUS_VERIFIED)
                                            <span class="alert alert-success d-block">
                                                @if (isset($userKyc->details['remark']))
                                                    @lang('ui.with')
                                                    {!! nl2br(@$userKyc->details['remark']) !!}
                                                @endif
                                                @lang('ui.submission_request_approved') <strong>{{ @$userKyc->txn_no }}</strong> by
                                                <strong>{{ @\App\Models\User::whereId(@$userKyc->details['action_by'])->first()->name ?? auth()->user()->name }}</strong>
                                                At {{ @$userKyc->updated_at }}
                                            </span>
                                        @else
                                            <span class="alert alert-danger d-block">
                                                @lang('ui.submission_request_rejected') <strong>
                                                    @if (isset($userKyc->details['rejection_remark']))
                                                        @lang('ui.due_to')
                                                        {!! nl2br($userKyc->details['rejection_remark']) ?? '--' !!}
                                                    @endif
                                                </strong> by
                                                <strong>{{ @\App\Models\User::whereId(@$userKyc->details['action_by'])->first()->name ?? auth()->user()->name }}</strong>
                                                At {{ @$userKyc->updated_at ?? '--' }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    {{-- INCLUDE CLIPBOARD COPY CDN --}}
    @include('panel.common.script.copy-to-clipboard')
    {{-- <script src="{{ asset($master_root_directory . 'plugins/clipboard/clipboard.min.js') }}"></script> --}}
    {{-- END INCLUDE CLIPBOARD COPY CDN --}}

    {{-- INCLUDE CROPPIE COPY CDN --}}
    <script src="{{ asset($master_root_directory . 'plugins/datedropper/croppie.min.js') }}"></script>
    {{-- END INCLUDE CROPPIE COPY CDN --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $(document).on('submit', '.ajaxForm', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);

            // Dynamically get the 'active' value from the current URL
            var urlParams = new URLSearchParams(window.location.search);
            var activeTab = urlParams.get('active');

            var redirectUrl = "{{ url('admin/users/show') }}" + '/' + "{{ secureToken($user->id) }}" +
                "?active=" + activeTab;

            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    <script>
        $(document).on('click', '.edit-contact', function() {
            var contact = $(this).data('contact');
            var id = $(this).data('id');
            $('#edit_type_id').val(contact.type_id);
            $('#edit_first_name').val(contact.first_name);
            $('#edit_last_name').val(contact.last_name);
            $('#edit_job_title').val(contact.job_title);
            $('#edit_job_title').val(contact.job_title);
            $('#edit_email').val(contact.email);
            $('#edit_prefix').val(contact.prefix);
            $('#editContactCountryCode').val(contact.phone);
            var url = "{{ url('/admin/user-contacts/update') }}" + '/' + id;
            $('#editContactForm').attr('action', url);
            $('#editContact').modal('show');
        });
    </script>

    <script>
        $(document).on('click', '.editAddress', function() {
            var address = $(this).data('id');
            var details = address.details;
            var phone = $(this).data('phone');
            if (details.type == 0) {
                // Correct way to select a radio button by name and value
                $('input[name="type"][value="0"]').prop("checked", true);
            } else {
                $('input[name="type"][value="1"]').prop("checked", true);
            }


            $('#editName').val(details.name);
            $('#id').val(address.id);
            $('#addressId').val(address.id);
            $('#user_id').val(address.user_id);
            $('#editAddressCountryCode').val(details.phone);
            $('#editAddress').val(details.address_1);
            $('#editAddress_2').val(details.address_2);
            $('#pincode_id').val(details.pincode_id);
            $('#countryEdit').val(details.country_id).trigger('change');

            // Load states, then cities
            getStateAsync(details.country_id).then(function() {
                $('#stateEdit').val(details.state_id).trigger('change');

                return getCityAsync(details.state_id); // chain promise
            }).then(function() {
                $('#cityEdit').val(details.city).trigger('change'); // safe to select now
            });

            $('#editAddressModal').modal('show');
        });
    </script>
    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            var table = $('.data_table').DataTable({
                responsive: true,
                fixedColumns: true,
                fixedHeader: true,
                scrollX: false,
                'aoColumnDefs': [{
                    'bSortable': false,
                    'aTargets': ['nosort']
                }],
                dom: "<'row'<'col-sm-2'l><'col-sm-7 text-center'B><'col-sm-3'f>>tipr",
                buttons: [{
                    extend: 'excel',
                    className: 'btn-sm btn-success',
                    header: true,
                    footer: true,
                    exportOptions: {
                        columns: ':visible',
                    }
                }, ]

            });
        });

        document.getElementById('avatar').onchange = function() {
            var src = URL.createObjectURL(this.files[0])
            $('#avatar_file').removeClass('d-none');
            document.getElementById('avatar_file').src = src
        }

        function updateCoords(im, obj) {
            $('#x').val(obj.x1);
            $('#y').val(obj.y1);
            $('#w').val(obj.width);
            $('#h').val(obj.height);
        }

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

        // this functionality work in edit page
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
                        $('#stateEdit').html(data);
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
                            $('#cityEdit').html(data);
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
            $('.accept').on('click', function() {
                $('#status').val(1)
            });
            $('.reject').on('click', function() {
                $('#status').val(2)
            });
            $('.reset').on('click', function() {
                $('#status').val(0)
            });
            var country = "{{ $user->country_id }}";
            var state = "{{ $user->state_id }}";
            var city = "{{ $user->city_id }}";

            if (state) {
                getStateAsync(country).then(function(data) {
                    $('#state').val(state).change();

                });
            }
            if (city) {
                $('#state').on('change', function() {
                    if (state == $(this).val()) {
                        getCityAsync(state).then(function(data) {
                            $('#city').val(city).change();

                        });
                    }
                });
            }
        });
        $(document).on('click', '.edit-note', function() {
            var data = $(this).data('item');
            var id = $(this).data('id');
            $('#note-type_id').val(data.type_id);
            $('#note-title').val(data.title);
            $('#note-description').val(data.description);
            $('#category_id_edit').val(data.category_id).trigger('change');
            var url = "{{ url('/admin/user-notes/update') }}" + '/' + id;
            $('#edit_noteForm').attr('action', url);
            $('#editModalCenter').modal('show');
        });

        $(document).on('click', '.addPayoutDetailBtn', function() {
            $('#bankDetailsModalCenter').modal('show');
        });

        $(document).on('click', '.editPayoutDetailBtn', function() {
            let record = $(this).data('row');
            if (record.type == "Saving")
                $('#editsaving').prop('checked', true);
            else
                $('#editcurrent').prop('checked', true);

            $('#payoutdetailId').val(record.id);
            $('#editaccount_holder_name').val(record.account_holder_name);
            $('#editaccount_no').val(record.account_number);
            $('#editifsc_code').val(record.bank_ifsc_code);
            $('#editbranch').val(record.branch);
            $('#edit_bank').val(record.bank_id).change();
            $('#editBankDetailsModal').modal('show');
        });

        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            var url = $(this).attr('data-url');
            updateURL('search', '');
            updateURL('active', active);
            fetchData(url, active);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START DELEGATE ACCESS BUTTON INIT --}}
    <script>
        $(document).on('click', '.loginAsBtn', function(e) {
            e.preventDefault();
            let user_id = $(this).data('user_id');
            let first_name = $(this).data('first_name');
            $('.delegateUserId').val(user_id);
            $('.delegateUserName').html(first_name);
            $('#DelegateAccessModel').modal('show');
        });
        $(document).on('click', '.close', function() {
            $('#DelegateAccessModel').modal('hide');
        });
    </script>
    {{-- END DELEGATE ACCESS BUTTON INIT --}}

    {{-- START DELEGATE ACCESS CODE HIDE SHOW INIT --}}
    <script>
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
    </script>
    {{-- END DELEGATE ACCESS CODE HIDE SHOW INIT --}}

    {{-- START PROFILE IMAGE CROPPER --}}
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
                imagePreview.src = URL.createObjectURL(file);
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
    {{-- END PROFILE IMAGE CROPPER --}}

    {{-- START PREVIEW MODAL INIT --}}
    <script>
        $(document).ready(function() {
            $('.open-modal').on('click', function() {
                var documentSrc = $(this).attr('href');

                $('#previewImageContainer').html(
                    `<img src="${documentSrc}" class="img-fluid" alt="File Preview">`);
            });

            $('#filePreviewModal').modal({
                show: false
            });
        });
    </script>
    {{-- END PREVIEW MODAL INIT --}}

    {{-- COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#contactPhone");
            const contactCountryCodeInput = document.querySelector("#contactCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                contactCountryCodeInput.value = selectedCountryData.dialCode;
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

    {{-- Edit Contact COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#editContactCountryCode");
            const editContactCountryCodeInput = document.querySelector("#editContactCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                editContactCountryCodeInput.value = selectedCountryData.dialCode;
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


    {{-- Address COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#addressPhone");
            const addressCountryCodeInput = document.querySelector("#addressCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                addressCountryCodeInput.value = selectedCountryData.dialCode;
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

    {{-- Edit Address COUNTRYCODE SELECTOR INIT --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const input = document.querySelector("#editAddressCountryCode");
            const editAddressCountryCodeInput = document.querySelector("#editAddressCountryCodeInput");

            const iti = window.intlTelInput(input, {
                initialCountry: "auto",
                separateDialCode: true,
                geoIpLookup: callback => {
                    fetch("https://ipapi.co/json")
                        .then(res => res.json())
                        .then(data => callback(data.country_code))
                        .catch(() => callback("us"));
                },
                utilsScript: "{{ asset('panel/admin/plugins/country-code/utils.js') }}",

            });
            window.iti = iti;

            const updateCountryCode = () => {
                const selectedCountryData = iti.getSelectedCountryData();
                editAddressCountryCodeInput.value = selectedCountryData.dialCode;
            };

            input.addEventListener("countryChange", updateCountryCode);
            input.addEventListener("keyup", updateCountryCode);
            input.addEventListener("change", updateCountryCode);

            setTimeout(() => {
                const event = new Event('countryChange');
                input.dispatchEvent(event);
            }, 300);
        });

        $(document).ready(function() {
            $('.updateStatusBtn').on('click', function() {
                $('#show-btn').removeClass('d-none');
                if ($(this).val() == 1) {
                    $('.txn-wrap').removeClass('d-none');
                    $('#remarkBox').removeAttr('required');
                    $('#txn_no').prop('required', 'required');
                    $('.remark-wrap').addClass('d-none');
                } else {
                    $('.remark-wrap').removeClass('d-none');
                    $('#remarkBox').prop('required', 'required');
                    $('#txn_no').removeAttr('required');
                    $('.txn-wrap').addClass('d-none');
                }
            });
        });
    </script>
    {{-- END COUNTRYCODE SELECTOR INIT --}}
@endpush
