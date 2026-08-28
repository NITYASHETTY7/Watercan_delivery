@extends('layouts.main')
@section('title', @$label)
@section('content')
    @php
        $breadcrumb_arr = [['name' => $label, 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
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
                            <h5>{{ __($label) }}</h5>
                            <span> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">

                    <div>
                        @include('panel.admin.include.breadcrumb.index')
                    </div>
                </div>
                @include('panel.admin.modal.sitemodal.index', [
                    'title' => __('ui.how_to_use'),
                    'content' => __('ui.you_need_create_unique_code'),
                ])
            </div>
        </div>

        <div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between" style="margin-top: -8px;">
                            <h3 class="mb-0"> @lang('ui.all_pages') </h3>
                            <div class="d-flex justify-content-right">
                                <div class="d-flex justicy-content-right mt-2 ml-1">
                                    @if ($master_permissions->contains('page_create_rp'))
                                        <a href="{{ route('panel.admin.website-pages.create') }}"
                                            class="btn btn-sm btn-outline-primary mr-2" title="Add New Pages"><i
                                                class="fa fa-plus" aria-hidden="true"></i> @lang('ui.add')
                                        </a>
                                    @endif
                                </div>
                                {{-- @if ($master_permissions->contains('page_bulk_rp'))
                                    @if (getSetting('toggling_pages_activation_bulk_delete', @$master_setting))
                                        <form action="{{ route('panel.admin.website-pages.bulk-action') }}" method="POST"
                                            id="bulkAction" class="d-flex mr-2">
                                            @csrf
                                            <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                                            <x-input type="hidden" id="full-name" value="{{ auth()->user()->full_name }}"
                                                name="" validation="empty" />
                                            <div>

                                                <x-button style="background: display: block; margin-top: 9px !important;"
                                                    class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                                    type="button" id="dropdownMenu1" data-toggle="dropdown"
                                                    aria-haspopup="true" aria-expanded="false"><i
                                                        class="ik ik-more-vertical fa-lg pl-1"></i></x-button>
                                                <ul class="dropdown-menu multi-level" role="menu"
                                                    aria-labelledby="dropdownMenu">
                                                    @if (getSetting('toggling_pages_activation_bulk_delete', @$master_setting))
                                                        <x-button type="submit"
                                                            class="dropdown-item bulk-action text-danger fw-700"
                                                            data-value=""
                                                            data-message="You want to delete these Website Pages?"
                                                            data-action="delete" data-callback="bulkDeleteCallback"> <i
                                                                class="ik ik-trash"> </i> @lang('ui.bulk_delete')
                                                        </x-button>

                                                        <hr class="m-1">

                                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                            data-value="0" data-status="Unpublish"
                                                            data-column="is_published"
                                                            data-message="You want to mark these Website Page as Unpublish?"
                                                            data-action="columnUpdate"
                                                            data-callback="bulkColumnUpdateCallback">@lang('ui.mark_as_unpublish')
                                                        </a>

                                                        <a href="javascript:void(0)" class="dropdown-item bulk-action"
                                                            data-value="1" data-status="Publish" data-column="is_published"
                                                            data-message="You want to mark these Website Page as Publish?"
                                                            data-action="columnUpdate"
                                                            data-callback="bulkColumnUpdateCallback">@lang('ui.mark_as_publish')
                                                        </a>
                                                    @endif

                                                </ul>
                                            </div>
                                        </form>
                                    @endif
                                @endif --}}
                            </div>
                        </div>
                        <form action="{{ route('panel.admin.website-pages.index') }}" method="GET" id="TableForm"
                            action="">
                            <div id="ajax-container" style="display: none;">
                                @include('panel.admin.website_setup.load')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if (getSetting('toggling_pages_activation_table_filter', @$master_setting))
        @include('panel.admin.website_setup.include.filter')
    @endif
@endsection

@push('script')
    @include('panel.admin.include.bulk_script.index')
    {{-- START HTML TO EXCEL INIT --}}
    <script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>
    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const moduleName = "{{ @$label ?? 'Website Setup' }}";
            exportTableToExcel({
                tableSelector : "#page_table",
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

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            fetchData("{{ route('panel.admin.website-pages.index') }}");
            window.history.pushState("", "", "{{ route('panel.admin.website-pages.index') }}");
            $('#TableForm').trigger("reset");
            $(document).find('.close.off-canvas').trigger('click');

            $('#from_date').val('');
            $('#to_date').val('');

        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}
@endpush
