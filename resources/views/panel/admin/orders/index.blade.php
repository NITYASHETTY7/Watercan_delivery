@extends('layouts.main')
@section('title', @$label)
@section('content')

    @push('head')

    <style>

        .export-only {
            display: none;
        }
        div.side-slide {
            z-index: 10 !important;
        }
    </style>
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

        <x-input type="hidden" id="full-name" value="{{ auth()->user()->full_name }}"
                                            validation="empty" name="" />


        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ @$label ?? '' }}</h3>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.orders.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.orders.includes.filter')
@endsection

@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
        $(document).ready(function() {
            getUsers();
    
            
        });
    </script>

    {{-- END SELECT 2 BUTTON INIT --}}

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

    {{-- START GETUSERS INIT --}}
    <script>
        $(document).ready(function() {
            getUsers();
        });
    </script>
    {{-- END GETUSERS INIT --}}

    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const moduleName = "{{ @$label ?? 'Branch' }}";
          
            exportTableToExcel({
                tableSelector : "#table",
                type: "xlsx",
                moduleName: moduleName,
                fullName: document.getElementById('full-name')?.value || "Unknown User",
                appName: "{{ env('APP_NAME') }}", 
                report_format: [
                    { label: "Date Range", value: dateRange },
                    { label: "Report Name", value: moduleName },
                    { label: "Company", value: "{{ env('APP_NAME') }}" }
                ]
            });
        });
    </script>

    @include('panel.admin.include.bulk_script.index')
@endpush
