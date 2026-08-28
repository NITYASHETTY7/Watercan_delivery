@php
    $root_directory = 'site/v1/';
    $root_directory_path = 'site.';
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    @include($root_directory_path . 'include.head.index')
</head>

<body>
    <div>
        <!-- initiate header-->
        <div class="main-content pl-0">
            @yield('content')
        </div>
        <!-- Back to top -->
        <a href="#" onclick="topFunction()" id="back-to-top" class="back-to-top fs-5"><i data-feather="arrow-up"
                class="fea icon-sm icons align-middle"></i></a>
    </div>
    <!-- initiate script-->
    @include($root_directory_path . 'include.script')
    @stack('script')
</body>

</html>
