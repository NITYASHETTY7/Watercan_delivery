<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div>

                <label for="length" class="d-flex align-items-center">
                    @lang('ui.show')
                    <x-select id="length" name="length" class="form-control mx-2" :arr="tableLimits()"
                        value="{{ @request()->length ?? '10' }}" label="" optionName="option" valueName="option"
                        validation="empty" />
                    @lang('ui.entries')
                </label>

            </div>
            <div>
                @if (getSetting('toggling_permission_table_excel_export', @$master_setting))
                    <x-button type="button" id="export_button" class="btn btn-light btn-sm ml-2"> @lang('ui.btn_excel')
                    </x-button>
                @endif
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('toggling_permission_table_search', @$master_setting))
                    <x-input name="search" placeholder="{{ __('ui.left_sidebar_search') }}" type="text"
                        tooltip="" regex="text" validation="common_name" value="{{ request()->get('search') }}" />
                @endif

            </div>
        </div>

    </div>
    <div class="table-responsive">
        @include('panel.admin.permissions.table')
    </div>
    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-6 d-flex justify-content-start">
                <div class="pagination mobile-justify-center">
                    {{ $allPermissions->appends(request()->except('page'))->links() ?? '' }}
                </div>
            </div>
            <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
                @if ($allPermissions->lastPage() ?? '' > 1)
                    <div class="d-flex align-items-center" for="jumpTo">
                         <div class="mr-2 jumpTo">
                            @lang('ui.jump_to') :
                        </div>
                        <div class="d-flex align-items-center">
                            <x-input type="number" class="form-control" id="jumpTo" name="page"
                                value="{{ $allPermissions->currentPage() ?? '' }}" validation="empty" />
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $allPermissions->lastPage() ?? '' }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
