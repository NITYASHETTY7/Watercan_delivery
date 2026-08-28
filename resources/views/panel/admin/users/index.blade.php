@extends('layouts.main')
@section('title', @$label)
@section('content')

    @push('head')
        {{-- INITIALIZE SHIMMER & INIT LOAD --}}
        <script>
            window.onload = function() {
                $('#ajax-container').show();
                fetchData("{!! getCurrentUrlWithParams() !!}");
            };
        </script>
        {{-- END INITIALIZE SHIMMER & INIT LOAD --}}
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
                                <a href="#">{{ @$label ?? '' }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ @$label ?? '' }}</h3>
                        <div class="d-flex align-items-center">
                            @if ($master_permissions->contains('user_create_rp'))
                                <a href="{{ route('panel.admin.users.create', ['role' => request()->get('role')]) }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add New Users"><i class="fa fa-plus"
                                        aria-hidden="true"></i> @lang('ui.add') </a>
                            @endif
                            @if ($master_permissions->contains('user_bulk_rp'))
                                @if (getSetting('toggling_user_management_bulk_status_update', @$master_setting) ||
                                        getSetting('toggling_user_management_bulk_delete', @$master_setting) ||
                                        getSetting('toggling_user_management_bulk_upload', @$master_setting))
                                    @if (@$bulk_activation == 1)
                                        <form action="{{ route('panel.admin.users.bulk-action') }}" method="POST"
                                            id="bulkAction" class="">
                                            @csrf
                                            <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                                            <x-input type="hidden" id="full-name" value="{{ auth()->user()->full_name }}"
                                                validation="empty" name="" />
                                            <div>
                                                <x-button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                                    type="button" id="dropdownMenu1" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"><i
                                                        class="ik ik-more-vertical fa-lg pl-1"></i></x-button>
                                                <ul class="dropdown-menu multi-level" role="menu"
                                                    aria-labelledby="dropdownMenu">

                                                    @if (getSetting('toggling_user_management_bulk_status_update', @$master_setting))

                                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                            data-value="0" data-status="Incomplete"
                                                            data-column="status"
                                                            data-message="You want to mark these Users as Inactive?"
                                                            data-action="columnUpdate"
                                                            data-callback="bulkColumnUpdateCallback">
                                                            @lang('ui.mark_as_inactive')
                                                        </a>

                                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                            data-value="1" data-status="Completed" data-column="status"
                                                            data-message="You want to mark these Users as Active?"
                                                            data-action="columnUpdate"
                                                            data-callback="bulkColumnUpdateCallback">@lang('ui.mark_as_active')
                                                        </a>
                                                    @endif
                                                    @if (getSetting('toggling_user_management_bulk_delete', @$master_setting))
                                                        <hr class="m-1">
                                                        <x-button type="submit"
                                                            class="dropdown-item bulk-action text-danger fw-700"
                                                            data-value="" data-message="You want to delete these Users?"
                                                            data-action="delete" data-callback="bulkDeleteCallback"><i
                                                                class="ik ik-trash"> </i>
                                                            @lang('ui.bulk_delete')
                                                        </x-button>
                                                    @endif
                                                </ul>
                                            </div>
                                        </form>
                                    @endif
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.users.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (getSetting('toggling_user_management_table_filter', @$master_setting))
        @include('panel.admin.users.includes.filter')
    @endif  

@endsection

@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}
    
    {{-- START UPDATE STATUS INIT --}}
    <script>
        // UPDATE USER STATUS USING AJAX
        $(document).on('click', '.statusChanger', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var currentStatus = $('.status' + id).data('status');
            var currentBadgeClass = $('.status' + id).data('class');
            var status = $(this).data('status');
            var badgeClass = $(this).data('class');
            var value = $(this).data('value');
            var url = $(this).data('url');
        })
    </script>
    {{-- END UPDATE STATUS INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                console.log(response);
                $('#walletModal').modal('toggle');
                $('.amount').val('');
                $('.transationType').prop('checked', false);
                let id = response.user_id;
                let route = "{{ url('/admin/wallet-logs/user') }}";
                window.location.href = route + '/' + id;
            }
        });
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START WALLET LOG INIT --}}
    <script>
        $(document).on('click', '.walletLogButton', function() {
            var user_record = $(this).data('id');
            $('#uuid').val(user_record);
            $('#walletModal').modal('show');
        });
        $(document).on('click', '.close', function() {
            $('#walletModal').modal('hide');
        });
    </script>
    {{-- END WALLET LOG INIT --}}

    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>
    {{-- START EXCEL BUTTON INIT --}}
    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const status = urlParams.get('is_verified') || 'All Status';
            const statuses = @json(App\Models\User::USER_STATUSES);
            const statusLabel = statuses[status]?.label || 'All Status';
            const moduleName = "{{ @$label ?? 'Customers' }}";
            exportTableToExcel({
                tableSelector : "#table",
                type: "xlsx",
                moduleName: moduleName,
                fullName: "{{ auth()->check() ? str_replace(' ', '-', auth()->user()->full_name) : 'Unknown User' }}",
                appName: "{{ env('APP_NAME') }}", // Injected by Laravel Blade
                report_format: [
                    // { label: "Verification Status", value: statusLabel },
                    // { label: "Date Range", value: dateRange },
                    { label: "Report Name", value: moduleName },
                    { label: "Company", value: "{{ env('APP_NAME') }}" }
                ]
            });
        });
    </script>

    {{-- END HTML TO EXCEL INIT --}}

    {{-- START JS HELPERS INIT --}}

    <script>
        $('#getDataByRole').change(function() {
            if (checkUrlParameter('role')) {
                url = updateURLParam('role', $(this).val());
            } else {
                url = updateURLParam('role', $(this).val());
            }
            fetchData(url);
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START RESET BUTTON INIT --}}
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
    {{-- END RESET BUTTON INIT --}}

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

    @include('panel.admin.include.bulk_script.index')
@endpush
