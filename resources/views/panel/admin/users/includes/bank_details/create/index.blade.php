<div class="modal fade" id="bankDetailsModalCenter" tabindex="-1" role="dialog"
    aria-labelledby="bankDetailsModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankDetailsModalCenterLabel">@lang('ui.add_bank_detail')</h5>
                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></x-button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.user-banks.store') }}" method="post" class="ajaxForm">
                    @csrf
                    <x-input type="hidden" id="user_id" name="user_id" value="{{ secureToken(@$user->id) }}" />
                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="create" />
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">
                                <x-label name="account_holder_name" validation="common_name" tooltip="" />
                                <x-input name="account_holder_name"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.account_holder_name') }}"
                                    type="text" tooltip="" regex="name" validation="common_name"
                                    value="{{ old('account_holder_name') }}" />
                                <x-message name="account_holder_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto">
                            <div class="form-group {{ @$errors->has('bank_name') ? 'has-error' : '' }}">
                                <x-label name="bank" validation="common_name" tooltip="" />
                                <div>
                                    <x-select name="bank_id" validation="required" id="bank_id"
                                        class="form-control select2" label="{{ __('ui.bank') }}" :value="$user->bank_id ?? old('bank_name')"
                                        optionName="label" :arr="\App\Models\UserBank::BANK_NAMES" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('account_no') ? 'has-error' : '' }}">
                                <x-label name="account_no" validation="bank_account_number" tooltip="" />
                                <x-input name="account_number" id="numberInput" placeholder="{{ __('ui.account_no') }}"
                                    type="number" tooltip="" regex="account_number"
                                    validation="bank_account_number" value="{{ old('account_no') }}" />
                            </div>
                            <div class="form-group {{ @$errors->has('ifsc_code') ? 'has-error' : '' }}">
                                <x-label name="ifsc_code" validation="bank_ifsc_code" tooltip="" />
                                <x-input name="bank_ifsc_code"
                                    placeholder="{{ __('ui.enter') }} {{ __('ui.ifsc_code') }}" type="text"
                                    tooltip="" regex="alpha_numeric" validation="bank_ifsc_code"
                                    value="{{ old('ifsc_code') }}" />
                            </div>
                            <div class="form-group {{ @$errors->has('branch') ? 'has-error' : '' }}">
                                <x-label name="branch" validation="common_name" tooltip="" />
                                <x-input name="branch" placeholder="{{ __('ui.enter') }} {{ __('ui.branch') }}"
                                    type="text" tooltip="" regex="text" validation="common_name"
                                    value="{{ old('branch') }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            @php
                                $types = [['value' => 0, 'name' => 'current'], ['value' => 1, 'name' => 'saving']];
                            @endphp
                        </div>
                        <div class="col-md-12 row">
                            <div class="col-6">
                                <x-label name="account_type" validation="empty" tooltip="" />
                                <x-radio checked name="account_type" type="radio" valueName="id" value="current"
                                    :arr="@$types" validation="empty" />
                                <x-message name="type" :message="@$message" validation="empty" />


                            </div>
                        </div>
                        <div class="col-12 form-group text-right">
                            <x-button type="submit" class="btn btn-primary ajax-btn">@lang('ui.create')</x-button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
