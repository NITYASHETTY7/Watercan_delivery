<div class="card-body">
    <form class="row" action="{{ route('panel.admin.profile.update.password', secureToken($user->id)) }}"
        method="POST">
        @csrf
        <x-input type="hidden" name="request_with" value="password" validation="empty" />
        <div class="col-12">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="password">@lang('ui.new_password') <span class="text-danger">*</span></label>
                    <x-input required type="password" class="form-control custom-placeholder" name="password"
                        placeholder="{{ __('ui.new_password') }}" id="password" value="" validation="empty" />
                </div>
            </div>
        </div>
        <div class="form-group col-md-6">
            <label for="confirm-password">@lang('ui.confirm_password') <span class="text-danger">*</span></label>
            <x-input required type="password" class="form-control custom-placeholder" name="confirm_password"
                placeholder="{{ __('ui.confirm_password') }}" id="confirm-password" value="" validation="empty" />
        </div>
        <div class="col-md-12">
            <x-button class="btn btn-primary" type="submit">@lang('ui.change') {{ Str::limit($user->full_name, 20) }}
                @lang('ui.password') </x-button>
        </div>
    </form>
</div>
