@php
    $root_directory = 'site/v1/';
    $root_directory_path = 'site.';
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


<!DOCTYPE html>
<html lang="en">

<head>
    <!-- @yield('meta_data') -->
    @include($root_directory_path . 'include.meta.index')
    @include($root_directory_path . 'include.head.index')
    @stack('head')
</head>
<style>
    .icon-size-custom {
        height: 32px !important;
    }
</style>

<body>
    <div>
        <!-- initiate header-->
        <div class="content-wrapper">

            @if (!checkMobileViewActivated())
                @include($root_directory_path . 'include.header.index')
            @endif

            <div class="main-content pl-0">
                @yield('content')
            </div>
        </div>
        <!-- Back to top -->
        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
                class="fea icon-sm icons align-middle icon-size-custom"></i></a>

        @if (!Request::is('shopping-cart') && !Request::is('checkout') && !Request::is('product-thankyou'))
            @if (!checkMobileViewActivated())
                @if (!isset($customer))
                    @include($root_directory_path . 'include.footer.index')
                @else
                    @include($root_directory_path . 'include.footer_bar.index')
                @endif
            @endif
        @endif
    </div>

    {{-- Preview File --}}
    {{-- @include('panel.common.preview-files.iframe') --}}

    <!-- initiate script-->
    @include($root_directory_path . 'include.script')
    @stack('script')
</body>

</html>
