@extends('layouts.empty')

@section('meta_data')
    @php
        $meta_title = 'Login | ' . getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Book My Water';
        $meta_author_email = '' ?? 'info@watercane.com';
        $meta_reply_to = '' ?? getSetting('frontend_footer_email');
        $meta_img = ' ';
    @endphp
@endsection
<style>
    .alert {
        padding: 0px 15px !important;
    }

    .alert-danger {
        color: #842029 !important;
        background-color: #f8d7da !important;
        border-color: #f5c2c7 !important;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    @media(max-width: 700px) {
        .custom-input_box {
            width: 25px !important;
            height: 30px;
            border: 0;
            border-bottom: 1px solid #817d7d;
        }
    }

    .forgot-pass {
        font-weight: 500;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    #resend-otp-msg {
        display: block;
        text-align: center;
        color: rgb(72, 72, 200) !important;
        width: 100%;
        /* Optional */
    }
</style>
@section('content')
    <section class="bg-home d-flex align-items-center position-relative p-lg-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-lg-4 col-sm-12 mx-auto">
                    <div class="p-3 bg-white rounded shadow form-signin">
                        <form method="POST" action="{{ route('verify-otp') }}" id="otp-form">
                            @csrf
                            <a href="{{ route('index') }}">
                                <img src="{{ getBackendLogo(getSetting('app_logo')) }}"
                                    class="avatar avatar-small mb-4 d-block mx-auto" style="width:250px" alt="">
                            </a>
                            {{-- <h5 class="mb-3 text-center">Please sign in</h5> --}}
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn close text-white" data-dismiss="alert"
                                        aria-label="Close">
                                    </button>
                                </div>
                            @endif
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger alert-dismissible fade show mb-3 p-2" role="alert">
                                        {{ $error }}
                                    </div>
                                @endforeach
                            @endif

                            {{-- OTP input --}}
                            <div class="otp">
                                <p class="text-dark"> We have send a OTP in E-mail {{ auth()->user()->email }}.</p>
                            </div>

                            <div id="otp-input">
                                <div class="form-floating">
                                    <label class="text-muted" for="otp">Enter OTP</label>
                                    <input required type="number" placeholder=" @lang('ui.enter_otp') " name="otp"
                                        class="form-control @error('otp') is-invalid @enderror" id="otp" autofocus>
                                    @error('otp')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <button class="btn btn-success w-100 mt-2" id="submit-otp-btn" type="submit">Verify
                                    OTP</button>
                                <a href="javascript:void(0);" id="resend-otp-msg"
                                    class="mb-0 text-muted mt-5 text-center">Resend OTP in <span id="resend-timer">10</span>
                                    seconds</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            // Countdown function for resending OTP
            function startResendTimer() {
                var count = 10; // Countdown time in seconds
                var resendTimer = setInterval(function() {
                    // Update the countdown timer display
                    $('#resend-timer').text(count);

                    if (count <= 0) {
                        clearInterval(resendTimer);
                        $('#resend-otp-msg').text('Resend OTP');
                        $('#submit-otp-btn').prop('disabled', false);
                        $('#resend-otp-msg').removeClass('disabled'); // Enable resend link
                    } else {
                        count--;
                    }
                }, 1000);

                $('#resend-otp-msg').addClass('disabled'); // Disable resend link
            }

            // Start the timer immediately when the page loads
            startResendTimer();

            // AJAX call on Resend OTP link click
            $('#resend-otp-msg').click(function(e) {
                e.preventDefault(); // Prevent default link behavior

                // Check if the link is disabled (during countdown)
                if ($(this).hasClass('disabled')) {
                    return; // Exit if it's still disabled
                }

                // Make AJAX request to resend OTP
                $.ajax({
                    url: "{{ route('resend.otp') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}" // Include CSRF token for security
                    },
                    success: function(response) {
                        console.log(
                        "OTP has been resent successfully!"); // Handle success response

                        // Restart the countdown timer
                        startResendTimer();
                    },
                    error: function(xhr, status, error) {
                        console.log(
                        "Failed to resend OTP. Please try again later."); // Handle error
                    }
                });
            });
        });
    </script>
@endpush
