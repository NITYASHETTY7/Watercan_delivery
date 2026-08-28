@if (!empty($no_footer) && $no_footer == 1)
@else
    <footer class="bg-gray">
        <div class="container pt-13 pt-md-13 pb-lg-6 pb-4">
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div class="footer-content">
                        <div class="social-footer-logo text-center mb-3">
                            <img src="{{ getBackendLogo(getSetting('app_logo')) }}" alt="Logo" height="100px">

                        </div>
                        <div class="text-center">
                            <p class="fs-16">{{ getSetting('frontend_footer_description') ?? '--' }}</p>
                        </div>
                        <div class="text-center mb-2 fs-15 text-gray-700 py-2">
                            <strong>Company & Legal Information:</strong>
                            <br>
                            CIN Number: {{ getSetting('cin_number') }} | FSSAI Number: {{ getSetting('fssai_number') }}
                        </div>
                        
                        <div class="d-flex justify-content-center flex-column flex-md-row mb-4">
                            <nav class="nav social social-muted social-menu social-center">
                                @if (isset($app_settings['linkedin_link']) && !empty($app_settings['linkedin_link']))
                                    <a href="{{ @$app_settings['linkedin_link'] ?? '' }}" target="_blank">
                                        <i class="fa-brands fa-linkedin"></i>
                                    </a>
                                @endif

                                @if (isset($app_settings['facebook_link']) && !empty($app_settings['facebook_link']))
                                    <a href="{{ @$app_settings['facebook_link'] ?? '' }}" target="_blank"><i
                                            class="uil uil-facebook-f"></i></a>
                                @endif

                                @if (isset($app_settings['instagram_link']) && !empty($app_settings['instagram_link']))
                                    <a href="{{ @$app_settings['instagram_link'] ?? '' }}" target="_blank"><i
                                            class="uil uil-instagram"></i></a>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
        <div class="footer-py-20 footer_bar bg-soft-primary">
            <div class="container">
                
                <div class="row align-items-center">
                    <div class="col-sm-12 py-2">
                        <div class="d-flex justify-content-center justify-content-md-between flex-column flex-md-row fs-14">

                            <div class="text-lg-start text-md-start text-center">
                                {{ isset($app_settings['frontend_copyright_text']) ? $app_settings['frontend_copyright_text'] : '' }}
                            </div>

                            <div class="legal-option">
                                <ul
                                    class="list-unstyled text-reset mb-0 footer-list d-lg-flex d-md-flex d-block gap-2 justify-content-center">
                                    @php
                                        $useful_links = App\Models\WebsitePage::select('title', 'slug')
                                            ->whereStatus(1)
                                            ->latest()
                                            ->limit(2)
                                            ->get();
                                    @endphp
                                    @foreach ($useful_links as $item)
                                        <li>
                                            <a href="{{ route('page.slug', $item->slug) }}" class="fs-13">
                                                {!!$item->title ?? '' !!}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="progress-wrap">
            <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
                <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
            </svg>
        </div>
    </footer>
@endif
