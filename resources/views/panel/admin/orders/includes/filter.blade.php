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
            <form action="{{ route('panel.admin.orders.index') }}" method="GET" class="d-flex" id="TableForm">
                <input type="hidden" name="type" value="{{ request()->get('type') }}">
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
                     <div class="col-12 form-group mr-2 align-items-center">
                        <x-label name="customer_type" validation="empty" tooltip="" />
                        <x-select name="customer_type" value="{{ request()->get('customer_type') }}" label="Customer Type"
                            validation="empty" optionName="label" id="customer_type" :arr="$accountTypes" />
                    </div>
                    <div class="col-12 form-group mr-2 align-items-center">
                        <x-label name="status" validation="empty" tooltip="" />
                        <x-select name="status" value="{{ request()->get('status') }}" label="Status"
                            validation="empty" optionName="label" id="status" :arr="$statuses" />
                    </div>
                    <div class="col-12 form-group mr-2 align-items-center">
                        <x-label name="payment_status" validation="empty" tooltip="" />
                        <x-select name="payment_status" value="{{ request()->get('payment_status') }}" label="Payment Status"
                            validation="empty" optionName="label" id="payment_status" :arr="$paymentStatuses" />
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="assign_to" validation="empty" />
                            <x-select name="user_id" validation="empty" id="user" class="form-control getUsersList user_id" label="driver" value="{{ old('user_id', $zonePincodeUser->user_id ?? '') }}" optionName="name" />
                            <x-message name="driver" :message="@$message" validation="empty" />
                        </div>
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
