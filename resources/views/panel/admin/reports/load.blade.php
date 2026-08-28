<style>
    /* Responsive font size for date label (already good) */
    .responsive-date-label {
        font-size: 1.5rem; /* default desktop size */
        margin: 0;
    }

    @media (max-width: 768px) {
        .responsive-date-label {
            font-size: 1.2rem; /* tablets and small screens */
        }
    }

    @media (max-width: 480px) {
        .responsive-date-label {
            font-size: 1rem; /* mobile screens */
        }
    }

    /* Table responsiveness (already present) */
    .table-responsive {
        overflow-x: auto;
        white-space: nowrap;
    }

    /* Custom styles for filter form on small screens */
    @media (max-width: 576px) {
        /* Stack the form elements vertically on extra small screens */
        .card-header .d-flex.align-items-center {
            flex-direction: column;
            align-items: flex-start !important; /* Align form items to the left */
        }

        /* Make date inputs take full width of the form container */
        .card-header form > div {
            width: 100%;
            margin-bottom: 0.5rem; /* Add some space between date fields */
        }

        /* Adjust alignment for the "From" and "To" span/label */
        .card-header form > div span {
            display: inline-block;
            min-width: 30px; /* give minimum width to align inputs */
        }
        
        /* Ensure input fields within date divs take full width */
        .card-header form > div input[type="date"] {
            width: calc(100% - 40px); /* Adjust based on span width */
        }

        /* Group the buttons and add top margin to separate from dates */
        .card-header form .btn-group-responsive {
            margin-top: 0.5rem;
            width: 100%;
            display: flex;
            justify-content: flex-end; /* Push buttons to the right */
        }
    }

        @media (max-width: 768px) {
        .btn-group-responsive {
            margin-left: 0 !important;
            justify-content: flex-start !important;
        }
    }
</style>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                <h3 class="responsive-date-label mb-3">
                    {{ isset($from_date) && $from_date ? \Carbon\Carbon::parse($from_date)->format('d/m-Y') . ' - ' : '' }}
                    {{ isset($to_date) && $to_date ? \Carbon\Carbon::parse($to_date)->format('d/m-Y') : '' }}
                    {{ $label }}
                </h3>
                {{-- <form class="d-flex align-items-center mt-2 mt-sm-0" method="get">
                    <div class="d-flex align-items-center mr-2">
                        <span>From:</span>
                        <x-input name="from_date" class="mr-2 ml-md-2" type="date" required value="{{ $from_date }}" />
                    </div>
                    <div class="d-flex align-items-center">
                        <span>To:</span>
                        <x-input name="to_date" class="ml-md-2" type="date" required value="{{ $to_date }}" />
                    </div>
                    
                    <div class="btn-group-responsive d-flex ml-2">
                        <x-button type="submit" class="btn btn-light rounded-0 text-muted btn-icon px-2" title="Apply Filter">
                            <i class="ik ik-filter ik-lg"></i>
                        </x-button>
                        <a href="javascript:void(0);" title="Reset Filter" id="reset" type="button" class="btn btn-light rounded-0 text-danger btn-icon px-2 ml-2"><i class="ik ik-rotate-cw ik-lg"></i></a>
                    </div>
                </form> --}}
            </div>
            <div class="card-body" id="reportResults">
                @if($from_date && $to_date)
                    @if($orders->count() > 0)
                        <div class="table-controller mb-2">
                            <div class="d-flex justify-content-between align-items-center flex-wrap">
                                <div class="mb-2 mb-sm-0">
                                    @if (getSetting('toggling_user_management_table_record_limit', @$master_setting))
                                        <label for="length" class="d-flex align-items-center">
                                            @lang('ui.show')
                                            <x-select id="length" name="length" class="form-control mx-2" :arr="tableLimits()"
                                                value="{{ @request()->length ?? '50' }}" label="" optionName="option" valueName="option"
                                                validation="empty" />
                                            @lang('ui.entries')
                                        </label>
                                    @endif
                                </div>
                                <div class="d-flex ml-2 mb-2 mb-sm-0" style="margin-top: -10px;">
                                    <button type="button" id="export_button" class="btn btn-light btn-sm">@lang('ui.btn_excel')</button>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="">
                                    @if (getSetting('toggling_user_management_table_search', @$master_setting))
                                        <x-input name="search" placeholder="{{ __('ui.left_sidebar_search') }}" type="text" tooltip=""
                                            regex="text" validation="common_name" value="{{ request()->get('search') }}" />
                                    @endif
                                </div>
                                <x-button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon">
                                   <i class="ik ik-filter ik-lg"></i>
                               </x-button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            @include('panel.admin.reports.table')
                        </div>
                    @else
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                            <div class="text-center">
                                <i class="fa fa-info-circle text-muted fa-3x mb-3"></i>
                                <h5 class="text-muted">No data found</h5>
                                <p class="text-secondary">Please select another dates and apply filter to see the report.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                        <div class="text-center">
                            <i class="fa fa-info-circle text-muted fa-3x mb-3"></i>
                            <p class="text-secondary">Please select dates to see orders records.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>