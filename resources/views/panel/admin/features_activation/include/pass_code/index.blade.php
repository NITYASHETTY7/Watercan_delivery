<style>
    #accessCodeInput::placeholder {
        font-size: 14px;
        color: #999;
        letter-spacing: 1px;
    }

    .mt-15 {
        margin-top: 5rem !important;
    }

    .custom-font-size {
        font-size: 25px !important;
        letter-spacing: 10px;
        padding: 5px;
    }
</style>
<div class="modal fade" id="DelegateAccessModel" tabindex="-1" aria-labelledby="DelegateAccessModelLabel"
    aria-hidden="true">
    <div class="modal-dialog mt-15">
        <div class="modal-content">
            <div class="modal-header bg-custom">
                <h5 class="modal-title" id="DelegateAccessModelLabel">@lang('ui.access_step')</h5>
                <x-button type="button" class="close text-dark" data-bs-dismiss="modal">&times;</x-button>
            </div>
            <div class="modal-body">
                <form id="accessCodeForm" action="{{ route('panel.admin.setting.features-activation') }}"
                    method="GET">
                    <h5>
                        <strong><span class="delegateUserName"></span>@lang('ui.features_activation')</strong>
                    </h5>
                    <div class="form-group">
                        <x-input type="hidden" value="" name="user_id" class="delegateUserId" validation="empty" />
                        <x-label for="accessCodeInput" validation="common_code" tooltip="" name="six_digit" />
                        <x-input type="number" value="" class="form-control text-center custom-font-size" id="accessCodeInput" placeholder="{{ __('ui.enter_passcode') }}" name="delegate_access" required validation="empty" />
                        <div class="mt-2">
                            <x-button class="btn btn-primary d-block w-50 mx-auto text-center btn-sm mt-1"
                                type="submit">{{ __('ui.access_activation') }}</x-button>
                        </div>
                        <div id="errorMessage" class="text-danger mt-2" style="display: none;">
                            @lang('ui.invalid_passcode')
                        </div>
                    </div>
                </form>
                <hr>
                <div>
                    <div class="text-muted text-center" style="">
                        <i class="ik ik-info text-success"></i>
                        @lang('ui.security_message')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
