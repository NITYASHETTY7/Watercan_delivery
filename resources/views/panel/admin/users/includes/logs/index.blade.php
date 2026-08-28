@extends('layouts.main')
@section('title', 'User Log')
@section('content')
    @php
        @$breadcrumb_arr = [
            ['name' => 'Administrator', 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => 'User Log', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

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
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3> @lang('ui.user_log') </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user_log_table" class="table">
                                <thead>
                                    <tr>
                                        <th class="text-center">#</th>
                                        <th> @lang('ui.user_name') </th>
                                        <th> @lang('ui.ip_address') </th>
                                        <th> @lang('ui.activity') </th>
                                        <th> @lang('ui.browser') </th>
                                        <th> @lang('ui.platform') </th>
                                        <th> @lang('ui.created_at') </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (@$user_log as $item)
                                        <tr>
                                            <td class="text-center">{{ @$loop->iteration ?? '--' }}</td>
                                            <td>{{ @$item->user_id->full_name ?? '--' }}</td>
                                            <td>{{ @$item->ip_address ?? '--' }}</td>
                                            <td>{{ @$item->activity ?? '--' }}</td>
                                            <td>{{ @$item->name ?? '--' }}</td>
                                            <td>{{ @$item->platform ?? '--' }}</td>
                                            <td>{{ getFormattedDateTime(@$item->created_at ?? '--') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        {{-- START JS HELPERS INIT --}}
        <script>
            $('#getDataByRole').on('change', function() {
                var val = $(this).val();
                var route = "{{ url('/admin/user-log/') }}";
                window.location.href = route + '/' + val;
            });
        </script>
        {{-- END JS HELPERS INIT --}}

        {{-- START JS HELPERS INIT --}}
        <script>
            $(document).ready(function() {
                var table = $('#user_log_table').DataTable({
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
                        },
                        'colvis',
                        {
                            extend: 'print',
                            className: 'btn-sm btn-primary',
                            header: true,
                            footer: false,
                            orientation: 'landscape',
                            exportOptions: {
                                columns: ':visible',
                                stripHtml: false
                            }
                        }
                    ]
                });
            });
        </script>
        {{-- END JS HELPERS INIT --}}
    @endpush
@endsection
