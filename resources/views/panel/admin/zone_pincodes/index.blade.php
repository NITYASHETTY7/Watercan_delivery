@extends('layouts.main')
@section('title', @$label)
@section('content')

    @push('head')
        {{-- INITIALIZE SHIMMER & INIT LOAD --}}
        <script>
            window.onload = function() {
                $('#ajax-container').show();
                fetchData("{!! getCurrentUrlWithParams() !!}");
            };
        </script>
        {{-- END INITIALIZE SHIMMER & INIT LOAD --}}
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$label ?? '' }}</h5>
                            <span> @lang('ui.list_of') {{ @$label ?? '' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('panel.admin.dashboard.index') }}"><i class="ik ik-home"></i></a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('panel.admin.branches.index') }}">Branches</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="{{ route('panel.admin.zones.index', ['branch_id' => secureToken($zone->branch_id)]) }}">Zones</a>
                            </li>
                            <li class="breadcrumb-item active">
                                <a href="#">{{ @$label ?? '' }}</a>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="row">
            @include('panel.admin.include.message')
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3>{{ @$label ?? '' }}</h3>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('panel.admin.zone-pincodes.create', ['zone_id' => request()->get('zone_id')]) }}" class="btn btn-sm btn-outline-primary mr-2" title="Add New Branch"><i class="fa fa-plus" aria-hidden="true"></i> @lang('ui.add') </a>
                            <form action="{{ route('panel.admin.zone-pincodes.bulk-action') }}" method="POST"
                                id="bulkAction" class="">
                                @csrf
                                <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                                <div>
                                    <x-button class="dropdown-toggle p-0 custom-dopdown bulk-btn btn btn-light"
                                        type="button" id="dropdownMenu1" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false"><i
                                            class="ik ik-more-vertical fa-lg pl-1"></i></x-button>
                                    <ul class="dropdown-menu multi-level" role="menu"
                                        aria-labelledby="dropdownMenu">

                                        <x-button type="submit"
                                            class="dropdown-item bulk-action text-danger fw-700"
                                            data-value="" data-message="You want to delete these Branches?"
                                            data-action="delete" data-callback="bulkDeleteCallback"><i
                                                class="ik ik-trash"> </i>
                                            @lang('ui.bulk_delete')
                                        </x-button>
                                    </ul>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="ajax-container" style="display: none;">
                            @include('panel.admin.zone_pincodes.load')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('panel.admin.zone_pincodes.includes.filter')
@endsection

@push('script')
    {{-- START SELECT 2 BUTTON INIT --}}
    <script>
        $('.select2').select2();
    </script>
    {{-- END SELECT 2 BUTTON INIT --}}

    {{-- START RESET BUTTON INIT --}}
    <script>
        $('#reset').click(function() {
            var currentUrl = '{{ url()->full() }}';
            fetchData(currentUrl);
            window.history.pushState("", "", currentUrl);
            $('#TableForm')[0].reset();
            $('.select2').val('').trigger('change');
            $(document).find('.close.off-canvas').trigger('click');
        });
    </script>
    {{-- END RESET BUTTON INIT --}}

    @include('panel.admin.include.bulk_script.index')
@endpush
