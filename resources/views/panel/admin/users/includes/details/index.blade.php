<div class="">
    <div class="card-body">

        <form action="{{ route('panel.admin.users.update', secureToken($user->id)) }}" method="POST"
            class="form-horizontal ">
            @csrf
            <x-input type="hidden" name="request_with" value="update" validation="empty" />
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="first_name" validation="common_name" tooltip="" />
                        <x-input required type="text" pattern="[a-zA-Z]+.*"
                            title="Please enter first letter alphabet and at least one alphabet character is required."
                            placeholder="First Name" class="form-control" name="first_name" id="name"
                            value="{{ @$user->first_name }}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="last_name" validation="common_name" tooltip="" />
                        <x-input required type="text" pattern="[a-zA-Z]+.*"
                            title="Please enter first letter alphabet and at least one alphabet character is required."
                            title="Please enter first letter alphabet and at least one alphabet character is required."
                            placeholder="Last Name" class="form-control" name="last_name" id="lname"
                            value="{{ @$user->last_name }}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="email" validation="email" tooltip="" />
                        <x-input required type="email" placeholder="test@test.com" class="form-control" name="email"
                            id="email" value="{{ @$user->email }}" />
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="phone" tooltip="" validation="empty" class="" />
                        <x-input type="tel" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" min="0" minlength="10"
                            maxlength="10" placeholder="123-45-678" id="phone" name="phone" class="form-control"
                            value="{{ @$user->phone }}" />
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="dob" tooltip="" validation="empty" class="" />
                        <x-input class="form-control" type="date" max="{{ now()->format('Y-m-d') }}" name="dob"
                            placeholder="Select your date" value="{{ @$user->dob }}" />
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <x-label name="status" tooltip="" validation="common_name" class="" />
                        <x-select name="status" validation="status" id="status" class="form-control select2"
                            label="{{ __('ui.status') }}" :value="$user->status ?? old('status')" optionName="label" :arr="$statuses" />
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="country" tooltip="" validation="empty" class="" />
                        <x-select name="country_id" validation="country_id" id="country" class="form-control select2"
                            label="{{ __('ui.Country') }}" :value="$user->country_id ??
                                (old('country_id') ?? \App\Models\Country::where('name', 'India')->first()?->id)" optionName="name" :arr="\App\Models\Country::all()" />
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="state" tooltip="" validation="empty" class="" />
                        <x-select name="state_id" validation="state_id" id="state" class="form-control select2"
                            label="{{ __('ui.state') }}" :value="$user->state ?? old('state_id')" optionName="name" required />

                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="city" tooltip="" validation="empty" class="" />
                        <x-select name="city_id" validation="city_id" id="city" class="form-control select2"
                            label="{{ __('ui.city') }}" :value="$user->city ?? old('city_id')" optionName="name" required />

                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="pincode" tooltip="" validation="empty" class="" />
                        <x-input id="pincode" type="number" class="form-control" name="pincode"
                            placeholder="Enter Pincode" value="{{ @$user->pincode ?? old('pincode') }}" />
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="assign_role" validation="common_name" tooltip="" />
                        <x-select name="role" value="{{ $role }}" label="{{ __('ui.assign_role') }}" optionName="name" class="select2" :arr="@$roles" validation="role" id="roleId" valueName="id" />

                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <x-label name="gender" validation="common_name" tooltip="" />
                        <div class="form-radio">
                            <div class="radio radio-inline">
                                <label>
                                    <x-input type="radio" name="gender" value="Male"
                                        {{ @$user->gender == 'Male' ? 'selected' : '' }} />
                                    <i class="helper"></i> @lang('ui.male')
                                </label>
                            </div>
                            <div class="radio radio-inline">
                                <label>
                                    <x-input type="radio" name="gender" value="Female"
                                        {{ @$user->gender == 'Female' ? 'selected' : '' }} />
                                    <i class="helper"></i> @lang('ui.female')
                                </label>
                            </div>
                        </div>
                        <div class="help-block with-errors"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-top: 20px;">
                        <div class="form-check mx-sm-2">
                            <label class="custom-control custom-checkbox">
                                <x-checkbox type="checkbox" name="is_verified"
                                    class="custom-control-input js-switch switch-input" value="1"
                                    {{ @$user->is_verified == 1 ? 'checked' : '' }} />@lang('ui.verified_profile')</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group" style="margin-top: 20px;">
                        <div class="form-check mx-sm-2">
                            <label class="custom-control custom-checkbox">
                                <x-checkbox type="checkbox" class="js-switch switch-input" value="1"
                                    @if (@$user->email_verified_at != null) checked @endif name="email_verified_at" />
                                @lang('ui.email_verified') </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <x-label for="address" name="address" validation="empty" tooltip="" />
                        <x-textarea name="address" rows="3" class="form-control"
                            value="{{ @$user->address }}" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <x-label for="bio" name="bio" validation="empty" tooltip="" />
                        <x-textarea name="bio" name="bio" rows="3" class="form-control"
                            value="{{ @$user->bio }}" />
                    </div>
                </div>

                <div class="text-center">
                    <div style="width: 150px; height: 150px; position: relative" class="mx-auto">
                        <img src="{{ @$user && @$user->avatar ? @$user->avatar : asset('panel/admin/default/default-avatar.png') }}"
                            class="rounded-circle" width="150"
                            style="object-fit: cover; width: 150px; height: 150px" />
                        <x-button class="btn btn-dark rounded-circle position-absolute"
                            style="width: 30px; height: 30px; padding: 8px; line-height: 1; top: 0; right: 0"
                            data-toggle="modal" data-target="#updateProfileImageModal"><i
                                class="ik ik-camera"></i></x-button>
                    </div>
                    <div class="mt-2">
                        <h5>{{ @$user->full_name }}</h5>
                    </div>
                </div>

            </div>
    </div>
