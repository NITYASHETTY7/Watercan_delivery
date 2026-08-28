@extends('layouts.empty')
@section('meta_data')
    @php
        $meta_title = getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Defenzelite';
        $meta_author_email = '' ?? 'support@defenzelite.com';
        $meta_reply_to = '' ?? getSetting('app_email');
    @endphp
@endsection
@section('content')
    <style>
        .payment-screen {
            min-height: 76vh;
            justify-content: center;
            display: flex;
            align-items: center;
        }

        .bg-white {
            background: #000 !important;
        }

        .main-content {
            height: 100vh;
        }
    </style>
    @if ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Error!</strong> {{ $message }}
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade {{ Session::has('success') ? 'show' : 'in' }}" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <strong>Success!</strong> {{ $message }}

        </div>
    @endif

    <div class="container mt-1">
        <div class="row">
            <div class="col-lg-6 col-sm-12 col-md-12 mx-auto">
                <div class="card text-center mt-10">
                    <div class="alert alert-secondary mb-0 payment-screen">
                        <div class="">
                            <strong class="text-black fs-18">Please wait...</strong>
                            <p class="text-dark fs-15">
                                Creating secure payment link. Do not close this window or press back.
                            </p>

                            <span class="mt-4">
                                <i class="fa fa-spin fa-spinner fs-28 text-black"></i>
                            </span>
                        </div>

                        <form class="d-none" action="{{ route('panel.user.order.payment', ['sso_token' => $sso_token]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <input type="hidden" name="sso_token" value="{{ $sso_token }}">

                            <script src="https://checkout.razorpay.com/v1/checkout.js" data-key="{{ env('API_RAZOR_KEY') }}"
                                data-amount="{{ $order->total * 100 }}" data-buttontext="Pay Amount" data-name="Book My Water"
                                data-description="{{ ucfirst($order->type) }} Order Payment for Water Can (Ref: #ORD{{ $order->id }})"
                                data-notes.orderid="{{ $order->id }}" data-prefill.name="{{ $user->full_name }}"
                                data-prefill.phone="{{ $user->phone }}" data-prefill.email="{{ $user->email }}" data-theme.color="#54a8c7"
                                data-image="{{ asset(getSetting('app_logo')) }}"></script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <script>
        const redirectUrl = "{{ route('panel.user.cart.index') }}"; // New Redirect URL

        setTimeout(() => {
            const rzpButton = $('.razorpay-payment-button');
            const rzpInstance = rzpButton.data('razorpay');

            if (rzpInstance && rzpInstance.modal && rzpInstance.modal.options) {
                // Overwrite modal.ondismiss to redirect to the cart index on exit
                rzpInstance.modal.options.ondismiss = function() {
                    window.location.href = redirectUrl;
                };
            }

            // Trigger Razorpay checkout
            rzpButton.trigger('click');

            // Optional message
            $('.checkpoint-message').html('Verifying payment. Please do not close or press the back button.');
        }, 1000);
        
        history.pushState(null, document.title, location.href);
        window.addEventListener('popstate', function(event) {
            history.pushState(null, document.title, location.href);

            window.location.href = redirectUrl;
        });
    </script>
@endsection
