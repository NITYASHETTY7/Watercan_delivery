@if (auth()->user() && session()->has('admin_user_id') && session()->has('temp_user_id'))
    <div class="alert alert-warning logged-in-as mb-4">
        @lang('ui.you_r_currently_loginas') {{ auth()->user()->full_name }}. <a
            href="{{ route('panel.admin.dashboard.logout-as') }}">@lang('ui.re_loginas')
            {{ session()->get('admin_user_name') }}
        </a>.
    </div>
@endif
