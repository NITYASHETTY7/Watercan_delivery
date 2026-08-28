<style>
    .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>
<div class="modal fade" id="editAddressModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"> @lang('ui.update_address') </h5>
                <x-button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></x-button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.addresses.update') }}" method="post" class="ajaxFrom">
                    <x-input name="id" id="addressId" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="" />
                    <x-input name="user_id" id="user_id" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="{{ @$user->id }}" />
                    @csrf
                    <div class="row">
                        <div class="col-md-12 row mx-auto">
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                    <x-label name="full_name" validation="common_name" tooltip="" />
                                    <x-input name="name" id="editName"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.full_name') }}" type="text"
                                        tooltip="" regex="name" validation="common_name" value="" />
                                    <x-message name="name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('phone') ? 'has-error' : '' }}">
                                    <x-label name="phone" validation="common_phone_number" tooltip="" />
                                    <div class="input-group">
                                        <x-input type="hidden" id="editAddressCountryCodeInput" name="country_code"
                                            value="" validation="empty" />
                                        <x-input type="tel" class="form-control w-21" id="editAddressCountryCode"
                                            name="phone" value="" validation="empty" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_1') ? 'has-error' : '' }}">
                                    <x-label name="address_1" validation="common_address" tooltip="" />
                                    <x-input name="address_1"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" id="editAddress"
                                        type="text" tooltip="" regex="address" validation="common_address"
                                        value="{{ old('address') }}" />
                                    <x-message name="address_1" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_2') ? 'has-error' : '' }}">
                                    <x-label name="address_2" validation="empty" tooltip="" />
                                    <x-input name="address_2"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" id="editAddress_2"
                                        type="text" tooltip="" regex="address" validation="empty"
                                        value="{{ old('address') }}" />
                                    <x-message name="address_2" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('country_id') ? 'has-error' : '' }}">
                                    <x-label name="country" validation="required" tooltip="" />
                                    <x-select name="country_id" id="countryEdit"
                                        value="{{ old('country_id', $userAddress->details['country_id'] ?? '') }}"
                                        label="Country" optionName="name" valueName="id" class="select2"
                                        :arr="\App\Models\Country::all()" validation="required" />
                                    <div class="invalid-feedback">
                                        Please select a valid country.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('state_id') ? 'has-error' : '' }}">
                                    <x-label name="state" validation="required" tooltip="" />
                                    <x-select name="state_id" id="stateEdit"
                                        value="{{ old('state_id', $userAddress->details['state_id'] ?? '') }}"
                                        label="State" optionName="name" valueName="id" class="select2"
                                        :arr="[]" validation="required" />
                                    <div class="invalid-feedback">
                                        Please provide a valid state.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('city_id') ? 'has-error' : '' }}">
                                    <x-label name="city" validation="required" tooltip="" />
                                    <x-select name="city_id" id="cityEdit"
                                        value="{{ old('city_id', $userAddress->details['city_id'] ?? '') }}"
                                        label="City" optionName="name" valueName="id" class="select2"
                                        :arr="[]" validation="required" />
                                    <div class="invalid-feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('pincode') ? 'has-error' : '' }}">
                                    <x-label name="pincode" validation="common_pin_code" tooltip="" />
                                    <x-input name="pincode"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.pincode') }}" id="pincode_id"
                                        type="text" tooltip="" regex="pin_code" validation="common_pin_code"
                                        value="{{ old('pin_code') }}" />
                                    <x-message name="pin_code" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('type') ? 'has-error' : '' }}">
                                    @php
                                        $type = [['value' => 0, 'name' => 'home'], ['value' => 1, 'name' => 'office']];
                                    @endphp
                                </div>
                            </div>
                            <div class="col-md-12 row" style="margin-top: -15px">
                                <div class="col-6">
                                    <x-label name="address_type" validation="common_name" tooltip="" />
                                    <x-radio checked name="type" type="radio" valueName="id" value=""
                                        :arr="@$type" validation="empty" />
                                    <x-message name="type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group text-right">
                                    <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.update')
                                    </x-button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
