<!DOCTYPE html>
<html>

<head>
    <title>Audio | {{ getSetting('app_name') }}</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/jump-forward/jump-forward.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/skip-back/skip-back.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/speed/speed.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icon-kit/dist/css/iconkit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/ionicons/dist/css/ionicons.min.css') }}">
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
</head>

<body id="top">
    @include('panel.common.utilities.includes.head')
    <div class="container" style="max-width:800px;">
        <div class="player">
            <audio preload="none" controls data-cast-title="[Your title]"
                data-cast-description="[Your optional description]" data-cast-poster="">

                <source src="{{ $path }}" type="audio/mp3">

            </audio>
        </div>
    </div>
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelement-and-player.min.js"></script> --}}
    <script src="{{ asset('common/assets/utilities/audio-video/dist/jump-forward/jump-forward.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/audio-video/dist/skip-back/skip-back.js') }}"></script>
    <script src="{{ asset('common/assets/utilities/audio-video/dist/speed/speed.js') }}"></script>
    <script>
        var mediaElements = document.querySelectorAll('video, audio');

        for (var i = 0, total = mediaElements.length; i < total; i++) {

            var features = ['playpause', 'current', 'progress', 'duration', 'volume', 'skipback', 'jumpforward', 'speed',
                'fullscreen'
            ];

            new MediaElementPlayer(mediaElements[i], {
                autoRewind: false,
                features: features,
            });
        }
    </script>
</body>

</html>
