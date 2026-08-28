@php
    $role = 'driver';
    $root_directory = "panel/$role/";
    $root_directory_path = "panel.$role";

@endphp

<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>
        @yield('title', '') | {{ getSetting('app_name') }}
    </title>
    @include($root_directory_path . '.include.meta.index')
    @include($root_directory_path . '.include.head.index')
</head>

<body class="bg-gray-50 text-gray-800 antialiased">
    @yield('content')

    <!-- initiate script-->
    @include($root_directory_path . '.include.script.index')
</body>

</html>
