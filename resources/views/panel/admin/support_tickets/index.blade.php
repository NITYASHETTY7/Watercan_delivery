@extends('layouts.main')
@section('title', __('ui.left_sidebar_support_tickets'))
@section('content')

    @php
        @$breadcrumb_arr = [
            [
                'name' => __('ui.left_sidebar_support_tickets'),
                'url' => 'javascript:void(0);',
                'class' => 'active',
            ],
        ];
    @endphp
    @push('head')
        <style>
            .select2-selection.select2-selection--single {
                width: 100px !important;
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
                            <h5>{{ __('ui.left_sidebar_support_tickets') ?? '' }}</h5>
                            <span> @lang('ui.list_of') {{ __('ui.left_sidebar_support_tickets') ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ __('ui.left_sidebar_support_tickets') ?? '' }}</h3>
                        <div class="d-flex justify-content-between">
                            {{-- @if ($master_permissions->contains('ticket_create_rp'))
                                <a href="{{ route('panel.admin.support-tickets.create') }}"
                                    class="btn btn-sm btn-outline-primary mr-2" title="Add new Support Ticket"><i
                                        class="fa fa-plus" aria-hidden="true"></i> @lang('ui.add')
                                </a>
                            @endif --}}
                            @if ($master_permissions->contains('ticket_bulk_rp'))
                                @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting) ||
                                        getSetting('toggling_support_ticket_bulk_delete', @$master_setting))
                                    <form action="{{ route('panel.admin.support-tickets.bulk-delete') }}" method="POST"
                                        id="bulkAction">
                                        @csrf
                                        <x-input name="ids" id="bulk_ids" placeholder="" type="hidden" tooltip="" regex="" validation="empty" :value="null" />
                                        <x-input type="hidden" id="full-name" value="{{ auth()->user()->full_name }}"
                                            validation="empty" name="" />

                                        <x-button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                            type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></x-button>
                                        <ul class="dropdown-menu multi-level mr-30" role="menu" aria-labelledby="dropdownMenu">
                                            @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting))
                                                <a href="javascript:void(0);" class="dropdown-item text-primary fw-700"
                                                    data-toggle="modal" data-target="#BulkStoreAgentModal"><i
                                                        class="ik ik-upload"></i>
                                                    @lang('ui.bulk_upload')</a>
                                            @endif
                                            @if (getSetting('toggling_support_ticket_bulk_delete', @$master_setting))
                                                <hr class="m-1">
                                                <x-button type="submit"
                                                    class="dropdown-item bulk-action text-danger fw-700" data-value=""
                                                    data-message="You want to delete these Support Tickets?"
                                                    data-action="delete" data-callback="bulkDeleteCallback"><i
                                                        class="ik ik-trash"> </i> @lang('ui.bulk_delete')
                                                </x-button>
                                            @endif
                                        </ul>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div id="ajax-container" style="display:none;">
                        @include('panel.admin.support_tickets.load')
                    </div>
                </div>

            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal" id="RaiseTicketModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('ui.new_ticket') </h5>
                    <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </x-button>
                </div>
                <form action="{{ route('panel.admin.support-tickets.reply') }}" method="post">
                    @csrf
                    <x-input name="request_with" id="reply" placeholder="" type="hidden" tooltip="" regex="" validation="empty" :value="null" />
                    <x-input name="id" id="Id" placeholder="" type="hidden" tooltip="" regex="" validation="empty" :value="null" />
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="@lang('ui.reply')">@lang('ui.your_reply'):</label>
                            <x-textarea name="reply" :placeholder="__('ui.please_enter_your_reply')" rows="7" cols="30" :value="null" validation="empty" />
                        </div>
                        <div class="modal-footer">
                            <x-button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> @lang('ui.close')
                            </x-button>
                            <x-button type="submit" class="btn btn-primary">@lang('ui.send')</x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- @if (getSetting('toggling_support_ticket_table_filter', @$master_setting)) --}}
        @include('panel.admin.support_tickets.include.filter')
    {{-- @endif --}}

    @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting))
        @include('panel.admin.support_tickets.include.bulk_upload.index')
    @endif

@endsection
@push('script')
    @include('panel.admin.include.bulk_script.index')
    <script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>

    {{-- START JS HELPERS INIT --}}
    <script>
        $('.reply').click(function() {
            $('#Id').val($(this).data('id'));
            $('#RaiseTicketModal').modal('show');
        })
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START HTML TO EXCEL FILE INIT --}}
    <script>
        
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const status = urlParams.get('status') || 'All Status';
            const statuses = @json(App\Models\SupportTicket::STATUSES);
            const statusLabel = statuses[status]?.label || 'All Status';
            const moduleName = "{{ @$label ?? 'Support Tickets' }}";
            exportTableToExcel({
                tableSelector : "#table",
                type: "xlsx",
                moduleName: moduleName,
                fullName: "{{ auth()->check() ? str_replace(' ', '-', auth()->user()->full_name) : 'Unknown User' }}",
                appName: "{{ env('APP_NAME') }}", // Injected by Laravel Blade
                report_format: [
                    { label: "Status", value: statusLabel },
                    { label: "Date Range", value: dateRange },
                    { label: "Report Name", value: moduleName },
                    { label: "Company", value: "{{ env('APP_NAME') }}" }
                ]
            });
        });
    </script>
    {{-- END HTML TO EXCEL FILE INIT --}}

    {{-- START UPDATE STATUS INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.support-tickets.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.support-tickets.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');
            $('.select2').val('').trigger('change');
            $('#from').val('');
            $('#to').val('');
        });
    </script>
    {{-- END UPDATE STATUS INIT --}}
@endpush
