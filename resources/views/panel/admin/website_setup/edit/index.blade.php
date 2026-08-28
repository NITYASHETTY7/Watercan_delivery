@extends('layouts.main')
@section('title', $websitePage->getPrefix() . ' - ' . $label . ' ' . __('ui.edit'))
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
            ['name' => @$label, 'url' => route('panel.admin.website-pages.index'), 'class' => 'active'],
            ['name' => $websitePage->getPrefix(), 'url' => route('panel.admin.website-pages.index'), 'class' => ''],
            ['name' => __('ui.edit'), 'url' => route('panel.admin.website-pages.index'), 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.edit_title') {{ @$label ?? '--' }}</h5>
                            <span> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        @include('panel.admin.website_setup.edit.form')
    </div>
@endsection
