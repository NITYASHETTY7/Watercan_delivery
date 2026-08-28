<div class="modal fade" id="editBankDetailsModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> @lang('ui.update_bank_detail') </h5>

                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></x-button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.user-banks.update') }}" method="post" class="ajaxForm">
                    <x-input name="id" id="payoutdetailId" placeholder="" type="hidden" tooltip=""
                        regex="" validation="empty" value="" />
                    <x-input name="user_id" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="" />
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">

                                <x-label name="account_holder_name" validation="common_name" tooltip="" />
                                <x-input name="account_holder_name" id="editaccount_holder_name"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.account_holder_name') }}"
                                    type="text" tooltip="" regex="name" validation="common_name"
                                    value="" />
                                <x-message name="account_holder_name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-12 mx-auto">
                            <div class="form-group {{ @$errors->has('bank_name') ? 'has-error' : '' }}">
                                <x-label name="bank_name" validation="required" tooltip="" />
                                <div>
                                    <x-select name="bank_id" validation="required" id="edit_bank" class="form-control"
                                        label="{{ __('ui.bank') }}" :value="$user->bank_id ?? old('bank_name')" optionName="label"
                                        :arr="\App\Models\UserBank::BANK_NAMES" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group {{ @$errors->has('account_no') ? 'has-error' : '' }}">
                                <x-label name="account_no" validation="bank_account_number" tooltip="" />
                                <x-input name="account_number" id="editaccount_no"
                                    placeholder="{{ __('ui.account_no') }}" type="number" tooltip=""
                                    regex="account_number" validation="bank_account_number" value="" />
                            </div>
                            <div class="form-group {{ @$errors->has('ifsc_code') ? 'has-error' : '' }}">
                                <x-label name="ifsc_code" validation="bank_ifsc_code" tooltip="" />
                                <x-input name="bank_ifsc_code" id="editifsc_code" placeholder="Enter ifsc code "
                                    type="text" tooltip="" regex="alpha_numeric" validation="bank_ifsc_code"
                                    value="" />
                            </div>
                            <div class="form-group {{ @$errors->has('branch') ? 'has-error' : '' }}">
                                <x-label name="branch" validation="common_name" tooltip="" />
                                <x-input name="branch" id="editbranch" placeholder="{{ __('ui.branch') }}"
                                    type="text" tooltip="" regex="text" validation="common_name"
                                    value="" />
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
                                <x-radio checked name="account_type" type="radio" valueName="value"
                                    :arr="@$types" validation="empty" :selected="old('type', @$user->type)" />
                                <x-message name="account_type" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-12 form-group text-right">
                            <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.update')
                            </x-button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
