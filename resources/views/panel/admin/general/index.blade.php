@extends('layouts.main')
@section('title', @$label)
@section('content')
    @php
        $breadcrumb_arr = [
            [
                'name' => @$label,
                'url' => 'javascript:void(0);',
                'class' => '',
                'url' => 'javascript:void(0);',
                'class' => 'active',
            ],
        ];
    @endphp

    @push('head')
        <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/datedropper/datedropper.min.css') }}">
        <style>
            .radio-toolbar-cus {
                margin: 10px;
            }

            .radio-toolbar-cus input[type="radio"] {
                opacity: 0;
                position: fixed;
                width: 0;
            }

            .radio-toolbar-cus label {
                display: inline-block;
                background-color: #ddd;
                margin-top: 0;
                padding: 6px 12px;
                font-family: sans-serif, Arial;
                font-size: 14px;
                border: 2px solid rgb(255, 255, 255);
                border-radius: 4px;
            }

            .radio-toolbar-cus label:hover {
                background-color: rgb(194, 192, 192);
            }

            .radio-toolbar-cus input[type="radio"]:focus+label {
                border: 2px #444;
                background: #444;
            }

            .radio-toolbar-cus input[type="radio"]:checked+label {
                background-color: rgb(64, 153, 255);
                color: #ffffff;
                border: #444;
            }

            .croppie-container .cr-boundary {
                width: 300px;
                height: 300px;
                margin: auto;
                overflow: hidden;
                position: relative;
            }

            .center {
                position: absolute;
                left: 50%;
                transform: translate(-50%, -50%);
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
                            <h5> @lang(@$label) </h5>
                            <span> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                    @include('panel.admin.modal.sitemodal.index', [
                        'title' => 'How to use',
                        'content' =>
                            'You need to create a unique code and call the unique code with paragraph content helper.',
                    ])
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card card-484">
                    <div role="tabpanel">
                        <div class="card-header border-0">
                            <ul class="nav nav-tabs" role="tablist">
                                @if (getSetting('toggling_general_activation', @$master_setting))
                                    @if ($master_permissions->contains('general_setting_view_rp'))
                                        <li class="nav-item">
                                            <a href="#general" data-active="general"
                                                class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'general') || !request()->has('active')) active @endif"
                                                aria-controls="general" role="tab" data-toggle="tab"> @lang('ui.general')
                                            </a>
                                        </li>
                                    @endif
                                @endif
                                <li class="nav-item">
                                    <a href="#control-details" data-active="control-details"
                                        class="nav-link active-swicher @if ((request()->has('active') && request()->get('active') == 'control-details') || !request()->has('active')) active @endif"
                                        aria-controls="control-details" role="tab" data-toggle="tab"> @lang('ui.control_detail')
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body pt-0">
                            <div class="tab-content">
                                @if (getSetting('toggling_general_activation', @$master_setting))
                                    @if ($master_permissions->contains('general_setting_view_rp'))
                                        <div role="tabpanel"
                                            class="tab-pane fade @if ((request()->has('active') && request()->get('active') == 'general') || !request()->has('active')) show active @endif pt-3"
                                            id="general" aria-labelledby="general-tab">
                                            @include('panel.admin.general.include.general_tab.index')
                                        </div>
                                    @endif
                                @endif
                                <div role="tabpanel"
                                    class="tab-pane fade @if ((request()->has('active') && request()->get('active') == 'control-details') || !request()->has('active')) show active @endif pt-3"
                                    id="control-details" aria-labelledby="control-details-tab">
                                    @include('panel.admin.general.include.control_details.index')
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset($master_root_directory . 'plugins/datedropper/datedropper.min.js') }}"></script>
    <script src="{{ asset($master_root_directory . 'plugins/select2/dist/js/select2.min.js') }}"></script>
    <script src="{{ asset($master_root_directory . 'plugins/clipboard/clipboard.min.js') }}"></script>
    <script src="{{ asset($master_root_directory . 'plugins/datedropper/croppie.min.js') }}"></script>
    {{-- START AJAX FORM INIT --}}
    
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                window.location.href = redirectUrl;
            }
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $('.active-swicher').on('click', function() {
            var active = $(this).attr('data-active');
            updateURL('active', active);
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
