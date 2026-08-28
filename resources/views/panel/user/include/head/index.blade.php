{{-- 1. TAILWIND CSS (CRITICAL: Load first as CSS) --}}
{{-- 2. Custom CSS & Plugins --}}
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/style.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/plugins.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/leaflet.css') }}">

{{-- 3. Flaticon Icons (Styling) --}}
{{-- <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-straight/css/uicons-thin-straight.css" /> --}}
<link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-bold-straight/css/uicons-bold-straight.css" />
{{-- <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-thin-rounded/css/uicons-thin-rounded.css" /> --}}
{{-- <link rel="stylesheet" href="https://cdn-uicons.flaticon.com/3.0.0/uicons-solid-rounded/css/uicons-solid-rounded.css" /> --}}
<link rel="stylesheet"
href="https://cdn-uicons.flaticon.com/3.0.0/uicons-regular-rounded/css/uicons-regular-rounded.css" />
<!-- LOAD TAILWIND LAST -->
<script src="https://cdn.tailwindcss.com"></script>



@stack('head')