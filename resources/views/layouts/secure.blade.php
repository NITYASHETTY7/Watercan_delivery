@php
    $root_directory = 'panel/secure/';
    $root_directory_path = 'user.';
    $role = 'user';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        @hasSection('title')
            @yield('title')
        @elseif(isset($meta_title) && !empty($meta_title))
            {{ $meta_title }}
        @else
            {{ getSetting('app_name') }}
        @endif
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- initiate header-->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="{{ asset('panel/secure/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('site/v1/plugins/fontawesome-6.5.1/fontawesome.css') }}" />
    <!-- End initiate header-->

</head>

<body>

    <div class="main-content mt-3">
        <section class="bg-white">
            @yield('content')
        </section>
    </div>

    <!-- initiate Body-->
    <script src="{{ asset('site/v1/plugins/fontawesome-6.5.1/all.min.js') }}"></script>
    <script src="{{ asset('site/v1/plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>
    @stack('script')
    <!-- initiate Body-->
</body>

</html>
