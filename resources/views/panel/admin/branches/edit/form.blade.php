<form class="forms-sample ajaxForm" method="POST"
    action="{{ route('panel.admin.branches.update', secureToken($branch->id)) }}">
    <div class="row">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @csrf

        <x-input name="id" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
            value="{{ $branch->id ?? '' }}" />
        <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="update" />

        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.branch') @lang('ui.details') </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="name" validation="common_name" />
                                <x-input name="name" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text" regex="name" validation="common_name" value="{{ $branch->name }}" />
                                <x-message name="name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="address" validation="common_name" />
                                <x-textarea name="address" placeholder="{{ __('ui.enter') . ' ' . __('ui.address') }}" type="text" validation="common_name" value="{{ $branch->address }}" />
                                <x-message name="address" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('ui.save_update')
    </x-button>
</form>
@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            let route = $(this).attr('action');
            let method = $(this).attr('method');
            let data = new FormData(this);
            var redirectUrl = "{{ url('admin/branches') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
