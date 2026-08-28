<form class="ajaxForm" method="POST" action="{{ route('panel.admin.products.store') }}" autocomplete="off">
    @csrf
    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <div class="row">
        <div class="col-md-8 mx-auto">
            @include('panel.admin.include.message')
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.product') @lang('ui.details')</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="name" validation="common_name" />
                                <x-input name="name" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text" regex="name" validation="common_name" value="{{ old('name') }}" />
                                <x-message name="name" :message="@$message" validation="empty" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="price" validation="required" />
                                <x-input name="price" placeholder="{{ __('ui.enter') . ' ' . __('ui.price') }}" type="number" step="any" regex="price" validation="price" value="{{ old('price') }}" icon="post: {{ getSetting('app_currency') }}" validation="required" />
                                <x-message name="price" :message="@$message" validation="required" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="base_price" validation="required" />
                                <x-input name="base_price" placeholder="{{ __('ui.enter') . ' ' . __('ui.base_price') }}" type="number" step="any" regex="base_price" validation="base_price" value="{{ old('base_price') }}" icon="post: {{ getSetting('app_currency') }}" validation="required" />
                                <x-message name="base_price" :message="@$message" validation="required" />
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <x-label name="weight" validation="required" />
                                <x-input name="weight" placeholder="{{ __('ui.enter') . ' ' . __('ui.weight') }}" type="number" step="any" regex="weight" validation="weight" value="{{ old('weight') }}" icon="post: LTR" validation="required" />
                                <x-message name="weight" :message="@$message" validation="required" />
                            </div>
                        </div>
                        <div class="col-md-12 d-flex align-items-center">
                            <div class="form-group">
                                <x-label name="is_published" class="mr-2" validation="empty" />
                                <x-checkbox class="js-switch switch-input" validation="empty" type="checkbox" value="1" name="is_published" id="is_published" placeholder="Select is_published" checked />
                                <x-message name="is_published" :message="@$message" validation="empty" />
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
            var redirectUrl = "{{ url('admin/products') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl, form);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
