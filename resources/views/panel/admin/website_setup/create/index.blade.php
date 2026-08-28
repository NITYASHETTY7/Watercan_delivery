@extends('layouts.main')
@section('title', @$label . ' ' . __('ui.create'))
@section('content')
    @push('head')
        <style>
            .bootstrap-tagsinput {
                width: 100%;
            }
        </style>
    @endpush
    @php
        $breadcrumb_arr = [
            ['name' => @$label, 'url' => route('panel.admin.website-pages.index'), 'class' => ''],
            [
                'name' => __('ui.add') . ' ' . @$label,
                'url' => route('panel.admin.website-pages.index'),
                'class' => 'active',
            ],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.create') {{ @$label ?? '--' }}</h5>
                            <span> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        @include('panel.admin.website_setup.create.form')
    </div>
    {{-- @include('panel.admin.website_setup.include.legal_modal.index') --}}
@endsection
