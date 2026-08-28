<div class="row">


    <div class="col-lg-6">
        <div class="shadow-none">
            <div class="dark-theme-bg primary-theme-bg mb-2">
                <h6 class="mb-0"> @lang('ui.sales_configuration') </h6>
            </div>
            <hr>
            <div class="dark-theme-body-bg primary-theme-body-bg">
                <form action="{{ route('panel.admin.setting.store') }}" class="ajaxForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-group">
                        <x-label name="min_qty_b2c" validation="common_meta_description" tooltip="min_qty_b2c" />
                        <x-input name="min_qty_b2c" placeholder="Enter Minimum Quantity" type="number" min="1"
                            tooltip="" regex="" validation="empty" value="{{ getSetting('min_qty_b2c') }}" />
                    </div>


                    <div class="form-group">
                        <x-label name="min_qty_b2b" validation="common_meta_description" tooltip="min_qty_b2b" />
                        <x-input name="min_qty_b2b" placeholder="Enter Minimum Quantity" type="number" min="1"
                            tooltip="" regex="" validation="empty" value="{{ getSetting('min_qty_b2b') }}" />
                    </div>

                    <div class="form-group">
                        <x-label name="gst_rate_in_percentage" validation="common_meta_description" tooltip="gst_rate" />

                        <x-input name="gst_rate" placeholder="Enter GST Rate" type="number" min="1"
                            max="100" step="0.01" tooltip="" regex="" validation="empty"
                            value="{{ getSetting('gst_rate') }}" />
                    </div>

                    <div class="form-group">
                        <x-label name="cin_number" validation="common_meta_description" tooltip="gst_rate" />

                        <x-input name="cin_number" placeholder="Enter CIN Number" type="text" min="1"
                            max="100" step="0.01" tooltip="" regex="" validation="empty"
                            value="{{ getSetting('cin_number') }}" />
                    </div>

                    <div class="form-group">
                        <x-label name="fssai_number" validation="common_meta_description" tooltip="gst_rate" />

                        <x-input name="fssai_number" placeholder="Enter FSSAI Number" type="text" min="1"
                            max="100" step="0.01" tooltip="" regex="" validation="empty"
                            value="{{ getSetting('fssai_number') }}" />
                    </div>



                    <div class="text-right">
                        <x-button class="btn btn-primary" type="submit">
                            @lang('ui.save_update')
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">

        <div class="shadow-none dark-theme-bg">
            <div class="primary-theme-bg mb-2">
                <h6 class="mb-0"> @lang('ui.business_address') </h6>
            </div>
            <hr>
            <div class="dark-theme-body-bg primary-theme-body-bg">
                <form action="{{ route('panel.admin.setting.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                        validation="empty" value="{{ 'website_footer_contact' }}" />
                    <div class="form-group">
                        <x-label name="primary_address" validation="common_address" tooltip="primary_address" />
                        <x-textarea regex="text" validation="common_address"
                            value="{{ getSetting('frontend_footer_address', $master_setting ?? null) }}"
                            name="frontend_footer_address" id="frontend_footer_address"
                            placeholder="{{ __('ui.enter') . ' ' . __('ui.primary_address') }}" />


                    </div>
                    <div class="text-right">
                        <x-button class="btn btn-primary" type="submit">
                            @lang('ui.save_update')
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="col-lg-12 mt-3">
        <div class="dark-theme-bg primary-theme-bg mb-2">
            <h6 class="mb-0"> @lang('ui.social_links') </h6>
        </div>
        <hr>
        <form action="{{ route('panel.admin.setting.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                validation="empty" value="{{ 'website_footer_bottom' }}" />
            <div class="p-0">
                <div class="shadow-none">
                    <div class="dark-theme-bg primary-theme-bg">
                    </div>
                    <div class="dark-theme-body-bg primary-theme-body-bg">
                        <div class="form-group">
                            <x-input type="url" icon="pre:<i class='ik ik-linkedin'></i>" name="linkedin_link"
                                placeholder="https://linkedin.com/*" tooltip="" regex=""
                                validation="empty" class="mb-2"
                                value="{{ getSetting('linkedin_link', @$master_setting) }}" />

                            <x-input type="url" icon="pre:<i class='ik ik-facebook'></i>" name="facebook_link"
                                placeholder="https://facebook.com/*" tooltip="" regex=""
                                validation="empty" class="mb-2"
                                value="{{ getSetting('facebook_link', @$master_setting) }}" />

                            <x-input type="url" icon="pre:<i class='ik ik-instagram'></i>" name="instagram_link"
                                placeholder="https://instagram.com/*" tooltip="" regex=""
                                validation="empty" class="mb-2"
                                value="{{ getSetting('instagram_link', @$master_setting) }}" />

                            {{-- <x-input type="url" icon="pre:<i class='fa-brands fa-x-twitter'></i>"
                                name="twitter_link" placeholder="https://twitter.com/*" tooltip=""
                                regex="" validation="empty" class="mb-2"
                                value="{{ getSetting('twitter_link', @$master_setting) }}" /> --}}


                            {{-- <x-input type="url" icon="pre:<i class='ik ik-youtube'></i>" name="youtube_link"
                                placeholder="https://youtube.com/*" tooltip="" regex="" validation="empty"
                                class="mb-2" value="{{ getSetting('youtube_link', @$master_setting) }}" /> --}}

                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <x-button class="btn btn-primary" type="submit">
                        @lang('ui.save_update')
                    </x-button>
                </div>
            </div>
        </form>
    </div>
</div>
