<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('toggling_manage_item_table_record_limit', @$master_setting))
                    <label for="length" class="d-flex align-items-center">
                        @lang('ui.show')
                        <x-select id="length" name="length" class="form-control mx-2" :arr="tableLimits()"
                            value="{{ @request()->length ?? '10' }}" label="" optionName="option" valueName="option"
                            validation="empty" />
                        @lang('ui.entries')
                    </label>
                @endif
            </div>
            <div>
                @if (getSetting('toggling_manage_item_table_excel_export', @$master_setting))
                    <x-button type="button" id="export_button" class="btn btn-light btn-sm ml-2"> @lang('ui.btn_excel')
                    </x-button>
                @endif
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <x-input name="search" placeholder="{{ __('ui.left_sidebar_search') }}" type="text" tooltip=""
                    regex="" validation="empty" value="{{ request()->get('search') }}" />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        @include('panel.admin.session.table')
    </div>

    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-6 d-flex justify-content-start">
                <div class="pagination mobile-justify-center">
                    {{ @$sessions->appends(request()->except('page'))->links() ?? '' }}
                </div>
            </div>
            <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
                @if (@$sessions->lastPage() ?? '' > 1)
                    <div class="d-flex align-items-center" for="jumpTo">
                         <div class="mr-2 jumpTo">
                            @lang('ui.jump_to') :
                        </div>
                        <div class="d-flex align-items-center">
                            <x-input type="number" class="form-control" id="jumpTo" name="page"
                                value="{{ @$sessions->currentPage() ?? '' }}" validation="empty" />
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ @$sessions->lastPage() ?? '' }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
