
<form class="ajaxForm" method="POST" action="{{ route('panel.admin.zone-pincodes.store') }}" autocomplete="off">
    @csrf
    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <x-input name="zone_id" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="{{ request()->get('zone_id') }}" />
    <div class="row">
        <div class="col-md-8 mx-auto">
            @include('panel.admin.include.message')
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.zone') @lang('ui.pincode') @lang('ui.details')</h3>
                </div>
                <div class="card-body negative-margin">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="pincode" validation="required" />
                                <x-input name="pincode" placeholder="{{ __('ui.enter') . ' ' . __('ui.pincode') }}" type="number" regex="pincode" validation="required" value="{{ old('name') }}" />
                                <x-message name="pincode" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-button class="btn btn-primary floating-btn ajax-btn" type="submit">
        @lang('ui.create')
    </x-button>
</form>

@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var route = form.attr('action');
            var method = form.attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/zone-pincodes') }}" + "?zone_id=" + "{{ request()->get('zone_id') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
