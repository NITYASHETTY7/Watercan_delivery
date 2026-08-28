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
                            <h5> @lang($label) </h5>
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
                    <div class="card-header border-0">
                        <h5 class="mb-0">
                            @lang('ui.trouble_shoot')
                        </h5>
                    </div>
                    <div class="card-body pt-0">
                        @include('panel.admin.troubleshoot.include.troubleshoot_tab.index')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
