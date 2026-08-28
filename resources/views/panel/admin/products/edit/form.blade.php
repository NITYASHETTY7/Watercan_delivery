<form class="forms-sample ajaxForm" method="POST"
    action="{{ route('panel.admin.products.update', secureToken($product->id)) }}">
    <div class="row">
        <!-- start message area-->
        @include('panel.admin.include.message')
        <!-- end message area-->
        @csrf

        <x-input name="id" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
            value="{{ $product->id ?? '' }}" />
        <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
            value="update" />

        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3> @lang('ui.product') @lang('ui.details') </h3>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="price" validation="required" />
                                <x-input name="price" id="price"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.price') }}" type="number"
                                    step="any" min="0" regex="price" validation="price"
                                    value="{{ $product->price }}" icon="post: {{ getSetting('app_currency') }}" />
                                <x-message name="price" :message="@$message" validation="required" />
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="base_price" validation="required" />
                                <x-input name="base_price" id="base_price"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.base_price') }}" type="number"
                                    step="any" min="0" regex="base_price" validation="base_price"
                                    value="{{ $product->base_price }}" icon="post: {{ getSetting('app_currency') }}" />
                                <x-message name="base_price" :message="@$message" validation="required" />
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
            var redirectUrl = "{{ url('admin/products') }}";
            var response = postData(method, route, 'json', data, null, null, '1', true, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}
    <script>
    document.addEventListener("DOMContentLoaded", () => {
    
        const price = document.getElementById("price");
        const basePrice = document.getElementById("base_price");
    
        function validateBasePrice() {
            if (parseFloat(basePrice.value) < parseFloat(price.value)) {
                basePrice.setCustomValidity("Base price cannot be less than price");
            } else {
                basePrice.setCustomValidity("");
            }
        }
    
        price.addEventListener("input", validateBasePrice);
        basePrice.addEventListener("input", validateBasePrice);
    
    });
    </script>
@endpush


