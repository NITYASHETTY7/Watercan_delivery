@extends('layouts.main')
@section('title', __('ui.user_sessions'))
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

    @php
        /**
         * User Subscription
         *
         * @category ZStarter
         *
         * @ref zCURD
         * @author  Book My Water <info@watercane.come>
         * @license https://watercane-dev.dze-labs.in Book My Water
         * @version <zStarter: 1.1.0>
         * @link    https://watercane-dev.dze-labs.in
         */
        @$breadcrumb_arr = [
            ['name' => __('ui.user'), 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => $user->getPrefix(), 'url' => route('panel.admin.users.index'), 'class' => ''],
            ['name' => __('ui.sessions'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ $user->full_name ?? '' }}</h5>
                            <span>@lang('ui.sessions_list')</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>@lang('ui.sessions')</h3>
                        <div class="d-flex justify-content-right">
                            <form action="{{ route('panel.admin.users.session.bulk-action') }}" method="POST"
                                id="bulkAction" class="">
                                @csrf
                                <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                                <div>
                                    <x-button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></x-button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <x-button type="submit" class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these session?"
                                            data-action="delete" data-callback="bulkDeleteCallback"><i class="ik ik-globe">
                                            </i> @lang('ui.bulk_logout')
                                        </x-button>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="ajax-container" style="display: none;">
                        @include('panel.admin.session.load')
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.session.include.filter')

@endsection
@push('script')
    @include('panel.admin.include.bulk_script.index')
    <script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>
    {{-- END HTML TO EXCEL FILE INIT --}}
    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const moduleName = "{{ @$label ?? 'Sessions' }}";
            exportTableToExcel({
                tableSelector : "#table",
                type: "xlsx",
                moduleName: moduleName,
                fullName: "{{ auth()->check() ? str_replace(' ', '-', auth()->user()->full_name) : 'Unknown User' }}",
                appName: "{{ env('APP_NAME') }}", // Injected by Laravel Blade
                report_format: [
                    { label: "Date Range", value: dateRange },
                    { label: "Report Name", value: moduleName },
                    { label: "Company", value: "{{ env('APP_NAME') }}" }
                ]
            });
        });
    </script>
    {{-- END HTML TO EXCEL FILE INIT --}}


    {{-- START RESET BUTTON INIT --}}

    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.users.sessions', $user->id) }}");
            window.history.pushState("", "", "{{ route('panel.admin.users.sessions', $user->id) }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START GETUSERS INIT --}}
    <script>
        $(document).ready(function() {
            getUsers();
        })
    </script>
    {{-- END GETUSERS INIT --}}
@endpush
