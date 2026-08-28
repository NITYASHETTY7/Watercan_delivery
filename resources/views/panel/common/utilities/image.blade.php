<!DOCTYPE html>
<html>

<head>
    <title>Image Previewer | {{ getSetting('app_name') }}</title>
    <meta charset="utf-8">
    <script src="{{ asset($root_directory . 'plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>
    {{-- Include Bootstrap CSS --}}
    <link rel="stylesheet" href="{{ asset($master_root_directory . 'plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    {{-- End Include Bootstrap CSS --}}

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    <!--Docs-->
    <script src="{{ asset('common/assets/utilities/docx/jszip-utils.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/docx/mammoth.browser.min.js') }}"></script>
    <!--officeToHtml-->
    <script src="{{ asset('common/assets/utilities/officeToHtml.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/officeToHtml.css') }}">
    <style>
        .container {
            position: relative;
            width: 95vh - 10px;
            margin: 0 auto;
            overflow: hidden;
        }

        .image-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: auto;
            overflow: hidden;
            object-fit: contain !important;
            border: 1px solid #ccc;
            margin: 0 auto;
        }

        .image-container img {
            display: block;
            width: auto;
            max-height: 85vh;
            height: 85vh;
            margin: auto;
        }

        .controls {
            position: relative;
            z-index: 2;
            top: -1px;
            background: #212529;
            justify-content: center;
            display: flex;
        }

        @media print {
            .controls {
                display: none;
            }

            .image-container {
                width: 100% !important;
            }

            .image-container img {
                width: 100% !important;
                object-fit: contain;
            }
        }
    </style>

</head>

<body id="top">
    @include('panel.common.utilities.includes.head')
    <div class="container">
        <div class="image-container">
            <img style="cursor: move;" id="zoom-image" src="{{ $path }}" alt="Zoomable Image">
        </div>
        <div class="controls gap-2">
            <a title="Zoom In" class="mt-1" href="#" id="zoom-in-btn"><span
                    class="material-symbols-outlined text-white">zoom_in</span></a>
            <a title="Zoom Out" class="mt-1" href="#" id="zoom-out-btn"><span
                    class="material-symbols-outlined text-white">zoom_out</span></a>
            <a title="Rotate" class="mt-1" href="#" id="rotate-btn"><span
                    class="material-symbols-outlined text-white">rotate_90_degrees_ccw</span></a>
            <a title="Full Screen" class="mt-1" href="#" id="fullscreen-btn"><span
                    class="material-symbols-outlined text-white">fullscreen</span></a>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageContainer = document.querySelector('.image-container');
        const zoomImage = document.querySelector('#zoom-image');
        const zoomInBtn = document.querySelector('#zoom-in-btn');
        const zoomOutBtn = document.querySelector('#zoom-out-btn');
        const rotateBtn = document.querySelector('#rotate-btn');

        let scale = 1;
        const scaleStep = 0.1;
        const minScale = 0.5;
        const maxScale = 2;
        let rotation = 0;

        function updateImageTransform() {
            zoomImage.style.transform = `scale(${scale}) rotate(${rotation}deg)`;
        }

        zoomInBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (scale < maxScale) {
                scale += scaleStep;
                updateImageTransform();
            }
        });

        zoomOutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (scale > minScale) {
                scale -= scaleStep;
                updateImageTransform();
            }
        });

        rotateBtn.addEventListener('click', function(event) {
            event.preventDefault();
            rotation += 90;
            updateImageTransform();
        });
    });

    // Moveable 
    document.addEventListener('DOMContentLoaded', function() {
        const imageContainer = document.querySelector('.image-container');
        const zoomImage = document.querySelector('#zoom-image');
        const zoomInBtn = document.querySelector('#zoom-in-btn');
        const zoomOutBtn = document.querySelector('#zoom-out-btn');
        const rotateBtn = document.querySelector('#rotate-btn');

        let scale = 1;
        const scaleStep = 0.1;
        const minScale = 0.5;
        const maxScale = 2;
        let rotation = 0;
        let posX = (imageContainer.offsetWidth - zoomImage.offsetWidth) / 2;
        let posY = (imageContainer.offsetHeight - zoomImage.offsetHeight) / 2;
        let dragging = false;
        let lastX = 0;
        let lastY = 0;

        function updateImageTransform() {
            zoomImage.style.transform =
                `scale(${scale}) rotate(${rotation}deg) translate(${posX}px, ${posY}px)`;
        }

        zoomInBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (scale < maxScale) {
                scale += scaleStep;
                updateImageTransform();
            }
        });

        zoomOutBtn.addEventListener('click', function(event) {
            event.preventDefault();
            if (scale > minScale) {
                scale -= scaleStep;
                updateImageTransform();
            }
        });

        rotateBtn.addEventListener('click', function(event) {
            event.preventDefault();
            rotation += 90;
            updateImageTransform();
        });

        imageContainer.addEventListener('mousedown', function(event) {
            dragging = true;
            lastX = event.clientX;
            lastY = event.clientY;
        });

        imageContainer.addEventListener('mousemove', function(event) {
            if (dragging) {
                const deltaX = event.clientX - lastX;
                const deltaY = event.clientY - lastY;
                posX += deltaX;
                posY += deltaY;
                lastX = event.clientX;
                lastY = event.clientY;
                updateImageTransform();
            }
        });

        imageContainer.addEventListener('mouseup', function() {
            dragging = false;
        });

        imageContainer.addEventListener('mouseleave', function() {
            dragging = false;
        });
    });

    // Fullscreen Mode
    document.getElementById('fullscreen-btn').addEventListener('click', function() {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen();
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            }
        }
    });
</script>

</html>
