@extends('layouts.main')
@section('title', __('ui.basic_details'))
@section('content')
    @php
        @$breadcrumb_arr = [['name' => __('ui.basic_details'), 'url' => 'javascript:void(0);', 'class' => 'active']];
    @endphp
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ __('ui.basic_details') ?? '--' }}</h5>
                            <span class="fs-15"> @lang('ui.website_page_heading') </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div>
                        @include('panel.admin.include.breadcrumb.index')
                    </div>
                </div>
                @include('panel.admin.modal.sitemodal.index', [
                    'title' => __('ui.how_to_use'),
                    'content' => __('ui.you_need_create_unique_code'),
                ])
            </div>
        </div>

        <div class="row">
            <div class="col-md-10 mx-auto">
                <div class="card">
                    <ul class="nav nav-pills custom-pills" id="pills-tab" role="tablist">
                        @if (getSetting('toggling_control_details_activation', @$master_setting))
                            <li class="nav-item"><a class="nav-link active" id="pills-profile-tab" data-toggle="pill"
                                    href="#last-month" role="tab" aria-controls="pills-profile" aria-selected="false">
                                    @lang('ui.control_detail') </a>
                            </li>
                        @endif
                        @if (getSetting('toggling_custom_style_activation', @$master_setting))
                            <li class="nav-item"><a data-active="security"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'security') active @endif"
                                    id="pills-security-tab" data-toggle="pill" href="#security" role="tab"
                                    aria-controls="pills-security" aria-selected="true"> @lang('ui.custom_style') </a>
                            </li>
                        @endif
                        @if (getSetting('toggling_custom_script_activation', @$master_setting))
                            <li class="nav-item"><a data-active="customscript"
                                    class="nav-link active-swicher @if (request()->has('active') && request()->get('active') == 'customscript') active @endif"
                                    id="pills-customscript-tab" data-toggle="pill" href="#customscript" role="tab"
                                    aria-controls="pills-customscript" aria-selected="true"> @lang('ui.custom_script') </a>
                            </li>
                        @endif
                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        @if (getSetting('toggling_control_details_activation', @$master_setting))
                            <div class="tab-pane fade show active" id="last-month" role="tabpanel"
                                aria-labelledby="pills-profile-tab">
                                <div class="card-body">
                                    <div class="row gutters-10">
                                        <div class="col-lg-6">
                                            <div class="card shadow-none bg-light">
                                                <div class="card-header dark-theme-bg primary-theme-bg">
                                                    <h6 class="mb-0"> @lang('ui.about_website') </h6>
                                                </div>
                                                <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                    <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf

                                                        <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'website_footer_about' }}" />

                                                        <div class="form-group">
                                                            <x-label name="about_content" validation="required" tooltip="setting_about_content" />
                                                            <x-textarea regex="text" value="{{ trim(getSetting('frontend_footer_description', @$master_setting)) }}" name="frontend_footer_description" id="frontend_footer_description" placeholder="Enter Content" validation="required" />
                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="map_location" validation="common_meta_description" tooltip="map_location" />
                                                            <x-textarea regex="text" value="{{ getSetting('toggling_frontend_map_code', @$master_setting) }}" name="toggling_frontend_map_code" id="toggling_frontend_map_code" placeholder="Enter Location" validation="common_meta_description" />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="copy_right" validation="common_meta_description" tooltip="setting_copyright" />
                                                            <x-textarea regex="text" value="{{ getSetting('frontend_copyright_text', @$master_setting) }}" name="frontend_copyright_text" id="frontend_copyright_text" placeholder="Enter Copyright" validation="common_meta_description" />

                                                        </div>

                                                        <div class="text-right ajax-btn">
                                                            <x-button class="btn btn-primary" type="submit">
                                                                @lang('ui.save_update')
                                                            </x-button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card shadow-none bg-light dark-theme-bg">
                                                <div class="card-header primary-theme-bg">
                                                    <h6 class="mb-0"> @lang('ui.business_address') </h6>
                                                </div>
                                                <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                    <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'website_footer_contact' }}" />

                                                        <div class="form-group">
                                                            <x-label name="primary_address" validation="common_address" tooltip="primary_address" />
                                                            <x-textarea regex="text" validation="common_address" value="{{ getSetting('frontend_footer_address', @$master_setting) }}" name="frontend_footer_address" id="frontend_footer_address" placeholder="Enter Primary Address" />


                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="secondary_address" validation="common_address" tooltip="secondary_address" />
                                                            <x-textarea regex="text" validation="common_address" value="{{ getSetting('toggling_frontend_footer_address_secondary', @$master_setting) }}" name="toggling_frontend_footer_address_secondary" id="toggling_frontend_footer_address_secondary" placeholder="Enter Secondary Address" />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="primary_number" validation="common_phone_number" tooltip="primary_number" />
                                                            <x-textarea regex="tel_no" validation="common_phone_number" value="{{ getSetting('frontend_footer_phone', @$master_setting) }}" name="frontend_footer_phone" id="frontend_footer_phone" placeholder="Enter Primary Number" />
                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="secondary_number" validation="common_phone_number" tooltip="secondary_number" />
                                                            <x-textarea regex="tel_no" validation="common_phone_number" value="{{ getSetting('frontend_footer_phone', @$master_setting) }}" name="frontend_footer_phone" id="frontend-footer-secondary-number" placeholder="Enter Primary Number" />

                                                        </div>
                                                        <div class="form-group">
                                                            <x-label name="primary_email" validation="common_email" tooltip="primary_email" />
                                                            <x-input name="frontend_footer_email" placeholder="Enter Email" type="text" tooltip="primary_email" regex="email" validation="common_email" value="{{ getSetting('frontend_footer_email', @$master_setting) }}" />

                                                        </div>
                                                        <div class="text-right ajax-btn">
                                                            <x-button class="btn btn-primary" type="submit">
                                                                @lang('ui.save_update')
                                                            </x-button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf

                                                <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'website_footer_bottom' }}" />

                                                <div class="card-body p-0">

                                                    <div class="card shadow-none bg-light">
                                                        <div class="card-header dark-theme-bg primary-theme-bg">
                                                        </div>
                                                        <div class="card-body dark-theme-body-bg primary-theme-body-bg">
                                                            <div class="form-group">
                                                                <x-input type="url" name="facebook_link"
                                                                    value="{{ getSetting('facebook_link', @$master_setting) }}"
                                                                    validation="empty"
                                                                    icon="pre:<i class='ik ik-facebook'></i>"
                                                                    placeholder="https://facebook.com/*" tooltip=""
                                                                    class="mb-2" />

                                                                <x-input type="url" name="twitter_link"
                                                                    value="{{ getSetting('twitter_link', @$master_setting) }}"
                                                                    validation="empty"
                                                                    icon="pre:<i class='fa-brands fa-x-twitter'></i>"
                                                                    placeholder="https://twitter.com/*" tooltip=""
                                                                    class="mb-2" />

                                                                <x-input type="url" name="instagram_link"
                                                                    value="{{ getSetting('instagram_link', @$master_setting) }}"
                                                                    validation="empty"
                                                                    icon="pre:<i class='ik ik-instagram'></i>"
                                                                    placeholder="https://instagram.com/*" tooltip=""
                                                                    class="mb-2" />

                                                                <x-input type="url" name="youtube_link"
                                                                    value="{{ getSetting('youtube_link', @$master_setting) }}"
                                                                    validation="empty"
                                                                    icon="pre:<i class='ik ik-youtube'></i>"
                                                                    placeholder="https://youtube.com/*" tooltip=""
                                                                    class="mb-2" />

                                                                <x-input type="url" name="linkedin_link"
                                                                    value="{{ getSetting('linkedin_link', @$master_setting) }}"
                                                                    validation="empty"
                                                                    icon="pre:<i class='ik ik-linkedin'></i>"
                                                                    placeholder="https://linkedin.com/*" tooltip=""
                                                                    class="mb-2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right ajax-btn">
                                                        <x-button class="btn btn-primary" type="submit">
                                                            @lang('ui.save_update')
                                                        </x-button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'security') show active @endif" id="security"
                            role="tabpanel" aria-labelledby="pills-security-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                    enctype="multipart/form-data" class="ajaxForm">
                                    @csrf
                                    <x-input name="active" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'security' }}" />
                                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'appearance_custom_style' }}" />
                                    <div class="form-group row">
                                        <x-label name="header_custom" validation="empty" tooltip="" />
                                        <div class="col-md-8">
                                            <x-textarea validation="empty" placeholder="<style> ... </style>"
                                                name="custom_header_style" :value="getSetting('custom_header_style', @$master_setting)" class="form-control"
                                                rows="4" />
                                            <small class="text-color-white"> @lang('ui.write_style') </small>
                                        </div>
                                    </div>

                                    <div class="text-right">

                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->has('active') && request()->get('active') == 'customscript') show active @endif" id="customscript"
                            role="tabpanel" aria-labelledby="pills-customscript-tab">
                            <div class="card-body">
                                <form action="{{ route('panel.admin.setting.store') }}" method="POST"
                                    enctype="multipart/form-data" class="ajaxForm">
                                    @csrf
                                    <x-input name="active" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'customscript' }}" />
                                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="{{ 'appearance_custom_script' }}" />

                                    <div class="form-group row">
                                        <x-label name="header_custom_script" validation="empty" tooltip="" />
                                        <div class="col-md-8">
                                            <x-textarea validation="empty" name="custom_header_script" :value="getSetting('custom_header_script', @$master_setting)"
                                                class="form-control" placeholder="<script>
                                                    ...
                                                </script>"
                                                rows="4" />
                                            <small class="text-color-white"> @lang('ui.write_script') </small>
                                        </div>
                                    </div>
                                    @csrf
                                    <div class="form-group row">
                                        <x-label name="footer_custom" validation="empty" tooltip="" />
                                        <div class="col-md-8">
                                            <x-textarea validation="empty" name="custom_footer_script" :value="getSetting('custom_footer_script', @$master_setting)"
                                                class="form-control" placeholder="<script>
                                                    ...
                                                </script>"
                                                rows="4" />
                                            <small class="text-color-white"> @lang('ui.script_tag') </small>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.update')
                                        </x-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('script')
        {{-- COUNTRYCODE SELECTOR INIT --}}
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const input = document.querySelector("#phone");
                const countryCodeInput = document.querySelector("#countryCodeInput");

                const iti = window.intlTelInput(input, {
                    initialCountry: "auto",
                    separateDialCode: true,
                    utilsScript: "{{ asset($master_root_directory . 'plugins/country-code/utils.js') }}",
                });
                window.iti = iti;
                const updateCountryCode = () => {
                    const selectedCountryData = iti.getSelectedCountryData();
                    countryCodeInput.value = selectedCountryData.dialCode;
                };

                input.addEventListener("countryChange", updateCountryCode);
                input.addEventListener("keyup", updateCountryCode);
                input.addEventListener("change", updateCountryCode);

                setTimeout(() => {
                    const event = new Event('countryChange');
                    input.dispatchEvent(event);
                }, 300);
            });
        </script>
        {{-- END COUNTRYCODE SELECTOR INIT --}}
    @endpush

@endsection
