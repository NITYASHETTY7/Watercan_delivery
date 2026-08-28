<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <x-input type="hidden" name="group_name" value="{{ 'general_setting_verification' }}" />
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label">
            @lang('ui.email_notification') <a data-toggle="tooltip" href="javascript:void(0);" title="@lang('tooltip.email_notification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <x-checkbox class="js-switch switch-input" :arr="[]" :checked="getSetting('email_notify', @$setting) == '1'" name="email_notify" type="checkbox" id="email_notify" value="1" />
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('ui.sms_notification') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('tooltip.sms_notification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <x-checkbox class="js-switch switch-input" :arr="[]" :checked="getSetting('sms_notify', @$setting) == '1'" name="sms_notify" type="checkbox" id="sms_notify" value="1" />
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('ui.site_notification') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('tooltip.on_site_notifications')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <x-checkbox class="js-switch switch-input" :arr="[]" :checked="getSetting('notification', @$setting) == '1'" name="notification" type="checkbox" id="notification" value="1" />
        </div>
    </div>

    <hr>

    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('ui.email_verification') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('tooltip.email_verification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <x-checkbox class="js-switch switch-input" :arr="[]" :checked="getSetting('email_verify', $setting ?? null) == '1'" name="email_verify" type="checkbox" id="email_verify" value="1" />
        </div>
    </div>
    <div class="form-group row">
        <label for="exampleInputUsername2" class="col-sm-9 col-form-label"> @lang('ui.sms_verification') <a data-toggle="tooltip"
                href="javascript:void(0);" title="@lang('tooltip.sms_verification')"><i
                    class="ik ik-help-circle text-muted ml-1"></i></a>
            <br>
        </label>
        <div class="col-sm-3">
            <x-checkbox class="js-switch switch-input" :arr="[]" :checked="getSetting('sms_verify', $setting ?? null) == '1'" name="sms_verify" type="checkbox" id="sms_verify" value="1" />
        </div>
    </div>

    <div class="card-footer text-right">
        <x-button type="submit" class="btn btn-primary mr-2"> @lang('ui.save_update') </x-button>
    </div>
</form>
