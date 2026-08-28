<!DOCTYPE html>
<html>

<head>
    <title>Excel | {{ getSetting('app_name') }} </title>
    <meta charset="utf-8">
    <script src="{{ asset($root_directory . 'plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>
    {{-- Include Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include Bootstrap CSS --}}

    <!--Docs-->
    <!--All Spreadsheet -->
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/SheetJS/handsontable.full.min.css') }}">
    <script type="text/javascript" src="{{ asset('common/assets/utilities/SheetJS/handsontable.full.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/assets/utilities/SheetJS/xlsx.full.min.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('common/assets/utilities/officeToHtml.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/officeToHtml.css') }}">
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    </style>
</head>

<body id="top">
    @include('panel.common.utilities.includes.head')

    <div class="container">
        <div id="resolte-contaniner" style="width: 100%; height:90vh; overflow: auto;">
        </div>
        <script>
            (function($) {
                $(document).ready(function() {
                    var file_path = "{{ $path }}";
                    $("#resolte-contaniner").officeToHtml({
                        url: file_path,
                        pdfSetting: {
                            setLang: "",
                            setLangFilesPath: ""
                        }
                    });
                });
            }(jQuery));
        </script>
</body>

</html>
