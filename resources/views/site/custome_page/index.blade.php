@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Landing';
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
    <!-- Start Contact -->
    <section class="section">
        <div class="container-fluid">

            <div class="text-center" style="margin: 125px">

                <i data-feather="check-circle" class="fea icon-lg text-success fea-check-circle"></i>
                <h3 class="mt-4">{{ $request->title ?? 'Thank You' }}</h3>
                <p class="text-muted">{{ $request->sub_title ?? 'Your Request has been taken successfully.' }}</p>

                <a href="{{ route('index') }}" class="btn btn-link">Back Home</a>


                @if (getSetting('frontend_footer_phone') != null)
                    <hr class="w-50 mx-auto">

                    <h6 class="text-muted">Need Help?</h6>
                    Contact us: <a class="text-dark" href="tel:{{ getSetting('frontend_footer_phone') }}">
                        {{ getSetting('frontend_footer_phone') }}
                    </a>
                @endif
            </div>

        </div><!--end container-->
    </section>
    <!--end section-->
    <!-- End contact -->
@endsection
