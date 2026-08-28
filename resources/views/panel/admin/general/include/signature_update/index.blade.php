<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <x-input type="hidden" name="group_name" value="{{ 'general_setting' }}" />

    <div class="form-group row">
        <x-label for="InvoicePrefix" class="col-sm-3 col-form-label" name="invoice_prefix" validation="empty" tooltip="invoice_prefix" />
        <div class="col-sm-9">
            <x-input type="text" name="invoice_prefix" class="form-control" required value="{{ getSetting('invoice_prefix', $setting ?? null) }}" placeholder="Invoice Prefix" validation="empty" />
        </div>
    </div>

    <div class="form-group row">
        <x-label for="seal_signature" class="col-sm-3 col-form-label" name="seal_signature" validation="empty" tooltip="upload_signature" />
        <div class="col-sm-9">
            <input type="file" name="seal_signature" class="file-upload-default">
            <div class="input-group col-xs-12">
                <x-input type="text" class="form-control file-upload-info" disabled placeholder="Upload Seal & Signature" name="" value="" />
                <span class="input-group-append">
                    <x-button class="file-upload-browse btn btn-success" type="button">@lang('ui.upload') </x-button>
                </span>
            </div>
        </div>
        <div class="col-sm-3"> </div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('seal_signature', $setting ?? null)) }}" alt="Invoice term"
                        width="120px">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <x-label for="InvoiceTerm" class="col-sm-3 col-form-label" name="invoice_term" validation="empty" tooltip="invoice_term" />
        <div class="col-sm-9">
            <x-textarea name="invoice_term" id="invoice_term" rows="5" cols="5" class="form-control" placeholder="{{ __('ui.invoice_term') }}" :value="getSetting('invoice_term', $setting ?? null)" required />
        </div>
    </div>
    <div class="card-footer text-right">
        <x-button type="submit" class="btn btn-primary mr-2 ajax-btn">@lang('ui.save_update') </x-button>
    </div>
</form>
