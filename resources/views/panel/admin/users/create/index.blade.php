@extends('layouts.main')
@section('title', @$label . ' ' . __('ui.create'))
@section('content')

    @push('style')
        <style>
            .input-group {
                position: relative;
                display: flex;
                align-items: center;
            }

            .input-group input {
                width: 100%;
                padding-right: 40px;
                /* Adjust based on icon size */
            }

            .input-group .input-group-text {
                position: absolute;
                right: 10px;
                /* Adjust based on design */
                cursor: pointer;
                background: none;
                border: none;
                margin-top: -25px;
                margin-right: -10px;
            }

            @media (min-width: 992px) {
                .container-fluid-height {
                    height: 83vh;
                }
            }

            .iti--inline-dropdown .iti__dropdown-content {
                z-index: 9 !important;
            }
        </style>
    @endpush

    <div class="container-fluid container-fluid-height">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.create') {{ @$label ?? '' }}</h5>
                            <span> @lang('ui.create_record') {{ @$label }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.users.index',['role' => request()->role ]) }}">{{ @$label ?? '' }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#"> @lang('ui.create')</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @include('panel.admin.users.create.form')
    </div>
@endsection
