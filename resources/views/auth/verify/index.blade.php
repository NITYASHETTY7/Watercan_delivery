@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"> @lang('tooltip.verify_email_address') </div>

                    <div class="card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                @lang('tooltip.email_verification_link')
                            </div>
                        @endif

                        @lang('tooltip.did_not_receive_email')
                        @lang('tooltip.did_not_receive_email') ,
                        <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 m-0 align-baseline"> @lang('tooltip.click_here_to_request_another') </button>.
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
