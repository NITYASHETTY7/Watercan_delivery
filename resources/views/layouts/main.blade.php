@php
    $authRole = AuthRole();
@endphp

@php
    if (request()->has('ref') && request()->get('ref') == 'app') {
        if (request()->sso_token != null) {
            if (auth()->check()) {
                auth()->logout();
                $user = App\Models\User::where('sso_token', request()->sso_token)->first();
                auth()->loginUsingId($user->id);
            }
            session()->put('mobile_view_activated', 1);
        } else {
            session()->put('mobile_view_activated', 1);
        }
    }
@endphp


@if ($authRole == 'User')
    @include('layouts.user')
@elseif($authRole == 'Driver')
    @include('layouts.driver')
@else
    @include('layouts.admin')
@endif
