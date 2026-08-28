<form class="forms-sample ajaxForm" method="POST"
    action="{{ route('panel.admin.zone-pincodes.update', secureToken($zonePincode->id)) }}">
    <div class="row">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @csrf

        <x-input name="id" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="{{ $zonePincode->id ?? '' }}" />
        <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="update" />

        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.zone') @lang('ui.pincode') @lang('ui.details') </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="pincode" validation="required" />
                                <x-input name="pincode" placeholder="{{ __('ui.enter') . ' ' . __('ui.pincode') }}" type="number" regex="name" validation="required" value="{{ $zonePincode->pincode }}" />
                                <x-message name="pincode" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('ui.save_update')</x-button>
</form>
@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            let route = $(this).attr('action');
            let method = $(this).attr('method');
            let data = new FormData(this);
            var redirectUrl = "{{ url('admin/zone-pincodes') }}" + "?zone_id=" + "{{ secureToken($zonePincode->zone_id) }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
