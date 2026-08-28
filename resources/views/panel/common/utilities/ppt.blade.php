<!DOCTYPE html>
<html>

<head>
    <title>PPT | {{ getSetting('app_name') }} </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    <link href="{{ asset('common/assets/utilities/styles/layout.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/jquery_ui/themes/start/jquery-ui.min.css') }}">
    <script src="{{ asset('common/assets/utilities/jquery/jquery-1.12.4.min.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/jquery_ui/jquery-ui.min.js') }}"></script>
    <!--Docs-->
    <script src="{{ asset('common/assets/utilities/docx/jszip-utils.js') }}"></script>
    <!--PPTX-->
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/PPTXjs/css/pptxjs.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/PPTXjs/css/nv.d3.min.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/revealjs/reveal.css') }}">

    <script type="text/javascript" src="{{ asset('common/assets/utilities/PPTXjs/js/filereader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/assets/utilities/PPTXjs/js/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/assets/utilities/PPTXjs/js/nv.d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/assets/utilities/PPTXjs/js/pptxjs.js') }}"></script>
    <script type="text/javascript" src="{{ asset('common/assets/utilities/PPTXjs/js/divs2slides.js') }}"></script>
    <!--All Spreadsheet -->
    <script type="text/javascript" src="{{ asset('common/assets/utilities/SheetJS/xlsx.full.min.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('common/assets/utilities/officeToHtml.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/officeToHtml.css') }}">
    <style>
        canvas {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        #resolte-contaniner {
            width: 100vw;
            height: 80vh;
            overflow: hidden;
        }

        #slides-next {
            margin-left: 10px !important;
        }

        #all_slides_warpper {}

        #slides-full-screen {
            display: none;
        }

        .slides-toolbar {
            position: fixed;
            z-index: 9999999;
            bottom: 0px;
            background: #212529;
            justify-content: center;
            display: flex;
            color: white !important;
            width: 100% !important;
        }

        @media print {
            .controls {
                display: none;
            }
        }
    </style>
</head>

<body id="top">
    @include('panel.common.utilities.includes.head')

    <div id="resolte-contaniner"></div>
    <script>
        (function($) {
            $(document).ready(function() {
                var file_path = "{{ $path }}";
                var container = document.getElementById("resolte-contaniner");

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
