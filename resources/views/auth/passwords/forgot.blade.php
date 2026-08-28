@extends('layouts.app')
@if (isset($settings['recaptcha']) && $settings['recaptcha'] == 1)
    {!! ReCaptcha::htmlScriptTagJsApi() !!}
@endif


@section('meta_data')
    @php
        $meta_title = 'Forgot Password | ' . getSetting('app_name');
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
    </style>
@endpush
@section('content')
    <section class="bg-home-75vh">
        <div class="container">
            <div class="row form-center">
                <div class="col-lg-7 col-xl-5 col-xxl-5 col-md-8 mx-auto">
                    <div class="card">
                        <div class="card-body p-6 text-center">
                            <div class="form-signin">
                                {{-- @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger alert-dismissible fade show my-1" role="alert">
                                            {{ $error }}
                                            <button type="button" class="btn close" data-dismiss="alert" aria-label="Close">
                                                <span class="">&times;</span>
                                            </button>
                                        </div>
                                    @endforeach
                                @endif --}}
                                <form method="POST" action="{{ route('password.email') }}">
                                    @csrf
                                    <a href="{{ url('/') }}">
                                        {{-- <img src="{{ getBackendLogo(getSetting('app_logo')) }}" height="100px"
                                            class="avatar-small mb-4 d-block mx-auto" alt=""> --}}
                                        <img src="{{ getBackendLogo(getSetting('app_logo')) }}" height="80px"
                                            class="avatar-small mb-4 d-block mx-auto" alt="">
                                    </a>
                                    <h1 class="my-3 text-center fs-18">Reset your password</h1>
                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="floatingInput" placeholder="name@example.com" name="email"
                                            value="{{ old('email') }}" required>
                                        <label for="floatingInput">Email Address</label>
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ @$message ?? '' }}</strong>
                                        </span>
                                    @enderror
                                    <button id="submit-btn"
                                        class="btn btn-primary position-relative rounded-pill w-100 mb-2 mt-2"
                                        type="submit">
                                    {{-- Spinner: absolute‑centred, hidden by default --}}
                                    <span class="spinner-border position-absolute top-50 start-50 translate-middle d-none"
                                          role="status" aria-hidden="true"></span>

                                    {{-- Button text --}}
                                    <span class="btn-text">Send Instructions</span>
                                </button>
                                    @if(session('flash_success'))
                                        <div id="flash-success" class="alert alert-success alert-dismissible fade show" role="alert">
                                            {{ session('flash_success') }}
                                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                        </div>
                                    @endif
                                    {{-- <div class="col-12 text-center mt-3">
                                        <div class="d-flex justify-content-center gap-1">
                                            <p class="mb-0">Already have an account?</p>
                                            <a href="{{ route('login', 'user') }}" class="hover"> Sign In</a>
                                        </div>
                                        <p class="mb-0 mt-3"><small class="text-dark me-2"></small></p>
                                    </div> --}}
                                    <p class="mb-0 text-muted mt-3 text-center">©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script> {{ getSetting('app_name') }}
                                    </p>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const box = document.getElementById('flash-success');
    if (box) {
        setTimeout(() => {
            // Bootstrap 5: just trigger the dismiss
            const alert = bootstrap.Alert.getOrCreateInstance(box);
            alert.close();

            // If you prefer a fade‑out instead:
            // box.classList.remove('show');        // triggers .fade CSS
        }, 10000); // 10 000 ms = 10 s
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const spinner = submitBtn.querySelector('.spinner-border');
    const btnText = submitBtn.querySelector('.btn-text');

    if (form) {
        form.addEventListener('submit', () => {
            submitBtn.setAttribute('disabled', 'disabled');
            spinner.classList.remove('d-none');  // show spinner
            btnText.classList.add('d-none');     // hide label
        });
    }

    /* optional: auto‑dismiss success alert after 10 s */
    const flash = document.getElementById('flash-success');
    if (flash) setTimeout(() => bootstrap.Alert.getOrCreateInstance(flash).close(), 10000);
});
</script>
@endpush
