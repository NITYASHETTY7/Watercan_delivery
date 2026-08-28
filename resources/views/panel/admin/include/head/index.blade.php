<link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />

<!-- font awesome library -->
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/font-family/nunito.css') }}" rel="stylesheet">

<script src="{{ asset($root_directory . 'plugins/js/app.js') }}"></script>

<!-- themekit admin template asstes -->
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/all.css?v=' . rand(0, 99999)) }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/dist/css/theme.css') }}">
{{-- Font Awesome --}}

<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/fontawesome.css') }}"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/icon-kit/dist/css/iconkit.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/ionicons/dist/css/ionicons.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/nprogress/nprogress.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/croppie.min.css') }}">

{{-- COUNTRYCODE SELECTOR INIT --}}
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/intlTelInput.css') }}">
{{-- End COUNTRYCODE SELECTOR INIT --}}

<!-- Stack array for including inline css or head elements -->
<link rel="stylesheet" type="text/css"
    href="{{ asset($root_directory . 'plugins/date-picker/daterangepicker.css') }}" />
@stack('head')

<link rel="stylesheet" type="text/css"
    href="{{ asset($root_directory . 'plugins/jquery-confirm-3.3.2/jquery-confirm.min.css') }}" />

@if (auth()->user()->preferences != null &&
        isset(auth()->user()->preferences['theme_id']) &&
        auth()->user()->preferences['theme_id'] != null)
    @if (auth()->user()->preferences['theme_id'] == 1)
        <link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/style.css') }}">
    @elseif(auth()->user()->preferences['theme_id'] == 2)
        <link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/dark-theme.css') }}">
    @elseif(auth()->user()->preferences['theme_id'] == 3)
        <link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/primary-theme.css') }}">
    @endif
@else
    <link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/style.css') }}">
@endif

<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/style.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/select2/dist/css/select2.min.css') }}">

<link rel="stylesheet"
    href="{{ asset($root_directory . 'plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/jquery-toast-plugin/dist/jquery.toast.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/shimmer.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/intlTelInput.css') }}">
