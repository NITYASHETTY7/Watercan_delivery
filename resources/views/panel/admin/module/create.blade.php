@extends('layouts.main')
@section('title', 'New Module Add')
@section('content')
    @php
        $breadcrumb_arr = [['name' => ' ', 'url' => 'javascript:void(0);', 'class' => '']];
    @endphp

    @push('head')
        <style>
            .btn:focus {
                box-shadow: none;
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
                            <h5> @lang('ui.create_new_module') </h5>
                            <span> @lang('ui.new_record_crud') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-12">
                <form action="" class="ajaxForm">
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('ui.settings') </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-4 col-md-4">
                                    <div class="form-group">
                                        <x-label name="module_name" tooltip="" validation="module_name" class="" />
                                        <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." class="form-control" name="module_name" value="" validation="empty" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <x-label name="model_name" tooltip="" validation="common_name" class="" />
                                        <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." class="form-control" name="model_name" value="" validation="empty" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <x-label name="menu_title" tooltip="" validation="common_name" class="" />
                                        <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." class="form-control" name="menu_icon" value="" validation="empty" />
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <x-label name="parent_menu" tooltip="" validation="common_name" class="" />
                                        <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." class="form-control" name="parent_menu" value="" validation="empty" />
                                        <small class="text-muted">@lang('ui.for_admin_panel_menu_only')</small>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-3">
                                    <div class="form-group">
                                        <x-label name="roles" tooltip="" validation="common_name" class="" />
                                        <x-select name="role" value="{{ old('role') }}" label=" {{ __('ui.role') }}" optionName="label" valueName="label" :arr="$roles" id="role" validation="empty" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 col-md-12">
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.admin_crud')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.user_crud')</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-12 my-2">
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.soft_deletes')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.generate_api_crud')</span>
                                    </label>
                                </div>
                                <div class="col-12 col-md-12">
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.create_form')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.edit_form')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.show_page')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.delete_action')</span>
                                    </label>
                                    <label class="form-check-label">
                                        <x-input name="" type="checkbox" checked="" value="" validation="empty" /><span class="checkbox-label">@lang('ui.multi_delete_action')</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('ui.field')</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="">&nbsp;</th>
                                            <th>@lang('ui.field_type')</th>
                                            <th>@lang('ui.database_column')</th>
                                            <th>@lang('ui.visual_title')</th>
                                            <th>@lang('ui.in_list')</th>
                                            <th>@lang('ui.in_create')</th>
                                            <th>@lang('ui.in_edit')</th>
                                            <th>@lang('ui.in_show')</th>
                                            <th>@lang('ui.is_sortable')</th>
                                            <th>@lang('ui.required')</th>
                                            <th>@lang('ui.edit')</th>
                                            <th>@lang('ui.delete')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td></td>
                                            <td>@lang('ui.auto_increment') </td>
                                            <td>@lang('ui.id')</td>
                                            <td>@lang('ui.id')</td>
                                            <td><i class="changeicon fa fa-check text-success fa-2x"
                                                    data-field-status="1"></i></td>
                                            <td></td>
                                            <td></td>
                                            <td><x-button class="btn bg-white fw-600 changeicon" type="button"
                                                    data-field-status="1"><i
                                                        class="fa fa-check text-success fa-2x"></i></x-button></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>@lang('ui.date_time')</td>
                                            <td>@lang('ui.created_at')</td>
                                            <td>@lang('ui.created_at')</td>
                                            <td><x-button class="btn bg-white fw-700 changeicon" data-field-status="0"
                                                    type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></x-button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td><x-button class="btn bg-white fw-600 changeicon" data-field-status="0"
                                                    type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></x-button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td>@lang('ui.auto_increment')</td>
                                            <td>@lang('ui.id')</td>
                                            <td>@lang('ui.id')</td>
                                            <td><x-button class="btn bg-white fw-700 changeicon" type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></x-button></td>
                                            <td></td>
                                            <td></td>
                                            <td><x-button class="btn bg-white fw-600 changeicon" data-field-status="0"
                                                    type="button"><i
                                                        class="fa fa-times text-danger fa-2x"></i></x-button>
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <x-button data-toggle="modal" data-target="#createModule" class="btn btn-info btn-block"
                                type="button"><i class="fa fa-plus"></i>@lang('ui.add_new_field')</x-button>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h3> @lang('ui.table') </h3>
                        </div>
                        <div class="card-body">

                        </div>
                    </div>
                    <div class="">
                        <a class="btn btn-default float-right" href="">@lang('ui.cancel')</a>
                        <x-button class="btn btn-primary float-right ajax-btn" type="submit">Save</x-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <!-- push external js -->
    @push('script')
        <script>
            $(document).on('click', '.changeicon', function() {
                $(this).parent()
                    .toggleClass('on')
                    .toggleClass('off');
            });
        </script>
        {{-- START AJAX FORM INIT --}}
        <script>
            $('.ajaxForm').on('submit', function(e) {
                e.preventDefault();
                var route = $(this).attr('action');
                var method = $(this).attr('method');
                var data = new FormData(this);
                var redirectUrl = "{{ url('panel/admin/module') }}";
                var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
            });
        </script>
        {{-- END AJAX FORM INIT --}}
    @endpush
@endsection
