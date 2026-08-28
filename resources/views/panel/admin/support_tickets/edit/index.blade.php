@extends('layouts.main')
@section('title', $supportTicket->getPrefix() . ' - ' . $label . ' ' . __('ui.edit'))
@section('content')
    @php
        @$breadcrumb_arr = [
            [
                'name' => __('ui.left_sidebar_support_ticket'),
                'url' => route('panel.admin.support-tickets.index'),
                'class' => '',
            ],
            ['name' => $supportTicket->getPrefix(), 'url' => route('panel.admin.support-tickets.index'), 'class' => ''],
            ['name' => 'Edit', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    @push('head')
        <style>
            .form-group1 {
                font-size: 13px;
                /* padding: 10px 15px; */
                height: 383px;
                width: 100%;
            }

            textarea.form-control {
                height: 255px;
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
                            <h5>{{ __('ui.edit') }} {{ __('ui.left_sidebar_support_ticket') ?? '' }}</h5>
                            <span>{{ __('ui.update_record') }}
                                {{ __('ui.left_sidebar_support_ticket') ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @include('panel.admin.support_tickets.edit.form')
    </div>
@endsection
