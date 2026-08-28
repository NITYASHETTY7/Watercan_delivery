<!DOCTYPE html>
<html>

<head>
    <title>Document | {{ getSetting('app_name') }} </title>
    <meta charset="utf-8">
    <script src="{{ asset($root_directory . 'plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>
    {{-- Include Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include Bootstrap CSS --}}
    <!--Docs-->
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    <script src="{{ asset('common/assets/utilities/docx/jszip-utils.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/docx/mammoth.browser.min.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('common/assets/utilities/officeToHtml.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/officeToHtml.css') }}">
    <style>
        #resolte-contaniner {
            height: 100vh;
            position: relative;
            width: 100% !important;
            max-width: 100% !important;
            overflow: revert-layer !important;
        }

        #content-frame {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            border: none;
        }
    </style>
</head>

<body id="top">
    @include('panel.common.utilities.includes.head')
    <div class="container">
        <div class="alert alert-light p-1">
            ~ {{ $path }}
        </div>
        <div id="resolte-contaniner">
            <iframe id="content-frame" src="{{ $path }}" frameborder="0"></iframe>
        </div>
    </div>
    <script>
        function adjustIframeHeight() {
            var iframe = document.getElementById('content-frame');
            if (iframe) {
                var docHeight = Math.max(iframe.contentWindow.document.body.scrollHeight, iframe.contentWindow.document
                    .documentElement.scrollHeight);
                iframe.style.height = docHeight + 'px';
            }
        }

        $(document).ready(function() {
            adjustIframeHeight();
        });

        $('#content-frame').on('load', function() {
            adjustIframeHeight();
        });

        $(window).on('resize', adjustIframeHeight);
    </script>
</body>

</html>
