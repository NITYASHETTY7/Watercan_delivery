@extends('layouts.main')
@section('title', @$user->getPrefix() . ' - ' . __('ui.edit') . ' ' . ($user->hasRole('driver') ? 'Driver' : 'User'))
@section('content')

    @php
        @$breadcrumb_arr = [
            ['name' => $user->hasRole('driver') ? 'Driver' :$label, 'url' => route('panel.admin.users.index', ['role' => UserRole($user->id)['display_name']]), 'class' => '--'],
            ['name' => @$user->getPrefix(), 'url' => route('panel.admin.users.index'), 'class' => '--'],
            ['name' => __('ui.edit'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-user-plus bg-blue"></i>
                        <div class="d-inline">
                            @if ($user->hasRole('driver'))
                                <h5> @lang('ui.edit') @lang('ui.driver')</h5>
                                <span> @lang('ui.update_record') @lang('ui.driver')</span>
                            @else
                                <h5> @lang('ui.edit') @lang('ui.user') </h5>
                                <span> @lang('ui.update_record') {{ $label }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')

                </div>
            </div>
        </div>
        @include('panel.admin.users.edit.form')
    </div>
@endsection
