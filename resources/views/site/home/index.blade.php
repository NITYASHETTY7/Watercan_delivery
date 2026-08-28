@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Home';
        $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = isset($app_settings['site_motto']) ? $app_settings['site_motto'] : '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = isset($app_settings['app_name']) ? $app_settings['app_name'] : 'Book My Water';
        $meta_author_email = isset($app_settings['frontend_footer_email'])
            ? $app_settings['frontend_footer_email']
            : 'info@watercane.com';
        $meta_reply_to = isset($app_settings['frontend_footer_email'])
            ? $app_settings['frontend_footer_email']
            : 'info@watercane.com';
        $meta_img = ' ';
    @endphp
@endsection
@section('content')
    <style>
        /* ==================================== */
        /* === HERO SECTION STYLES === */
        /* ==================================== */
        .hero-section {
            display: flex;
            background-color: white;
            align-items: center;
            justify-content: center;
            padding: 0 5%;
            position: relative;
            overflow: hidden;
            min-height: 550px;
            /* Added min-height for better visibility */
        }

        /* === ORANGE GRADIENT GLOW (No change needed) === */
        .hero-section::before {
            position: absolute;
            inset: 0;
            background-color: white;
            z-index: 0;
        }

        /* === LEFT CONTENT === */
        .hero-content {
            position: relative;
            z-index: 2;
            margin-top: 1.5rem;
            color: #1a1a1a;
            width: 50%;
            padding-right: 20px;
            /* Added slight padding */
        }

        .hero-content h1 {
            font-size: 60px;
            font-weight: 800;
            line-height: 1.1;
            color: #202020;
        }

        .hero-content h1 span {
            color: #3f78e0;
        }

        .hero-content p {
            font-size: 20px;
            color: #525252;
            line-height: 1.5;
            max-width: 550px;
            margin-top: 15px;
            margin-bottom: 25px;
        }

        /* === CTA BUTTON (No change needed) === */
        .cta-btn {
            position: relative;
            overflow: hidden;
            background: linear-gradient(90deg, #3f78e0, #6498ff);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(97, 128, 240, 0.4);
            /* Existing styles for hover and shine animation remain */
        }

        /* Existing hover/shine styles for cta-btn remain */

        /* === RIGHT IMAGE === */
        .hero-image {
            position: relative;
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1;
        }

        .hero-image img {
            width: 470px;
            filter: drop-shadow(0 10px 25px rgba(154, 151, 201, 0.3));
            animation: floaty 4s ease-in-out infinite;
        }

        @keyframes floaty {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }


        @media (max-width: 992px) {
            .hero-section {
                flex-direction: column;
                text-align: center;
                padding-top: 40px;
            }

            .hero-content {
                width: 100%;
                padding: 0 5%;
            }

            .hero-content h1 {
                font-size: 44px;
            }

            .hero-content p {
                font-size: 18px;
                margin: 0 auto 25px auto;
                max-width: 90%;
            }

            .hero-image {
                width: 80%;
                margin: 30px auto 0 auto;
            }

            .hero-image img {
                width: 350px;
                max-width: 90%;
            }

            .hero-content .row.g-2 .col-lg-10 {
                margin: 0 auto;
            }
        }

        /* ---------- Mobile view ---------- */
        @media (max-width: 768px) {
            .hero-section {
                padding: 0 20px;
                text-align: center;
            }

            .hero-content {
                width: 100%;
                padding: 0;
                margin-top: 1rem;
            }

            .hero-content h1 {
                font-size: 32px;
                line-height: 1.2;
            }

            .hero-content p {
                font-size: 16px;
                margin: 15px auto 25px auto;
                max-width: 95%;
            }

            .hero-content .cta-btn {
                font-size: 16px;
                padding: 10px 18px;
                border-radius: 8px;
            }

            .hero-image {
                display: none;
            }

            .margin-top {
                margin-top: -200px;
            }
        }
    </style>


    <section class="py-lg-16 py-sm-10 hero-section" style="padding-bottom: 0rem !important;">
        <div class="hero-content">

            <h1>Smart Water Delivery,<span> Simplified for Everyone</span><br> </h1>
            <p> Manage your entire bottled water delivery network from orders to drivers with one intelligent system.</p>
            <div class="row g-2">

                <div class="col-lg-10 col-sm-12">
                    <button class="cta-btn w-100 d-flex align-items-center justify-content-center gap-2 fw-semibold">
                        Get Started
                        <i class="fa-solid fa-arrow-right ms-1"></i>
                    </button>
                </div>
            </div>

        </div>

        <div class="hero-image">
            <img src="site/v1/img/home-banner-5.png" alt="Hero Image">
        </div>
    </section>

    <section class="how-it-works py-12 bg-light margin-top">
        <div class="container">
            <div class="text-center mb-8">
                <h2 class="fw-bold display-5 mb-0">How It Works</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="step-box p-4 text-center h-100 shadow-lg rounded-3">
                        <img class="img-fluid how-it-work-img mb-2"
                            src="{{ asset('site/v1/img/onbording/Order ahead-bro.svg') }}" alt="">
                        <h4 class="fw-semibold">Order with Ease</h4>
                        <p class="text-black fs-15">Select your water can size or subscription plan and confirm your address
                            in seconds.</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="step-box p-4 text-center h-100 shadow-lg rounded-3">
                        <img class="img-fluid how-it-work-img mb-2"
                            src="{{ asset('site/v1/img/onbording/Heavy box-bro.svg') }}" alt="">
                        <h4 class="fw-semibold">Get Instant Dispatch</h4>
                        <p class="text-black fs-15 mx-auto" style="max-width: 250px; text-align: center;">
                            The nearest verified driver picks up your order and heads your way.
                        </p>
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="step-box p-4 text-center h-100 shadow-lg rounded-3">
                        <img class="img-fluid how-it-work-img mb-2"
                            src="{{ asset('site/v1/img/onbording/Order ride-bro.svg') }}" alt="">
                        <h4 class="fw-semibold">Track & Stay Hydrated</h4>
                        <p class="text-black fs-15">Follow your delivery live, get notified on arrival, and enjoy fresh
                            water on time every time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Call To Action --}}
    <section class="wrapper position-relative overflow-hidden" id="call-to-action"
        style="background: linear-gradient(135deg, #2459af 0%, #7bc0ff 100%);">

        {{-- Animated Background Elements --}}
        <div class="position-absolute w-100 h-100 top-0 start-0" style="opacity: 0.1;">
            <div class="position-absolute rounded-circle"
                style="width: 300px; height: 300px; background: white; top: -100px; right: -100px; animation: float 6s ease-in-out infinite;">
            </div>
            <div class="position-absolute rounded-circle"
                style="width: 200px; height: 200px; background: white; bottom: -50px; left: -50px; animation: float 8s ease-in-out infinite;">
            </div>
        </div>

        <div class="container py-lg-14 py-md-10 py-12 position-relative">
            <div class="row gx-lg-10 gx-xl-12 align-items-center">

                {{-- App Image Section --}}
                <div class="col-lg-6 mb-8 mb-lg-0" data-aos="fade-right">
                    <div class="d-flex align-items-end justify-content-center position-relative" style="height: 550px;">

                        {{-- Decorative Elements --}}
                        <div class="position-absolute rounded-4"
                            style="width: 400px; height: 400px; background: rgba(255,255,255,0.1); backdrop-filter: blur(20px); top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 0;">
                        </div>

                        {{-- First Image: For Users --}}
                        <div class="text-center position-relative"
                            style="margin-right: -40px; z-index: 2; animation: floatUp 3s ease-in-out infinite;">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('site/v1/img/mockuper1.png') }}" alt="Download App"
                                    style="width: 260px; height: auto; filter: drop-shadow(0 20px 60px rgba(0,0,0,0.3));"
                                    class="app-downloads" />
                                <div class="position-absolute"
                                    style="bottom: -35px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 8px 20px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                                    <p class="fw-bold text-dark mb-0" style="font-size: 14px;">For User</p>
                                </div>
                            </div>
                        </div>

                        {{-- Second Image: For Partners --}}
                        <div class="text-center position-relative"
                            style="margin-left: -40px; z-index: 1; animation: floatUp 3s ease-in-out infinite 0.5s;">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('site/v1/img/mockuper3.png') }}" alt="Download App"
                                    style="width: 260px; height: auto; filter: drop-shadow(0 20px 60px rgba(0,0,0,0.3));"
                                    class="app-downloads" />
                                <div class="position-absolute"
                                    style="bottom: -35px; left: 50%; transform: translateX(-50%); background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); padding: 8px 20px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.1);">
                                    <p class="fw-bold text-dark mb-0" style="font-size: 14px;">For Partner</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Content Section --}}
                <div class="col-lg-6" id="download-section" data-aos="fade-left">

                    {{-- Heading --}}
                    <h1 class="display-5 fw-bold mb-4 lh-sm text-white" style="text-shadow: 0 2px 20px rgba(0,0,0,0.2);">
                        Stay hydrated without the hassle. Order once, and
                        <span class="d-inline-block position-relative">
                            we'll handle the rest.
                            <svg class="position-absolute" style="left: 0; bottom: -10px; width: 100%; height: 12px;"
                                viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M2 10C50 5 100 2 150 5C200 8 250 3 298 8" stroke="#FFD700" stroke-width="3"
                                    stroke-linecap="round" />
                            </svg>
                        </span>
                    </h1>

                    <p class="lead text-white mb-5" style="opacity: 0.95;">Download our app and experience seamless water
                        delivery at your fingertips.</p>

                    {{-- User & Driver Download Section --}}
                    <div class="row g-4">

                        {{-- For Users --}}
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 position-relative"
                                style="background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 45px; height: 45px; background: rgba(255,255,255,0.25);">
                                        <svg width="24" height="24" fill="white" viewBox="0 0 24 24">
                                            <path
                                                d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                                        </svg>
                                    </div>
                                    <h5 class="fw-bold text-white mb-0">For Users</h5>
                                </div>
                                <p class="text-white mb-4" style="opacity: 0.9; font-size: 14px;">Order water effortlessly
                                    with our easy-to-use app</p>
                                <div class="d-flex flex-row gap-2">
                                    <a href="{{ getSetting('iphone_user_app_url') }}" target="_blank" rel="noopener"
                                        class=""
                                        style="transition: all 0.3s ease;">
                                        <img src="{{ asset('site/v1/img/app-store-logo.png') }}" alt="App Store"
                                            style="height: 32px;">
                                    </a>
                                    <a href="{{ getSetting('android_user_app_url') }}" target="_blank" rel="noopener"
                                        class=""
                                        style="transition: all 0.3s ease;">
                                        <img src="{{ asset('site/v1/img/play-store-logo.png') }}" alt="Play Store"
                                            style="height: 32px;">
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- For Partners --}}
                        <div class="col-md-6">
                            <div class="p-4 rounded-4 h-100 position-relative"
                                style="background: rgba(255,255,255,0.15); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.2); transition: all 0.3s ease;">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                        style="width: 45px; height: 45px; background: rgba(255,255,255,0.25);">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"width="24" height="24" fill="white"><path d="M598.1 139.4C608.8 131.6 611.2 116.6 603.4 105.9C595.6 95.2 580.6 92.8 569.9 100.6L495.4 154.8L485.5 148.2C465.8 135 442.6 128 418.9 128L359.7 128L359.3 128L215.7 128C189 128 163.2 136.9 142.3 153.1L70.1 100.6C59.4 92.8 44.4 95.2 36.6 105.9C28.8 116.6 31.2 131.6 41.9 139.4L129.9 203.4C139.5 210.3 152.6 209.3 161 201L164.9 197.1C178.4 183.6 196.7 176 215.8 176L262.1 176L170.4 267.7C154.8 283.3 154.8 308.6 170.4 324.3L171.2 325.1C218 372 294 372 340.9 325.1L368 298L465.8 395.8C481.4 411.4 481.4 436.7 465.8 452.4L456 462.2L425 431.2C415.6 421.8 400.4 421.8 391.1 431.2C381.8 440.6 381.7 455.8 391.1 465.1L419.1 493.1C401.6 503.5 381.9 509.8 361.5 511.6L313 463C303.6 453.6 288.4 453.6 279.1 463C269.8 472.4 269.7 487.6 279.1 496.9L294.1 511.9L290.3 511.9C254.2 511.9 219.6 497.6 194.1 472.1L65 343C55.6 333.6 40.4 333.6 31.1 343C21.8 352.4 21.7 367.6 31.1 376.9L160.2 506.1C194.7 540.6 241.5 560 290.3 560L342.1 560L343.1 561L344.1 560L349.8 560C398.6 560 445.4 540.6 479.9 506.1L499.8 486.2C501 485 502.1 483.9 503.2 482.7C503.9 482.2 504.5 481.6 505.1 481L609 377C618.4 367.6 618.4 352.4 609 343.1C599.6 333.8 584.4 333.7 575.1 343.1L521.3 396.9C517.1 384.1 510 372 499.8 361.8L385 247C375.6 237.6 360.4 237.6 351.1 247L307 291.1C280.5 317.6 238.5 319.1 210.3 295.7L309 197C322.4 183.6 340.6 176 359.6 175.9L368.1 175.9L368.3 175.9L419.1 175.9C433.3 175.9 447.2 180.1 459 188L482.7 204C491.1 209.6 502 209.3 510.1 203.4L598.1 139.4z"/></svg>
                                    </div>
                                    <h5 class="fw-bold text-white mb-0">For Partners</h5>
                                </div>
                                <p class="text-white mb-4" style="opacity: 0.9; font-size: 14px;">Join us and manage
                                    deliveries on the go</p>
                                <div class="d-flex flex-row gap-2">
                                    <a href="{{ getSetting('iphone_driver_app_url') }}" target="_blank" rel="noopener"
                                        class=""
                                        style="transition: all 0.3s ease;">
                                        <img src="{{ asset('site/v1/img/app-store-logo.png') }}" alt="App Store"
                                            style="height: 32px;">
                                    </a>
                                    <a href="{{ getSetting('android_driver_app_url') }}" target="_blank" rel="noopener"
                                        class=""
                                        style="transition: all 0.3s ease;">
                                        <img src="{{ asset('site/v1/img/play-store-logo.png') }}" alt="Play Store"
                                            style="height: 32px;">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="row mt-5 g-3">
                        <div class="col-4">
                            <div class="text-center">
                                <h3 class="fw-bold text-white mb-1">50K+</h3>
                                <p class="text-white small mb-0" style="opacity: 0.8;">Active Users</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <h3 class="fw-bold text-white mb-1">4.8★</h3>
                                <p class="text-white small mb-0" style="opacity: 0.8;">App Rating</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center">
                                <h3 class="fw-bold text-white mb-1">24/7</h3>
                                <p class="text-white small mb-0" style="opacity: 0.8;">Support</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <style>
            /* Existing animation keyframes (float, floatUp, fadeInRight, fadeInLeft) remain */
            @keyframes float {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-20px);
                }
            }

            @keyframes floatUp {

                0%,
                100% {
                    transform: translateY(0px);
                }

                50% {
                    transform: translateY(-15px);
                }
            }

            [data-aos="fade-right"] {
                animation: fadeInRight 1s ease-out;
            }

            [data-aos="fade-left"] {
                animation: fadeInLeft 1s ease-out;
            }

            @keyframes fadeInRight {
                from {
                    opacity: 0;
                    transform: translateX(-30px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            @keyframes fadeInLeft {
                from {
                    opacity: 0;
                    transform: translateX(30px);
                }

                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .btn-light:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            }

            [style*="backdrop-filter"]:hover {
                background: rgba(255, 255, 255, 0.25) !important;
                transform: translateY(-5px);
                box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
            }

            /* End of existing styles */

            /* Add new mobile-specific styles here (or within the media query above) */
        </style>
    </section>
@endsection

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const getStartedBtn = document.querySelector(".cta-btn");
            const downloadSection = document.querySelector("#call-to-action");

            if (getStartedBtn && downloadSection) {
                getStartedBtn.addEventListener("click", function() {
                    let headerOffset;

                    // Set gap dynamically based on screen width
                    if (window.innerWidth >= 1200) {
                        headerOffset = 0; // Large screens
                    } else if (window.innerWidth >= 768) {
                        // Increased headerOffset for tablets to account for potential header or navbar
                        headerOffset = 150;
                    } else {
                        // Increased headerOffset for mobile devices
                        headerOffset = 100;
                    }

                    const elementPosition = downloadSection.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                    window.scrollTo({
                        top: offsetPosition,
                        behavior: "smooth"
                    });
                });
            }
        });
    </script>
@endpush
