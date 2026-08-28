@extends('layouts.main')
@section('title', @$label . ' ' . __('ui.create'))
@section('content')
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
                                <a href="{{ route('panel.admin.zones.index',['branch_id' => request()->branch_id ]) }}">{{ @$label ?? '' }}</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#"> @lang('ui.create')</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
        @include('panel.admin.zones.create.form')
    </div>
@endsection
