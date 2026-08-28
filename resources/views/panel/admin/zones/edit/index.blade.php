@extends('layouts.main')
@section('title', @$zone->getPrefix() . ' - ' . __('ui.edit') . (isset($label) ? ' ' . $label : ''))
@section('content')

    @php
        @$breadcrumb_arr = [
            ['name' => $label, 'url' => route('panel.admin.zones.index', ['branch_id' => secureToken($zone->branch_id)]), 'class' => '--'],
            ['name' => @$zone->getPrefix(), 'url' => route('panel.admin.zones.index', ['branch_id' => secureToken($zone->branch_id)]), 'class' => '--'],
            ['name' => __('ui.edit'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.edit') @lang('ui.zone') </h5>
                            <span> @lang('ui.update_record') {{ $label }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')

                </div>
            </div>
        </div>
        @include('panel.admin.zones.edit.form')
    </div>
@endsection
