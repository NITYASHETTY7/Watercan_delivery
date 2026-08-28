<style>
    .select2-container {
        width: 100% !important;
    }
</style>
<div class="side-slide" style="right: -100%;">
    <div class="filter">
        <div class="card-header d-flex justify-content-between align-items-end">
            <h5 class="mt-3 mb-0"> @lang('ui.filter') </h5>
            <x-button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true"><i class="ik ik-x fs-20"></i></span>
            </x-button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.zone-pincodes.index') }}" method="GET" class="d-flex" id="TableForm">
                <input type="hidden" name="branch_id" value="{{ request()->get('branch_id') }}">
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="from_date" tooltip="" validation="empty" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('from') }}" class="form-control" name="from" id="from" />
                    </div>
                    <div class="form-group col-12">
                        <x-label name="to_date" tooltip="" validation="empty" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('to') }}" class="form-control" name="to" id="to" />
                    </div>
                    <div class="col-12">
                        <x-button type="submit" class="btn btn-primary">@lang('ui.apply') @lang('ui.filter') </x-button>
                        <a href="javascript:void(0);" id="reset" type="button"
                            class="btn btn-light ml-2">@lang('ui.reset')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
