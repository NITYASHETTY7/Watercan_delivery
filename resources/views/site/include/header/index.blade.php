@if (!empty($no_header) && $no_header == 1)
@else
    <header class="wrapper bg-light">
        <nav class="navbar navbar-expand-lg classic transparent position-absolute navbar-light py-1"
            style="box-shadow: 0px 0px 2px #cdcdcd;">
            <div class="container flex-lg-row flex-nowrap align-items-center">
                <div class="navbar-brand w-100">
                  <a href="{{ url('/') }}">
                        <img id="logoImage" src="{{ getBackendLogo(getSetting('white_app_logo')) }}" alt="" class="header-logo" style="width: 20% !important;height: auto;object-fit: contain;height: 65px;" />
                    </a>
                </div>

                <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-end bg-white">
                    <div class="offcanvas-header d-lg-none">
                        <h3 class="fs-30 mb-0">{{ isset($app_settings['app_name']) ? $app_settings['app_name'] : '' }}
                        </h3>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body ms-lg-auto d-flex flex-column h-100">
                        <ul class="navbar-nav ">
                            @auth
                                @php
                                    $user = auth()->user();
                                    $route = '';

                                    if ($user->hasRole('admin')) {
                                        $route = route('panel.admin.dashboard.index');
                                    }
                                    if ($user->hasRole('user')) {
                                        $route = route('panel.user.dashboard.index');
                                    }
                                    if ($user->hasRole('driver')) {
                                        $route = route('panel.driver.dashboard.index');
                                    }

                                @endphp

                                @if($route)
                                    <li class="nav-item pt">
                                        <a href="{{ $route }}" class="btn btn-sm btn-primary rounded-2">Dashboard</a>
                                    </li>
                                @endif
                            @endauth


                        </ul>

                    </div>
                    <!-- /.offcanvas-body -->
                </div>
                <!-- /.navbar-collapse -->
                <div class="navbar-other ms-lg-4">
                    <ul class="navbar-nav flex-row align-items-center ms-auto">
                        <li class="nav-item d-none">
                            <button class="hamburger offcanvas-nav-btn"><span></span></button>
                        </li>
                    </ul>
                    <!-- /.navbar-nav -->
                </div>
                <!-- /.navbar-other -->
            </div>
            <!-- /.container -->
        </nav>
        <!-- /.navbar -->
        <div class="offcanvas offcanvas-end text-inverse bg-white d-none" id="offcanvas-info" data-bs-scroll="true">
            <div class="offcanvas-header p-4">
                <h3 class="fs-30 mb-0">{{ $app_settings['app_name'] ?? '' }}</h3>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-4 pb-6">
                <div class="widget mb-8">
                    <p class="text-dark">
                        {{ isset($app_settings['site_motto']) ? $app_settings['site_motto'] : '' }}
                    </p>
                </div>
                <!-- /.widget -->
                <div class="widget mb-8">
                    <h4 class="widget-title text-dark mb-3">Contact Info</h4>
                    <address class="fs-15 text-dark">
                        {{ isset($app_settings['app_address']) ? $app_settings['app_address'] : '' }}</address>
                    <a href="mailto:info@watercane.come" class="text-dark">{{ getSetting('app_email') }}</a><br />
                    <a href="tel:{{ isset($app_settings['app_contact']) ? $app_settings['app_contact'] : '' }}"
                        class="text-dark">{{ isset($app_settings['app_contact']) ? $app_settings['app_contact'] : '' }}</a>
                </div>
                <div class="widget mb-8">
                    <h4 class="widget-title text-dark mb-3">Learn More</h4>
                    <ul class="list-unstyled" style="font-size: 15px">
                        <li><a href="{{ url('/') }}">Home</a></li>
                    </ul>
                </div>
                <div class="widget">
                    <h4 class="widget-title text-dark mb-3">Follow Us</h4>
                    <nav class="nav social social-muted social-menu">
                        <a href="{{ isset($app_settings['twitter_link']) ? $app_settings['twitter_link'] : '#' }}"
                            target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="{{ isset($app_settings['facebook_link']) ? $app_settings['facebook_link'] : '#' }}"
                            target="_blank"><i class="uil uil-facebook-f"></i></a>
                        <a href="{{ isset($app_settings['instagram_link']) ? $app_settings['instagram_link'] : '#' }}"
                            target="_blank"><i class="uil uil-instagram"></i></a>
                    </nav>
                </div>
            </div>
        </div>
    </header>
@endif

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var logo = document.getElementById('logoImage');
            // Laravel-generated absolute paths
            var whiteLogoPath = "{{ getBackendLogo(getSetting('white_app_logo')) }}";
            var greenLogoPath = "{{ getBackendLogo(getSetting('app_logo')) }}";

            window.addEventListener('scroll', function() {
                if (window.scrollY > 40) {
                    logo.setAttribute('src', greenLogoPath);
                } else {
                    logo.setAttribute('src', whiteLogoPath);
                }
            });
        });
        window.addEventListener('scroll', function() {
            const hamburger = document.querySelector('.hamburger');
            if (window.scrollY > 10) {
                hamburger.classList.add('scrolled');
            } else {
                hamburger.classList.remove('scrolled');
            }
        });
    </script>
@endpush
