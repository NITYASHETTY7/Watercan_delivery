<form class="forms-sample" method="POST" action="{{ route('panel.admin.roles.update', secureToken($role->id)) }}">
    <div class="card-header d-flex justify-content-between">
        <h3>{{ __('ui.edit') . ' ' . ($role->name ?? '') }}</h3>
        <div class="form-group">
            <x-button type="submit" class="btn btn-primary btn-rounded ajax-btn"> @lang('ui.update')
            </x-button>
        </div>
    </div>
    <div class="card-body">
        @csrf
        <x-input name="request_with" placeholder="Enter Role Name" type="hidden" tooltip="" regex="text" validation="common_name" value="update" />
        <x-input name="id" placeholder="Enter Name" type="hidden" tooltip="" regex="positive_number"
            validation="number" value="{{ @$role->id }}" />
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <x-label name="role_name" validation="common_name" tooltip="add_role_name" />
                    <x-input name="role" placeholder="{{ __('ui.enter') . ' ' . __('ui.name') }}" type="text"
                        tooltip="add_role_name" regex="text" validation="common_name" value="{{ @$role->name }}" />
                </div>
                <div class="form-group">
                    <x-label name="display_name" validation="common_name" tooltip="add_role_display_name" />
                    <x-input name="display_name" placeholder="{{ __('ui.display_name') }}" type="text"
                        tooltip="add_role_display_name" regex="text" validation="common_name"
                        value="{{ @$role->display_name }}" />
                </div>
                <div class="form-group">
                    <x-label name="description" validation="common_short_description" tooltip="add_role_description" />
                    <x-textarea regex="name" validation="common_description" value="{{ @$role->description ?? '--' }}"
                        name="description" id="description"
                        placeholder="{{ __('ui.enter') . ' ' . __('ui.description') }}" />
                </div>
            </div>
            <div class="col-sm-8">
                <div class="d-flex justify-content-between">
                    <h6>@lang('ui.assign_permissions') </h6>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="all_item"
                            name="privileges[all_item]" @if (@$role_permission != null) checked @endif
                            validation="empty">
                        <label class="pt-1 form-check-label" for="all_item">
                            @lang('ui.select_all')
                        </label>
                    </div>
                </div>
                <hr class="mb-0">
                <div class="row mb-3">
                    @foreach (@$allPermissions as $permission)
                        <div class="col-sm-4">
                            <div class="mt-3 mb-0">
                                <label for="" class="fw-700 m-0">{{ __(@$permission->group) }}</label>
                            </div>
                            @foreach (explode(',', @$permission->permission_ids) as $key => $permission_id)
                                <label class="custom-control custom-checkbox mb-0">
                                    <!-- check permission exist -->
                                    <input type="checkbox" class="custom-control-input bulk-group" id="item_checkbox"
                                        name="permissions[]" value="{{ @$permission_id }}"
                                        @if (in_array(@$permission_id, @$role_permission)) checked @endif>
                                    <span class="custom-control-label">
                                        <!-- clean unescaped data is to avoid potential XSS risk -->
                                        {{ formatDisplayName(explode(',', @$permission->permission_names)[@$key]) }}
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</form>

@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);
            var redirectUrl = "{{ url('panel/admin/users') }}" + '?role=' + response.role;
            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                window.location.href = redirectUrl;
            }
        });

        $('#all_item').on('change', function() {
            if (this.checked) {
                $('.bulk-group').prop('checked', true);
            } else {
                $('.bulk-group').prop('checked', false);
            }
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
