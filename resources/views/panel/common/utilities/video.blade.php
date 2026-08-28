<!DOCTYPE html>
<html>

<head>
    <title>Videos | {{ getSetting('app_name') }} </title>
    <meta charset="utf-8">
    <!-- Import Mediaelement CSS -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelementplayer.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mediaelement/4.2.6/mediaelement-and-player.min.js"></script>
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/jump-forward/jump-forward.css') }}">
    <link rel="icon" href="{{ getBackendLogo(getSetting('app_favicon')) }}" />
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/skip-back/skip-back.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/dist/speed/speed.css') }}">
    <link rel="stylesheet" href="{{ asset('common/assets/utilities/audio-video/demo.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/icon-kit/dist/css/iconkit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/plugins/ionicons/dist/css/ionicons.min.css') }}">
    <style>
        .mejs__container {
            margin: 0 auto;
        }
    </style>
</head>

<body id="top">
    <div class="container" style="padding-top:2rem;">

        <div class="player">
            <video id="player1" width="1000" height="700" controls preload="none">
                <source src="{{ $path }}" type="video/mp4">
            </video>
        </div>
    </div>

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
