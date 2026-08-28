<style>
    .form-group .col-sm-9 {
        padding-left: 10px;
        /* Adjust as needed */
    }

    .input-group-append {
        position: absolute;
        margin-left: 690px !important;
    }
</style>

<form class="forms-sample ajaxForm updateLogoImageModal" action="{{ route('panel.admin.setting.store') }}"method="post"
    enctype="multipart/form-data">
    @csrf

    <x-input name="group_name" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'general_setting' }}" />
    <div class="form-group row negative-margin">
        <x-label for="exampleInputUsername2" class="col-sm-3 col-form-label" name="app_name" validation="empty" tooltip="app_name" />
        <div class="col-sm-9">
            <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." name="app_name" class="form-control" placeholder="Enter App Name" required value="{{ getSetting('app_name', @$master_setting) }}" validation="empty" />
        </div>
    </div>
    <div class="form-group row">
        <x-label for="exampleInputEmail2" class="col-sm-3 col-form-label" name="app_url" validation="empty" tooltip="app_url" />
        <div class="col-sm-9">
            <x-input type="url" name="app_url" class="form-control" required value="{{ getSetting('app_url', @$master_setting) }}" placeholder="App Url" validation="empty" />
        </div>
    </div>
    <div class="form-group row">
        <x-label for="logo" class="col-sm-3 col-form-label" name="app_logo" validation="empty" tooltip="app_logo" />
        <div class="col-sm-9">
            <input type="file" name="app_logo" id="app_logo" accept="image/jpg,image/png,image/jpeg"
                class="file-upload-default cropAppLogo">
            <div class="input-group col-xs-12">
                <x-input type="text" class="form-control file-upload-info"  disabled placeholder="{{ __('ui.upload') . ' ' . __('ui.logo') }}" name="" value="" />
                <span class="input-group-append" style="right: 0px !important;">
                    <x-button class="file-upload-browse btn btn-success" type="button">Choose </x-button>
                </span>
            </div>
            <div>
                <img id="logoImagePreview" class="d-none" src="#" alt="your image" />
                <div class="demoLogo"></div>
                <x-input name="toggling_croppedLogoData" id="toggling_croppedLogoData" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="" />
            </div>

        </div>
        <div class="col-sm-3"> </div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('app_logo', @$master_setting)) }}" alt="App Logo" width="120px">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <x-label for="logo" class="col-sm-3 col-form-label" name="white_app_logo" validation="empty" tooltip="app_logo" />
        <div class="col-sm-9">
            <input type="file" name="white_app_logo" id="white_app_logo" accept="image/jpg,image/png,image/jpeg"
                class="file-upload-default cropAppLogo">
            <div class="input-group col-xs-12">
                <x-input type="text" class="form-control file-upload-info"  disabled placeholder="{{ __('ui.upload') . ' ' . __('ui.logo') }}" name="" value="" />
                <span class="input-group-append" style="right: 0px !important;">
                    <x-button class="file-upload-browse btn btn-success" type="button"> Choose</x-button>
                </span>
            </div>
            <div>
                <img id="logoImagePreview" class="d-none" src="#" alt="your image" />
                <div class="demoLogo"></div>
                <x-input name="toggling_croppedLogoData" id="toggling_croppedLogoData" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="" />
            </div>

        </div>
        <div class="col-sm-3"> </div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('white_app_logo', @$master_setting)) }}" alt="App Logo" width="120px">
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row">
        <x-label for="logo" class="col-sm-3 col-form-label" name="app_favicon" validation="empty" tooltip="app_favicon" />
        <div class="col-sm-9">
            <input type="file" name="app_favicon" id="app_favicon"class="file-upload-default" accept="image/jpg,image/png,image/jpeg">
            <div class="input-group col-xs-12">
                <x-input type="text" class="form-control file-upload-info" disabled placeholder="{{ __('ui.upload') . ' ' . __('ui.favicon') }}" value="" name="" />
                <span class="input-group-append" style="right: 0px !important;">
                    <x-button class="file-upload-browse btn btn-success" type="button">Choose</x-button>
                </span>
            </div>
            <div>
                <img id="faviconImagePreview" class="d-none" src="#" alt="your image" />
                <div class="demoFavicon"></div>
                <x-input name="toggling_croppedLogoData" id="croppedFaviconData" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="" />
            </div>
        </div>
        <div class="col-sm-3"></div>
        <div class="col-sm-9">
            <div class="card m-0 p-2">
                <div class="mx-auto">
                    <img src="{{ asset(getSetting('app_favicon', @$master_setting)) }}" alt="Favicon"
                        width="40px">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group row">
        <x-label for="adroid_app_url" class="col-sm-3 col-form-label" name="adroid_app_url" validation="empty" tooltip="app_url" />
        <div class="col-sm-9">
            <x-input type="url" name="adroid_app_url" class="form-control" required value="{{ getSetting('adroid_app_url', @$master_setting) }}" placeholder="App Url" validation="empty" />
        </div>
    </div>

    <div class="form-group row">
        <x-label for="iphone_app_url" class="col-sm-3 col-form-label" name="iphone_app_url" validation="empty" tooltip="app_url" />
        <div class="col-sm-9">
            <x-input type="url" name="iphone_app_url" class="form-control" required value="{{ getSetting('iphone_app_url', @$master_setting) }}" placeholder="App Url" validation="empty" />
        </div>
    </div>

    <div class="form-group row">
        <x-label for="frontend_copyright_text" class="col-sm-3 col-form-label" name="frontend_copyright_text" validation="empty" tooltip="frontend_copyright_text" />
        <div class="col-sm-9">
            <x-input type="text" name="frontend_copyright_text" class="form-control" required value="{{ getSetting('frontend_copyright_text', @$master_setting) }}" placeholder="Enter Copyright Content" validation="empty" />
        </div>
    </div>

    
    <div class="form-group row">
        <x-label for="frontend_footer_description" class="col-sm-3 col-form-label" name="frontend_footer_description" validation="empty" tooltip="frontend_footer_description" />
        <div class="col-sm-9">
            <x-textarea type="text" name="frontend_footer_description" class="form-control" required value="{{ getSetting('frontend_footer_description', @$master_setting) }}" placeholder="Enter Footer Content" validation="empty" />
        </div>
    </div>

    <div class="card-footer text-right px-0">
        <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.save_update') </x-button>
    </div>
</form>

