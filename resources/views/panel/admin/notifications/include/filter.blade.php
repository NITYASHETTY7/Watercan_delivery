<style>
    .side-slide-custom {
        right: -100% !important;
    }
</style>
<div class="side-slide side-slide-custom">
    <div class="filter">
        <div class="card-header d-flex justify-content-between align-items-end">
            <h5 class="text-white mt-3 mb-0">@lang('ui.filter')</h5>
            <x-button type="button" class="close off-canvas text-white" data-type="close">
                <span aria-hidden="true"><i class="ik ik-x fs-20"></i></span>
            </x-button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.notifications.index') }}"method="GET" id="TableForm" class="d-flex">
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="from_date" validation="empty" tooltip="" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('from') }}" class="form-control" name="from_date" id="from_date" />
                    </div>
                    <div class="form-group col-12">
                        <x-label name="to_date" validation="empty" tooltip="" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('to') }}" class="form-control" name="to" id="to_date" />
                    </div>
                    <div class="col-12">
                        <x-button type="submit" class="btn btn-primary">@lang('ui.apply') @lang('ui.filter') </x-button>
                        <a href="javascript:void(0);" id="reset" type="button" class="btn btn-light ml-2">
                            @lang('ui.reset') </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
