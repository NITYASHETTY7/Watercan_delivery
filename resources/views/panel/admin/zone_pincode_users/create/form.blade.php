
<form class="ajaxForm" method="POST" action="{{ route('panel.admin.zone-pincode-users.store') }}" autocomplete="off">
    @csrf
    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <x-input name="zone_pincode_id" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="{{ request()->get('zone_pincode_id') }}" />
    <div class="row">
        <div class="col-md-8 mx-auto">
            @include('panel.admin.include.message')
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.zone') @lang('ui.pincode') @lang('ui.driver') @lang('ui.details')</h3>
                </div>
                <div class="card-body negative-margin">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="driver" validation="required" />
                                <x-select name="user_id[]" validation="required" id="user" 
                                data-placeholder="Select Drivers"
                                class="form-control select2 getUsersList" label="driver" value="{{ old('user') }}" optionName="name" isMultiple="1" />
                                <x-message name="driver" :message="@$message" validation="empty" />
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
            var redirectUrl = "{{ url('admin/zone-pincode-users') }}" + "?zone_pincode_id=" + "{{ request()->get('zone_pincode_id') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
