<div class="side-slide side-slide-custom" style="right: -100%">
    <div class="filter">
        <div class="card-header d-flex justify-content-between align-items-end">
            <h5 class="mt-3 mb-0">@lang('ui.filter')</h5>
            <x-button type="button" class="close off-canvas mt-2 mb-0" data-type="close">
                <span aria-hidden="true"><i class="ik ik-x fs-20"></i></span>
            </x-button>
        </div>
        <div class="card-body">
            <form action="{{ route('panel.admin.support-tickets.index') }}" class="d-flex" method="GET"
                id="TableForm">
                <x-input type="hidden" name="ids" id="bulk_ids" value="" validation="empty" />
                <div class="row">
                    <div class="form-group col-12">
                        <x-label name="from_date" validation="empty" tooltip="" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('from') }}" class="form-control" name="from" id="from" />
                    </div>
                    <div class="form-group col-12">
                        <x-label name="to_date" validation="empty" />
                        <x-date regex="date" max="{{ now()->format('Y-m-d') }}" validation="empty" type="date"
                            value="{{ request()->get('to') }}" class="form-control" name="to" id="to" />
                    </div>
                    {{-- <div class="col-12 form-group">

                        <x-label name="subject" validation="empty" tooltip="add_support_ticket_subject" />
                        <x-select name="subject" value="{{ request()->get('subject') }}" valueName=""
                            validation="empty" id="subject" class="" label="Subject" optionName="name"
                            :arr="App\Models\SupportTicket::SUBJECTS" />
                    </div> --}}

                    <div class="col-12 form-group">
                        <x-label name="customer_type" validation="empty" tooltip="" />
                        <x-select name="customer_type" validation="empty" id="customer_type" class="form-control"
                            value="{{ request()->get('customer_type') }}" label="Customer Type" option_name="label"
                            :arr="@$accountTypes" />
                    </div>

                    <div class="col-12 form-group">
                        <x-label name="role" validation="empty" tooltip="" />
                        <x-select name="role" validation="empty" id="role" class="form-control"
                            value="{{ request()->get('role') }}" valueName="display_name" label="Role" option_name="display_name"
                            :arr="@$roles" />
                    </div>



                    <div class="col-12 form-group">
                        <x-label name="status" validation="empty" tooltip="" />
                        <x-select name="status" validation="empty" id="status" class="form-control"
                            value="{{ request()->get('status') }}" label="Status" option_name="label"
                            :arr="@$statuses" />
                    </div>


                    <div class="col-12">
                        <x-button type="submit" class="btn btn-primary">@lang('ui.apply') @lang('ui.filter')</x-button>
                        <a href="javascript:void(0);" id="reset" type="button"
                            class="btn btn-light ml-2">@lang('ui.reset')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
