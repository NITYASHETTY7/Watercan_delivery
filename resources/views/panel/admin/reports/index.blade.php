@extends('layouts.main')
@section('title', $label)

@section('content')

    @push('head')
        <style>
            .table tbody td {
                padding: 0px 8px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ $label }}</h5>
                            <span></span>
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
                                <a href="#">{{ $label ?? '' }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div id="ajax-container">
            @include('panel.admin.reports.load')
        </div>
    </div>
    @include('panel.admin.reports.includes.filter')
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- START RESET BUTTON INIT --}}
    <script>
        $(document).on('click', '#reset', function() {
            // fetchData(currentUrl);
            var url = "{{ route('panel.admin.reports.index') }}";
            window.location.href = url;
        });
    </script>
    {{-- END RESET BUTTON INIT --}}
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>
    <script>
        function html_table_to_excel(type) {
            var table_core = $("#order_table").clone();
            var clonedTable = $("#order_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();

            clonedTable.find('.status-stepper').each(function() {
                var statusText = $(this).data('status-text');
                $(this).text(statusText);
            });

            $("#order_table").html(clonedTable.html());

            // Export logic
            var data = document.getElementById('order_table');
            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });
            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });
            XLSX.writeFile(file, 'OrderReport.' + type);
            $("#order_table").html(table_core.html());
        }

        $(document).on('click', '#export_button', function() {
            html_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
@endpush
