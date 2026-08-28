<!-- Modal -->
@php
    $websitePages = App\Models\WebsitePage::where('status', 1)->select('id', 'title', 'status')->get();
@endphp
<div class="modal fade" id="legalModal" tabindex="-1" role="dialog" aria-labelledby="legalModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('ui.generator') </h5>
                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </x-button>
            </div>
            <form action="{{ route('panel.admin.document-generator.store-code') }}" method="POST"
                enctype="multipart/form-data" class="documentGenerateForm">
                @csrf
                <div class="modal-body">
                    <x-label name="choose_document" tooltip="" validation="empty" class="" />
                    <div class="row">
                        <div class="col-12">
                            <x-select name="legal" validation="legal" id="legalOnChange" class="form-control select2" label="{{ __('ui.select_document') }}" :value="old('legal')" optionName="title" :arr="$websitePages" />

                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="name_of_company" tooltip="" validation="empty" class="" />

                            <x-input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="name_of_company" name="name_of_company" placeholder="{{ __('ui.name_of_company') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="website_url" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="website_url" name="website_url" placeholder="{{ __('ui.website_url') }}" class="form-control" value="" pattern="^(https?|ftp):\/\/[^\s/$.?#].[^\s]*$" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="website_name" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="website_name" name="website_name" placeholder="{{ __('ui.website_name') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="entity_type" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="entity_type" name="entity_type" placeholder="{{ __('ui.entity_type') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="address" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="address" name="address" placeholder="{{ __('ui.address') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="phone" tooltip="" validation="empty" class="" />
                            <x-input type="tel" pattern="[0-9]+.*" title="Please enter number is required." title="Please enter number is required." id="phone" name="phone" placeholder="{{ __('ui.phone') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="email" tooltip="" validation="empty" class="" />
                            <x-input type="email" pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="email" name="email" placeholder="{{ __('ui.email') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="country" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="country" name="country" placeholder="{{ __('ui.country') }}" class="form-control" value="" required validation="empty" />
                        </div>
                        <div class="col-lg-6 mt-2">
                            <x-label name="state" tooltip="" validation="empty" class="" />
                            <x-input type="text" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." id="state" name="state" placeholder="{{ __('ui.state') }}" class="form-control" value="" required validation="empty" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-button type="submit" class="btn btn-block btn-primary">@lang('ui.generate') </x-button>
                </div>
            </form>
        </div>
    </div>
</div>
