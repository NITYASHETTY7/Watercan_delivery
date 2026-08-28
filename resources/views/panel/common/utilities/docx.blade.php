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
    <script src="{{ asset('common/assets/utilities/docx/jszip-utils.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/docx/mammoth.browser.min.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('common/assets/utilities/officeToHtml.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/officeToHtml.css') }}">
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
</head>

<body id="top">
    @include('panel.common.utilities.includes.head')
    <div class="container">
        <div id="resolte-contaniner" style="width: 100%; height:100%; overflow: auto;"></div>
    </div>
    <script>
        (function($) {
            $(document).ready(function() {
                var file_path = "{{ $path }}";
                $("#resolte-contaniner").officeToHtml({
                    url: file_path,
                    pdfSetting: {
                        setLang: "",
                        setLangFilesPath: "" /*"include/pdf/lang/locale" - relative to app path*/
                    }
                });
            });
        }(jQuery));
    </script>
</body>

</html>
