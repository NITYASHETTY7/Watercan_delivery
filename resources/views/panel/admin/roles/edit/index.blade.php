@extends('layouts.main')
@section('title', @$role->display_name . ' - ' . __('ui.edit_roles'))
@section('content')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-award bg-blue"></i>
                        <div class="d-inline">
                            <h5>@lang('ui.edit') {{ @$label }}</h5>
                            <span>@lang('Edit role & associate permissions') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="#">{{ __(@$label) }}</a>
                            </li>
                            <li class="breadcrumb-item">
                                {{ @$role->display_name ?? '--' }}
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
            <div class="col-md-12">
                <div class="card">
                    @include('panel.admin.roles.edit.form')
                </div>
            </div>
        </div>
    </div>
@endsection
