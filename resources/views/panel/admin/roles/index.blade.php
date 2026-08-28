@extends('layouts.main')
@section('title', @$label)
@section('content')
    <!-- push external head elements to head -->
    @push('head')
        <style>
            .li-position {
                min-width: 7rem;
                width: 8rem;
                transform: translate3d(-48px, 19px, 0px) !important;
            }

            .role-scrollable {
                max-height: 300px;
                overflow-y: scroll;
                overflow-x: hidden;
                border: 1px solid #ddd;
                padding: 10px;
                scrollbar-width: thin;
                scrollbar-color: #888 #f1f1f1;
            }

            /* Chrome, Edge, Safari ke liye */
            .role-scrollable::-webkit-scrollbar {
                width: 8px;
            }

            .role-scrollable::-webkit-scrollbar-track {
                background: #f1f1f1;
            }

            .role-scrollable::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }

            .role-scrollable::-webkit-scrollbar-thumb:hover {
                background: #555;
            }


            .admn-roles {
                padding: 1px 0 12px 15px;
                background-color: rgb(250 250 250);
            }

            .bg-transparent {
                background: transparent !important;
                border: none !important;
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
                            <h5>{{ __(@$label ?? '') }}</h5>
                            <span> @lang('ui.define_roles_of_user') </span>
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
                                <a href="javacript:void(0);">{{ __(@$label ?? '') }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <!-- start message area-->
            @include('panel.admin.include.message')
            <!-- end message area-->

            @if ($master_permissions->contains('role_create_rp'))
                <div class="col-md-12">
                    <div class="card mb-2">
                        <form class="forms-sample" method="POST" action="{{ route('panel.admin.roles.store') }}">
                            <div class="card-header d-flex justify-content-between">

                                <h3 class="p-0 m-0"> @lang('ui.add') {{ __(@$label ?? '') }} </h3>
                                <div class="form-group text-right p-0 m-0 ajax-btn">
                                    <x-button type="submit" class="btn btn-primary">
                                        @lang('ui.permissions_title')
                                    </x-button>
                                </div>
                            </div>
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <x-label name="role_name" validation="common_name" tooltip="add_role_name" />
                                            <x-input name="role" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text" tooltip="add_role_name" regex="text" validation="common_name" value="{{ old('role') }}" />
                                        </div>
                                        <div class="form-group">
                                            <x-label name="display_name" validation="common_name" tooltip="add_role_display_name" />
                                            <x-input name="display_name" placeholder="{{ __('ui.display_name') }}" type="text" tooltip="add_role_display_name" regex="text" validation="common_name" value="{{ old('display_name') }}" />
                                        </div>

                                        <div class="form-group">
                                            <x-label name="description" validation="common_description" tooltip="add_role_description" />
                                            <x-textarea regex="text" validation="common_description" value="{{ old('description') }}" name="description" id="description" placeholder="{{ __('ui.enter') . ' ' . __('ui.description') }}" />
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6> @lang('ui.assign_permissions') </h6>
                                            </div>
                                            <div>
                                                <label class="custom-control custom-checkbox">
                                                    <x-checkbox type="checkbox" class="custom-control-input allPermissionChecked" name="" value="" validation="empty" />
                                                    <span class="custom-control-label">@lang('ui.select_all')</span>
                                                </label>
                                            </div>
                                        </div>
                                        <hr class="mb-0 mt-0">
                                        <div class="row mb-0 role-scrollable">
                                            @foreach (@$groups as $group)
                                                <div class="col-sm-5 admn-roles">
                                                    <div class="mt-3 mb-0">
                                                        <label for=""
                                                            class="fw-600 m-0 f-18">{{ __(@$group->group ?? '') }}</label>
                                                    </div>
                                                    @foreach (\App\Models\Permission::whereGroup($group->group)->get() as $key => $permission)
                                                        <label class="custom-control mb-0 custom-checkbox">
                                                            <x-checkbox type="checkbox"
                                                                class="custom-control-input permission_checkbox"
                                                                id="item_checkbox" name="permissions[]"
                                                                value="{{ @$permission->name ?? '' }}"
                                                                validation="empty" />
                                                            <span class="custom-control-label">
                                                                {{ formatDisplayName(@$permission->name) ?? '' }}
                                                            </span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card p-3">
                    <div class="card-header">
                        <h3>{{ __(@$label) }}</h3>
                    </div>
                    <div class="card-body">
                        @foreach (@$roles as $role)
                            <div class="d-flex">
                                @if (@$role->name != 'Super Admin')
                                    <div class="dropdown">
                                        <x-button class="dropdown-toggle p-0 border-0 bg-tranparents" type="button"
                                            id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></x-button>
                                        <ul class="dropdown-menu multi-level li-position" role="menu"
                                            aria-labelledby="dropdownMenu">
                                            @if ($master_permissions->contains('role_edit_rp'))
                                                <a href="{{ route('panel.admin.roles.edit', secureToken(@$role->id ?: '')) }}"
                                                    title="Edit Role" class="btn btn-sm">
                                                    <li class="dropdown-item p-0 fw-400"><i class="ik ik-edit">
                                                        </i> @lang('ui.edit')</li>
                                                </a>
                                            @endif
                                            <hr class="m-1 b-0">
                                            @if (env('DEV_MODE') == 1)
                                                <li class="dropdown-item p-0"><a
                                                        href="{{ route('panel.admin.roles.destroy', @$role->id ?: '') }}"
                                                        title="Delete Role"
                                                        class="btn btn-sm text-danger delete-item text-danger fw-700"><i
                                                            class="ik ik-trash mr-2"> </i>@lang('ui.delete')</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                                <h6 class="ml-2 mr-1">
                                    {{ @$role->display_name ?? '' }} |
                                </h6>
                                <p class="text-muted">{{ @$role->description ?? '' }}</p>
                            </div>
                            @if (@$role->display_name == 'Super Admin')
                                <span class="badge badge-success m-1"> @lang('ui.all_permissions') </span>
                            @else
                                @foreach (@$role->permissions()->get() as $item)
                                    <span class="badge badge-dark m-1"
                                        title="{{ $item->name }}">{{ formatDisplayName(@$item->name) ?? '--' }}</span>
                                @endforeach
                            @endif
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- push external js -->
    @push('script')
        {{-- START JS HELPERS INIT --}}
        <script src="{{ asset($master_root_directory . 'plugins/select2/dist/js/select2.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                var searchable = [];
                var selectable = [];
            });

            $(document).on('click', '.allPermissionChecked', function() {
                if ($(this).prop("checked") == true) {
                    $('.permission_checkbox').prop('checked', true);
                } else {
                    $('.permission_checkbox').prop('checked', false);
                }
            });
        </script>
        {{-- END JS HELPERS INIT --}}
    @endpush
@endsection
