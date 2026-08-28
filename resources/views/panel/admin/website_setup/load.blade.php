
<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex">
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
        <div class="d-flex justify-content-between">
            @if (getSetting('toggling_pages_activation_table_search', @$master_setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="text"
                    validation="common_name" value="{{ request()->get('search') }}" />
            @endif

            @if (getSetting('toggling_pages_activation_table_filter', @$master_setting))
                <x-button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"> <i
                        class="ik ik-filter ik-lg"></i></x-button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        @include('panel.admin.website_setup.table')
    </div>

    <div class="card-footer">
        <div class="row align-items-center">
            <div class="col-lg-7 d-flex justify-content-start">
                <div class="pagination mobile-justify-center">
                    {{ $websitePages->appends(request()->except('page'))->links() }}
                </div>
            </div>
            <div class="col-lg-5 mobile-mt-20 d-flex justify-content-end">
                @if (@$websitePages->lastPage() ?? '' > 1)
                    <label class="d-flex align-items-center" for="jumpTo">
                        <div class="mr-2 pt-2">
                            @lang('ui.jump_to') :
                        </div>
                        <div class="d-flex align-items-center">
                            <x-input type="number" class="form-control" id="jumpTo" name="page"
                                value="{{ @$websitePages->currentPage() ?? '' }}" validation="empty" />
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ @$websitePages->lastPage() ?? '' }}</div>
                        </div>
                    </label>
                @endif
            </div>
        </div>
    </div>
</div>
