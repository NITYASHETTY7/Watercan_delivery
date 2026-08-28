<form class="ajaxForm" method="POST" action="{{ route('panel.admin.branches.store') }}" autocomplete="off">
    @csrf
    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <div class="row">
        <div class="col-md-8 mx-auto">
            @include('panel.admin.include.message')
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.branch') @lang('ui.details')</h3>
                </div>
                <div class="card-body" style="margin-top: -20px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="name" validation="common_name" />
                                <x-input name="name" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text" regex="name" validation="common_name" value="{{ old('name') }}" />
                                <x-message name="name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="address" validation="common_name" />
                                <x-textarea name="address" placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" type="text" validation="common_name" value="{{ old('address') }}" />
                                <x-message name="address" :message="@$message" validation="empty" />
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
            var redirectUrl = "{{ url('admin/branches') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
