<style>
    .iti--inline-dropdown .iti__dropdown-content {
        z-index: 9 !important;
    }
</style>
<div class="modal fade" id="addressModalCenter" role="dialog" aria-labelledby="addressModalCenterLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addressModalCenterLabel"> @lang('ui.add_address') </h5>
                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></x-button>
            </div>
            <div class="modal-body">
                <form action="{{ route('panel.admin.addresses.store') }}" method="post">
                    @csrf

                    <x-input name="user_id" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="{{ secureToken(@$user->id) }}" />
                    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex=""
                        validation="empty" value="create" />
                    <div class="row">
                        <div class="col-12 mx-auto row">
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('name') ? 'has-error' : '' }}">
                                    <x-label name="full_name" validation="common_name" tooltip="" />
                                    <x-input name="name" id="name"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.full_name') }}" type="text"
                                        tooltip="" regex="name" validation="common_name"
                                        value="{{ old('name') }}" />
                                    <x-message name="name" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('phone') ? 'has-error' : '' }}">
                                    <x-label name="phone" validation="common_phone_number" tooltip="" />
                                    <div class="input-group">
                                        <x-input type="hidden" id="addressCountryCodeInput" name="country_code"
                                            value="" validation="empty" />
                                        <x-input type="tel" class="form-control w-21" id="addressPhone"
                                            name="phone" value="{{ old('phone') }}" validation="phone_number" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_1') ? 'has-error' : '' }}">

                                    <x-label name="address_1" validation="required" tooltip="" />
                                    <x-input name="address_1"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" id="address_1"
                                        type="text" tooltip="" regex="address" validation="required"
                                        value="{{ old('address') }}" />
                                    <x-message name="address_1" :message="@$message" validation="required" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('address_2') ? 'has-error' : '' }}">

                                    <x-label name="address_2" validation="empty" tooltip="" />
                                    <x-input name="address_2"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" id="address_2"
                                        type="text" tooltip="" regex="address" validation="empty"
                                        value="{{ old('address') }}" />
                                    <x-message name="address_2" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('country_id') ? 'has-error' : '' }}">

                                    <x-label name="country" validation="required" tooltip="" />
                                    <x-select name="country_id" value="{{ old('country_id') }}" label="Country"
                                        optionName="name" valueName="id" class="select2" :arr="@\App\Models\Country::all()"
                                        validation="required" id="country" />
                                    <div class="invalid-feedback">
                                        Please select a valid country.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('state_id') ? 'has-error' : '' }}">
                                    <x-label name="state" validation="required" tooltip="" />
                                    <x-select name="state_id" class="select2" value="{{ old('state_id') }}"
                                        label="State" optionName="name" valueName="id" class="select2"
                                        validation="required" id="state" />
                                    <div class="invalid-feedback">
                                        Please provide a valid state.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('city_id') ? 'has-error' : '' }}">
                                    <x-label name="city" validation="required" tooltip="" />
                                    <x-select name="city_id" value="{{ old('city_id') }}" label="City"
                                        optionName="name" valueName="id" class="select2" validation="required"
                                        id="city" />
                                    <div class="invalid-feedback">
                                        Please provide a valid city.
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group {{ @$errors->has('pincode') ? 'has-error' : '' }}">
                                    <x-label name="pincode" validation="common_pin_code" tooltip="" />
                                    <x-input name="pincode"
                                        placeholder="{{ __('ui.enter') . ' ' . __('ui.pincode') }}" id="pincode"
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
                                    <x-radio name="type" type="radio" :arr="$type" validation="empty"
                                        value="office" checked />
                                    <x-message name="type" :message="@$message" validation="empty" />
                                </div>
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group text-right">
                                    <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.create')
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
