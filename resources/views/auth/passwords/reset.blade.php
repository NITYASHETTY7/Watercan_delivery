@extends('layouts.app')



@section('meta_data')
    @php
        $meta_title = 'Reset Password | ' . getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Book My Water';
        $meta_author_email = '' ?? 'info@watercane.com';
        $meta_reply_to = '' ?? getSetting('app_email');
        $meta_img = ' ';
        $no_header = 1;
        $no_footer = 1;
    @endphp
@endsection
@push('head')
    <style>
        .form-center {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        body {
            overflow: hidden;
        }
    </style>
@endpush
@section('content')
    <section class="bg-home-65vh">
        <div class="container">
            <div class="row form-center">
                <div class="col-lg-7 col-xl-5 col-xxl-5 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body p-6 text-center">
                            <div class="form-signin">
                                <a href="{{ url('/') }}">
                                    {{-- <img src="{{ getBackendLogo(getSetting('app_logo')) }}" height="100px"
                                        class="avatar-small mb-4 d-block mx-auto" alt=""> --}}
                                    <img src="{{ getBackendLogo(getSetting('app_logo')) }}" height="80px"
                                        class="avatar-small mb-4 d-block mx-auto" alt="">
                                </a>
                                <h4 class="fw-semibold">Reset Password</h4>
                                <p class="text-muted small">Enter your new password below</p>

                                <div class="text-start">
                                    <form method="POST" action="{{ route('password.update') }}">
                                        @csrf
                                        <input type="hidden" name="token" value="{{ @$token ?? '' }}">

                                        <div class="mb-3">
                                            <label for="email" class="form-label">@lang('Email Address')</label>
                                            <input readonly id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ $email ?? old('email') }}" required disabled>
                                            @error('email')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                @lang('Password') <span class="text-danger">*</span>
                                            </label>
                                            <input id="password" type="password" placeholder="New Password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="new-password">
                                            @error('password')
                                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="password-confirm" class="form-label">
                                                @lang('Confirm Password') <span class="text-danger">*</span>
                                            </label>
                                            <input id="password-confirm" type="password" placeholder="Confirm New Password"
                                                class="form-control" name="password_confirmation" required
                                                autocomplete="new-password">
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                @lang('Change Password')
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
