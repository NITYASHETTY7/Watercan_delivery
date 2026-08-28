<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <x-input type="hidden" name="group_name" value="{{ 'general_setting_currency' }}" />
    <div class="form-group row">
        <x-label for="exampleInputUsername2" class="col-sm-3 col-form-label" name="select_currency" validation="required" tooltip="" />
        <div class="col-sm-9">
            <x-select name="app_currency" validation="required|currency" id="currency" class="form-control select2" label="Currency" :value="old('app_currency', getSetting('app_currency', @$master_setting))" :arr="config('currencies')" valueName="symbol" optionValueKey="name" />

        </div>
    </div>
    <div class="form-group d-flex m-0">
        <x-label for="thousand_separator" class="col-sm-3 pl-0 col-form-label" name="decimals" validation="required" tooltip="currency_no_of_decimal" />
        <x-select name="no_of_decimal" value="{{ getSetting('no_of_decimal', @$master_setting) }}" label=" {{ __('ui.decimals') }}" optionName="label" valueName="label" :arr="$noOfDecimal" id="no_of_decimal" />

    </div>
    <div class="form-group d-flex m-0">
        <x-label for="decimal_separator" class="col-sm-3 pl-0 col-form-label" name="decimal_separator" validation="empty" tooltip="currency_decimal_separator" />
        <div class="col-sm-9 m-0 pl-1 col-form-label">
            @php
                $radio_arr = [['name' => 'dot', 'value' => 1], ['name' => 'comma', 'value' => 2]];
            @endphp
            <x-radio name="decimal_separator" value="{{ getSetting('decimal_separator', @$master_setting) }}" class="custom-radio" :arr="$radio_arr" validation="empty" tooltip="" data-custom="example" />
        </div>
    </div>
    <div class="form-group d-flex m-0">
        <x-label for="currency_positon" class="col-sm-3 pl-0 col-form-label" name="currency_position" validation="empty" tooltip="currency_position" />
        <div class="col-sm-9 m-0 pl-1 col-form-label">

            @php
                $radio_arr = [['name' => '$1,100.00', 'value' => 1], ['name' => '1,100$', 'value' => 2]];
            @endphp

            <x-radio name="currency_position" value="{{ getSetting('currency_position', @$master_setting) }}" class="fw-700" :arr="$radio_arr" validation="empty" tooltip="" data-custom="example" />

        </div>
    </div>
    <div class="card-footer text-right">
        <x-button type="submit" class="btn btn-primary mr-2 ajax-btn">@lang('ui.save_update') </x-button>
    </div>
</form>
