<!DOCTYPE html>
<html>

<head>
    <title>PDF | {{ getSetting('app_name') }} </title>
    <meta charset="utf-8">
    {{-- Include Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset('common/utilities/pdf/pdf.viewer.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    <style>
        canvas {
            width: 100%;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .controls {
            position: fixed;
            z-index: 2;
            bottom: 0px;
            background: #212529;
            justify-content: center;
            display: flex;
            width: 100%;
        }

        @media print {
            .controls {
                display: none;
            }
        }
    </style>
</head>

<body>
    @include('panel.common.utilities.includes.head')
    <div id="resolte-container" style="width: 100%; height: 100%; overflow: auto;" data-pdf-path="{{ $path }}">
    </div>
    <div class="controls gap-2">
        <a title="Rotate" class="mt-1" href="#" id="rotate-btn"><span
                class="material-symbols-outlined text-white">rotate_90_degrees_ccw</span></a>
        <a title="Full Screen" class="mt-1" href="#" id="fullscreen-btn"><span
                class="material-symbols-outlined text-white">fullscreen</span></a>
    </div>

    <!-- Load jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- Load PDF.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>
    <script>
        $(document).ready(function() {
            var pdfPath = $('#resolte-container').data('pdf-path');
            var pdf;
            var scale = 2;
            var rotation = 0;
            var container = document.getElementById('resolte-container');

            pdfjsLib.getDocument(pdfPath).promise.then(function(pdfDocument) {
                pdf = pdfDocument;
                renderPDF();
            }).catch(function(error) {
                console.error('Error loading PDF:', error);
            });

            $('#rotate-btn').click(function(e) {
                e.preventDefault();
                rotation += 90;
                renderPDF();
            });

            $('#fullscreen-btn').click(function(e) {
                e.preventDefault();
                toggleFullScreen();
            });

            function renderPDF() {
                container.innerHTML = '';
                for (var pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                    pdf.getPage(pageNumber).then(function(page) {
                        var viewport = page.getViewport({
                            scale: scale,
                            rotation: rotation
                        });
                        var canvas = document.createElement('canvas');
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;
                        var renderContext = {
                            canvasContext: context,
                            viewport: viewport
                        };
                        page.render(renderContext).promise.then(function() {
                            container.appendChild(canvas);
                        });
                    });
                }
            }

            function toggleFullScreen() {
                if (!document.fullscreenElement) {
                    document.documentElement.requestFullscreen();
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }
        });
    </script>
</body>

</html>
