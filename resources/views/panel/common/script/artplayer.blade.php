<script src="{{ asset('site/v1/plugins/artplayer/artplayer.js') }}"></script>
<script src="{{ asset('site/v1/plugins/artplayer/hls.min.js') }}"></script>
<script src="{{ asset('site/v1/plugins/artplayer/artplayer-plugin-hls-quality.js') }}"></script>
<script>
    // Define the initializeArtplayer function globally
    window.initializeArtplayer = function() {
        var appName = "{{ getSetting('app_name') }}";
        var lessonName = "{{ $firstItem->title }}";
        const videoUrl = '{{ $firstItem->getFirstMediaUrl('video') }}';
        var art = new Artplayer({
            container: '.artplayer-app',
            url: videoUrl,
            volume: 0.5,
            isLive: false,
            muted: false,
            autoplay: true,
            pip: true,
            autoSize: false,
            autoMini: false,
            screenshot: false,
            setting: true,
            loop: false,
            flip: false,
            playbackRate: true,
            aspectRatio: true,
            fullscreen: true,
            fullscreenWeb: false,
            subtitleOffset: false,
            miniProgressBar: true,
            mutex: true,
            backdrop: true,
            playsInline: true,
            autoPlayback: true,
            airplay: true,
            theme: '#A435F0FF',
            lang: navigator.language.toLowerCase(),
            moreVideoAttr: {
                crossOrigin: 'anonymous',
            },
            contextmenu: [{
                html: 'Custom menu',
                click: function(contextmenu) {
                    console.info('You clicked on the custom menu');
                    contextmenu.show = false;
                },
            }],
            layers: [{
                html: '<img width="50" src="{{ asset('site/assets/img/brands/ds-white-logo.png') }}">',
                click: function() {
                    window.open('{{ url('/') }}');
                    console.info('You clicked on the custom layer');
                },
                style: {
                    position: 'absolute',
                    top: '20px',
                    right: '20px',
                    opacity: '.9',
                },
            }],
            plugins: [
                artplayerPluginHlsQuality({
                    control: true,
                    auto: 'Auto',
                    default: 'Auto',
                }),
            ],
            customType: {
                m3u8: function playM3u8(video, url, art) {
                    if (Hls.isSupported()) {
                        if (art.hls) art.hls.destroy();
                        const hls = new Hls();
                        hls.loadSource(url);
                        hls.attachMedia(video);
                        art.hls = hls;
                        art.on('destroy', () => hls.destroy());
                    } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                        video.src = url;
                    } else {
                        art.notice.show = 'Unsupported playback format: m3u8';
                    }
                }
            }
        });
    };

    // Call the function once the DOM is ready
    document.addEventListener('DOMContentLoaded', initializeArtplayer);
</script>
