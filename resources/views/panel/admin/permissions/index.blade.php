@extends('layouts.main')
@section('title', __('ui.left_sidebar_permissions'))
@section('content')
    @php
        @$breadcrumb_arr = [
            ['name' => __('ui.left_sidebar_permissions'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
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
                            <h5> @lang('ui.permissions') </h5>
                            <span> @lang('ui.permission_subtitle') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('panel.admin.include.message')
            <!-- end message area-->
            @if (env('DEV_MODE') == 1)
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>@lang('ui.create') @lang('ui.permission') </h3>
                        </div>
                        <div class="card-body">
                            <form class="forms-sample" method="POST" action="{{ route('panel.admin.permissions.store') }}">
                                @csrf
                                <x-input name="create" placeholder="Enter Name" type="hidden" tooltip="" regex="text" validation="common_name" value="request_with" />
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <x-label name="permission" validation="common_name" tooltip="add_permission_name" />
                                            <x-input name="permission" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text" tooltip="add_permission_name" regex="" validation="common_name" value="{{ old('permission') }}" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            @php
                                                $user_arr = App\Models\User::whereRoleIs('User')
                                                    ->orderBy('first_name', 'ASC')
                                                    ->get();
                                            @endphp
                                            <x-label name="assign_to_role" validation="empty" tooltip="add_permission_roles" />
                                            <x-select name="roles[]" validation="empty" id="roles"
                                                class="form-control select2" valueName="id"
                                                value="{{ old('id', @$role->id) }}" label="Roles"
                                                option_name="display_name" :arr="@$roles" :isMultiple="1" />
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <x-label name="group" validation="common_name" tooltip="add_permission_group" />
                                            <x-input name="group" placeholder="{{ __('ui.enter_permission_group_name') }}" type="text" tooltip="add_permission_group" regex="name" validation="common_name" value="{{ old('group') }}" />

                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <x-button type="submit" class="btn btn-primary btn-rounded ajax-btn">
                                                @lang('ui.create_permission')
                                            </x-button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

            <div @if (env('DEV_MODE') != 1) class="col-md-12" @else class="col-md-8" @endif>
                <div class="card">
                    <div id="ajax-container" style="display: none;">
                        @include('panel.admin.permissions.load')
                    </div>
                </div>
            </div>
            {{-- @endif --}}
        </div>
        <div class="row">

        </div>
    </div>

@endsection
@push('script')
    {{-- START HTML TO EXCEL BUTTON INIT --}}
    <script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>
    <script>
        $(document).on('click', '#export_button', function () {
            const urlParams = new URLSearchParams(window.location.search);
            const from = urlParams.get('from') || 'N/A';
            const to = urlParams.get('to') || 'N/A';
            const dateRange = `${from} - ${to}`;
            const moduleName = "{{ @$label ?? 'Permissions' }}";
            exportTableToExcel({
                tableSelector : "#permissions_table",
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

    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            $(document).find('#roles').select2();
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
