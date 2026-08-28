<title> {{ @$meta_title ?? getSetting('seo_meta_title') }}</title>
<link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />

<!-- CSS -->
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/plugins.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/intlTelInput.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/style.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/base/colors/navy.css') }}">
<link rel="stylesheet" href="{{ asset($master_root_directory . '/plugins/jquery-3.6.0/jquery-3.6.0.js') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/jquery-confirm-3.3.2/jquery-confirm.min.css') }}" />
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/fontawesome.css') }}" />
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/dist/jquery.toast.min.css') }}">
<link rel="stylesheet" href="{{ asset($root_directory . 'plugins/select2/dist/css/select2.min.css') }}">

{{-- Dynamic CSS Before Head --}}
@if (getSetting('custom_header_style') != 0)
    <link rel="stylesheet" href="{!! getSetting('custom_header_style') !!}" />
@endif

<style>
    .alert {
        position: relative;
        padding: 0.75rem 1.7rem;
        margin-bottom: 1rem;
        border: 1px solid transparent;
        border-radius: 0.3125rem;
        font-weight: 500;
    }

    .alert-dismissible {
        padding-right: 4rem;
    }

    .alert-dismissible .close {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0.75rem 1.25rem;
        color: inherit;
    }

    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
</style>
