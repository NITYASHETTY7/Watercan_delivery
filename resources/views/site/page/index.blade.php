@extends('layouts.app')

@section('meta_data')
    @php
        $decoded_title = html_entity_decode(html_entity_decode($page->title)) . ' | Book My Water';
        $meta_title = $decoded_title;
        $meta_description = $page->page_meta_description ? $page->page_meta_description : '';
        $meta_keywords = $page->page_keywords ? $page->page_keywords : getSetting('app_name');
        $meta_motto = false ? $page->page_keywords : getSetting('app_name');
        $meta_author_name = '' ?? 'Book My Water';
        $meta_img = ' ';
        $forceGreenLogo = 1;
    @endphp
@endsection

@section('content')
    <!--Shape End-->
    <!-- Start Terms & Conditions -->
    @if(!checkMobileViewActivated())
        <section class="wrapper">
            <div class="container pt-md-12 text-start">
                <div class="row" style="margin-top: 50px;">
                    <div class="col-md-10 col-lg-8 col-xl-7 col-xxl-6">
                        <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);"
                            aria-label="breadcrumb">
                            <ol class="breadcrumb mb-1">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{!!$page->title ?? '' !!}</li>
                            </ol>
                        </nav>
                        <!-- /nav -->
                    </div>
                    <!-- /column -->
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container -->
        </section>
    @endif
    <section class="section bg-white">
        <div class="container">
            <div class="row gx-0">
                <!-- /column -->
                <div class="col-xl-12 pb-10">
                    <section id="terms-conditions" class="wrapper pt-3 pt-md-3">
                        <div class="card">
                            @if ($page->getMedia('page_meta_image')->isNotEmpty())
                                <img src="{{ $page->getFirstMediaUrl('page_meta_image') }}" class="card-img-top cover-img"
                                    alt="Banner Image" loading="lazy">
                            @endif
                            <div class="card-body p-md-10 p-4">
                                {{-- <h1 class="mb-3 fs-26">{!!@$page->title!!}</h1> --}}
                                <p>{!! $page->content !!}</p>
                            </div>
                            <!--/.card-body -->
                        </div>
                        <!--/.card -->
                    </section>
                </div>
                <!-- /column -->
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!--end section-->
    <!-- End Terms & Conditions -->
@endsection
