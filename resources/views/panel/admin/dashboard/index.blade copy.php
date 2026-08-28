@extends('layouts.main')
@section('title', __('ui.left_sidebar_dashboard'))
@section('content')

    @php
        $breadcrumb_arr = [
            ['name' => __('ui.left_sidebar_dashboard'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];  
    @endphp

    @push('head')
        <style>
            .ticket-card {
                margin-bottom: 20px;
            }

            .bg-color {
                background-color: #fff;
            }

            /* blinking light */
            .blinking-light {
                width: 6px;
                height: 6px;
                background-color: #EB525D;
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
                animation: blink 3s infinite;
                margin-top: 7px;
            }

            @keyframes blink {

                0%,
                50%,
                100% {
                    opacity: 1;
                }

                25%,
                75% {
                    opacity: 0;
                }
            }

            .blink-light-effect {
                display: flex;
                gap: 9px;
            }

            .d-footer {
                padding: 5px 20px !important;
                padding-left: 270px !important;
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
                            <h5>{{ getGreetingBasedOnTime() }}</h5>
                        </div>
                    </div>
                    <span>
                        @lang('ui.namaste') <span
                            class="text-dark dashboard-fullname fw-700">{{ auth()->user()->full_name }}</span>
                    </span>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>

        <div class="shimmer-content">
            @include('panel.admin.dashboard.includes.shimmer.index')
        </div>

        <div class="row dashboard-content d-none">
            <div class="col-lg-12 col-sm-12">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="statistic-header">
                            <h5>User Management</h5>
                        </div>
                    </div>
                </div>
                <div class="statistics-grid">
                    <div class="row">
                        @foreach ($roles as $key => $role)
                            <div class="col-md-4 col-12"> 
                                <a class=""  href="{{ route('panel.admin.users.index', ['role' => $role->name]) }}">
                                    <div class="card m-0">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between">
                                                <div class="state">
                                                    <h3 class="text-secondary">
                                                        {{ getOtherUsersCountByRole($role->name ?? '') }}
                                                    </h3>
                                                  <h6 class="card-subtitle text-dark fw-700 mb-0">
                                                    {{ $role->display_name }}
                                                </h6>
                                                </div>
                                                <div class="col-auto icon-size">
                                                    <i class="ik ik-users text-muted f-12 btn btn-light btn-icon p-2"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-sm-12">
                <div class="row clearfix">
                    <div class="col-md-12">
                        <div class="statistic-header">
                            <h5>@lang('ui.order_management')</h5>
                        </div>
                    </div>
                </div>
                <div class="statistics-grid">
                    @foreach (\App\Models\Order::STATUSES as $key => $order)
                        <a class="" href="{{ route('panel.admin.orders.index', ['status' => $key]) }}">
                            <div class="card m-0">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="state">
                                            <h3 class="text-secondary">
                                                {{ getOrderStatusCount($key) }}
                                            </h3>
                                            <h6 class="card-subtitle text-dark fw-700 mb-0 d-flex">
                                                {{ isset($order['label']) ? $order['label'] : '' }}
                                                <div class="ml-2">
                                                    @if ($key == 0)
                                                        <div class="blinking-light"></div>
                                                    @endif
                                                </div>
                                            </h6>
                                        </div>
                                        <div class="col-auto icon-size">
                                            <i
                                                class="{{ isset($order['icon']) ? $order['icon'] : '' }} text-muted f-12 btn btn-light btn-icon p-2"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            $('.dashboard-content').removeClass('d-none');
            $('.shimmer-content').addClass('d-none');
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
