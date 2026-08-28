@extends('layouts.main')
@section('title', __('ui.notifications'))
@section('content')
    @php
        $breadcrumb_arr = [['name' => __('ui.notifications'), 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
    {{-- INITIALIZE SHIMMER & INIT LOAD --}}
    <script>
        window.onload = function() {
            $('#ajax-container').show();
            fetchData("{!! getCurrentUrlWithParams() !!}");
        };
    </script>
    {{-- END INITIALIZE SHIMMER & INIT LOAD --}}
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.notifications') </h5>
                            <span> @lang('ui.list_of_notification') </span>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3> @lang('ui.notifications') </h3>
                        <div class="d-flex justify-content-between">
                            <button id="deleteAllNotifications" class="btn btn-light text-dark btn-sm mr-3"">Delete
                                All</button>

                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.notifications.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.notifications.include.filter')

@endsection

@push('script')
    {{-- START HTML TO EXCEL INIT --}}
    <script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>
    <script>
        $('#deleteAllNotifications').click(function() {
            if (confirm('Are you sure you want to delete all notifications?')) {
                $.post("{{ route('panel.admin.notifications.delete-all') }}", {
                    _token: "{{ csrf_token() }}"
                }, function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert('Error: ' + response.message);
                    }
                });
            }
        });
    </script>
    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.notifications.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.notifications.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}
    {{-- START HTML TO EXCEL BUTTON INIT --}}
    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const moduleName = "{{ @$label ?? 'Notification' }}";
            exportTableToExcel({
                tableSelector : "#notification_table",
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
    {{-- END HTML TO EXCEL BUTTON INIT --}}
@endpush
