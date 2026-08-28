@extends('layouts.main')
@section('title', $label . ' Social Login')

@section('content')

    @php
        $breadcrumb_arr = [
            ['name' => 'Website Setting', 'url' => 'javascript:void(0);', 'class' => ''],
            ['name' => 'Social Login', 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-edit bg-blue"></i>
                        <div class="d-inline">
                            <h5> @lang('ui.website_social_login') </h5>
                            <span> @lang('ui.updated_website') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>

                        @include('panel.admin.include.breadcrumb.index')
                    </div>
                </div>
                @include('panel.admin.modal.sitemodal.index', [
                    'title' => 'How to use',
                    'content' =>
                        'You need to create a unique code and call the unique code with paragraph content helper.',
                ])
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-600 mb-0">{{ 'Social Login' }}</h6>
                    </div>
                    <form action="{{ route('panel.admin.setting.store') }}" method="POST" class="ajaxForm">
                        @csrf
                        <x-input type="hidden" name="group_name" value="social_login" validation="empty" />
                        <div class="card-body">
                            <div id="accordion">
                                <div class="accordion-header mb-3" id="headingOne">
                                    <x-button type="button" class="btn accordion-button" data-toggle="collapse"
                                        data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                        Facebook
                                    </x-button>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.facebook_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>

                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="facebook_login_active" class="js-switch switch-input facebook" @if (getSetting('facebook_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.facebook_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="facebook_client_id" value="{{ getSetting('facebook_client_id') }}" class="form-control" placeholder="Stripe Client ID" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.facebook_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="password" name="facebook_client_secret" value="{{ getSetting('facebook_client_secret') }}" class="form-control" placeholder="Facebook Secret" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-header mb-3" id="headingTwo">
                                    <x-button type="button" class="btn accordion-button collapsed" data-toggle="collapse"
                                        data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        Google
                                    </x-button>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.google_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="google_login_active" class="js-switch switch-input google" @if (getSetting('google_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.google_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="google_client_id" value="{{ getSetting('google_client_id') }}" class="form-control" placeholder="Google Client ID" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.google_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="password" name="google_client_secret" value="{{ getSetting('google_client_secret') }}" class="form-control" placeholder="Google Secret" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-header mb-3" id="headingThree">
                                    <x-button type="button" class="btn accordion-button collapsed"
                                        data-toggle="collapse" data-target="#collapseThree" aria-expanded="false"
                                        aria-controls="collapseThree">
                                        LinkedIn
                                    </x-button>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.linkedin_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="linkedin_login_active" class="js-switch switch-input linkedin" @if (getSetting('linkedin_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.linkedin_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="linkedin_client_id" value="{{ getSetting('linkedin_client_id') }}" class="form-control" placeholder="LinkedIn Client ID" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.linkedin_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="password" name="linkedin_client_secret" value="{{ getSetting('linkedin_client_secret') }}" class="form-control" placeholder="LinkedIn Secret" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-header mb-3" id="headingFour">
                                    <x-button type="button" class="btn accordion-button collapsed"
                                        data-toggle="collapse" data-target="#collapseFour" aria-expanded="false"
                                        aria-controls="collapseFour">
                                        Twitter
                                    </x-button>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.twitter_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="twitter_login_active" class="js-switch switch-input twitter" @if (getSetting('twitter_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.twitter_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="twitter_client_id" class="form-control" placeholder="Twitter Client ID" value="{{ getSetting('twitter_client_id') }}" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.twitter_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a></label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="password" name="twitter_client_secret" class="form-control" placeholder="Twitter Secret" value="{{ getSetting('twitter_client_secret') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-header mb-3" id="headingFive">
                                    <x-button type="button" class="btn accordion-button collapsed"
                                        data-toggle="collapse" data-target="#collapseFive" aria-expanded="false"
                                        aria-controls="collapseFive">
                                        Apple
                                    </x-button>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.apple_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="apple_login_active" class="js-switch switch-input twitter" @if (getSetting('apple_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.apple_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="apple_client_id" class="form-control" placeholder="Twitter Client ID" value="{{ getSetting('apple_client_id') }}" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.apple_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" validation="empty" type="password" name="apple_client_secret" class="form-control" placeholder="Twitter Secret" value="{{ getSetting('apple_client_secret') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-header mb-3" id="headingSix">
                                    <x-button type="button" class="btn accordion-button collapsed"
                                        data-toggle="collapse" data-target="#collapseSix" aria-expanded="false"
                                        aria-controls="collapseSix">
                                        Microsoft
                                    </x-button>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                        data-parent="#accordion">
                                        <div class="accordion-body">
                                            <div class="switch-content mt-4">
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.active')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.microsoft_active')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="checkbox" name="apple_login_active" class="js-switch switch-input twitter" @if (getSetting('microsoft_login_active') == 1) checked @endif value="" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.client_id')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.microsoft_client_id')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="text" name="microsoft_client_id" class="form-control" placeholder="Twitter Client ID" value="{{ getSetting('microsoft_client_id') }}" />
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label for="exampleInputUsername2" class="col-sm-3 col-form-label">
                                                        @lang('ui.secret')
                                                        <a href="javascript:void(0);" title="@lang('tooltip.microsoft_secret')"><i
                                                                class="ik ik-help-circle text-muted ml-1"></i></a> </label>
                                                    <div class="col-sm-9">
                                                        <x-input validation="empty" type="password" name="microsoft_client_secret" class="form-control" placeholder="Twitter Secret" value="{{ getSetting('microsoft_client_secret') }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <x-button type="submit" class="btn btn-primary mr-2"> @lang('ui.submit') </x-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('script')
    {{-- START JS HELPERS INIT --}}
    <script>
        $('.switch-input').on('click', function(e) {
            var content = $(this).closest('.switch-content');
            if (content.hasClass('d-none')) {
                $(this).attr('checked', 'checked');
                content.find('input').attr('required', true);
                content.removeClass('d-none');
            } else {
                content.addClass('d-none');
                content.find('input').attr('required', false);
            }
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {}
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
