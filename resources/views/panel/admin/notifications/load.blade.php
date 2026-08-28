<div class="table-controller mt-0">
    <div class="d-flex justify-content-between">
        <div class="mr-3">
            <label for="length" class="d-flex align-items-center">
                @lang('ui.show')
                <x-select id="length" name="length" class="form-control mx-2" :arr="tableLimits()"
                    value="{{ @request()->length ?? '10' }}" label="" optionName="option" valueName="option"
                    validation="empty" />
                @lang('ui.entries')
            </label>
        </div>
        <div>
            <x-button type="button" id="export_button" class="btn btn-light btn-sm ml-2"> @lang('ui.btn_excel') </x-button>
        </div>
    </div>
</div>
<div class="table-responsive">
    @include('panel.admin.notifications.table')
</div>


<div class="card-footer">
    <div class="row align-items-center">
        <div class="col-lg-6 d-flex justify-content-start">
            <div class="pagination mobile-justify-center">
                {{ @$notifications->appends(request()->except('page'))->links() ?? '' }}
            </div>
        </div>
        <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
            @if (@$notifications->lastPage() ?? '' > 1)
                <div class="d-flex align-items-center" for="jumpTo">
                    <div class="mr-2">
                        @lang('ui.jump_to') :
                    </div>
                    <div class="d-flex align-items-center">
                        <x-input type="number" class="form-control" id="jumpTo" name="page"
                            value="{{ @$notifications->currentPage() ?? '' }}" validation="empty" />
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ @$notifications->lastPage() ?? '' }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
