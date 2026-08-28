@extends('layouts.main')
@section('title', @$zonePincodeUser->getPrefix() . ' - ' . __('ui.edit') . (isset($label) ? ' ' . $label : ''))
@section('content')

    @php
        @$breadcrumb_arr = [
            ['name' => $label, 'url' => route('panel.admin.zone-pincode-users.index', ['zone_pincode_id' => secureToken($zonePincodeUser->zone_pincode_id)]), 'class' => '--'],
            ['name' => @$zonePincodeUser->getPrefix(), 'url' => route('panel.admin.zone-pincode-users.index', ['zone_pincode_id' => secureToken($zonePincodeUser->zone_pincode_id)]), 'class' => '--'],
            ['name' => __('ui.edit'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.edit') {{ $label }} </h5>
                            <span> @lang('ui.update_record') {{ $label }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')

                </div>
            </div>
        </div>
        @include('panel.admin.zone_pincode_users.edit.form')
    </div>
    @push('script')
        {{-- START GETUSERS INIT --}}
        <script>
            $(document).ready(function() {
                getUsers();

                // Preselect user if editing existing record
                let userId = "{{ @$zonePincodeUser->user_id ?? '' }}";
                let userName = "{{ @$zonePincodeUser->user->name ?? '' }}";
                let userEmail = "{{ @$zonePincodeUser->user->email ?? '' }}";

                if (userId && userName) {
                    // Add an option for the preselected user
                    let option = new Option(`${userName} | #UID${userId} | ${userEmail}`, userId, true, true);
                    $('.getUsersList').append(option).trigger('change');
                }
            });
        </script>
        {{-- END GETUSERS INIT --}}
    @endpush
@endsection
