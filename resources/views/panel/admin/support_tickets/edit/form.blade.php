<form action="{{ route('panel.admin.support-tickets.update', secureToken($supportTicket->id)) }}" method="post"
    class="ajaxForm">
    @csrf

    <x-input name="request_with" placeholder="" type="hidden" tooltip="" regex="" validation="empty"
        :value="'update'" />

    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('ui.user_name') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="user" validation="required" tooltip="edit_support_ticket_username" />
                                <x-select name="user_id" :value="$supportTicket->user_id" label="User"
                                    optionName="full_name" class="select2" valueName="id" :arr="@$users"
                                    validation="required" id="user_id" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('ui.assign_to_admin') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="assign_to_admin" validation="empty" tooltip="assign_ticket_to_admin" />
                                <x-select name="assign_to" :value="$supportTicket->assign_to"
                                    label="Assign to Admin" optionName="full_name" class="select2" valueName="id"
                                    :arr="$admins" validation="empty" id="assign_to" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>{{ __('ui.priority') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @php
                                    $priority_arr = collect(\App\Models\SupportTicket::PRIORITIES)
                                        ->map(function ($value, $key) {
                                            return [
                                                'value' => $key,
                                                'name' => strtolower($value['label']),
                                            ];
                                        })
                                        ->toArray();
                                @endphp
                                <x-label name="priority" validation="empty" tooltip="add_support_ticket_priority" />
                                <div class="radio radio-inline">
                                    <x-radio name="priority" type="radio" :value="@$supportTicket->priority"
                                        :arr="@$priority_arr" validation="empty" />
                                </div>
                                <x-message name="priority" :message="@$message" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('ui.category') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="category" validation="required" tooltip="edit_support_ticket_category" />
                                <div class="select-wrapper">
                                    <x-select name="ticket_type_id" :value="@$supportTicket->ticket_type_id"
                                        label="{{ __('ui.category') }}" optionName="name" valueName="id" class=""
                                        :arr="$categories" validation="required" id="type" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex=""
                validation="empty" value="update" />
            <div class="card">
                <div class="card-header justify-content-between">
                    <h3>{{ __('ui.edit_support_ticket') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="subject" validation="common_title"
                                    tooltip="add_support_ticket_subject" />
                                <x-select name="subject" :value="$supportTicket->subject" valueName=""
                                    validation="common_title" id="subject" class="select2" label="Subject"
                                    optionName="name" :arr="App\Models\SupportTicket::SUBJECTS" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group1">
                                <x-label name="body" validation="common_short_description"
                                    tooltip="edit_support_ticket_body" />
                                <x-textarea regex="name" validation="common_short_description"
                                    :value="$supportTicket->message ?? '--'" name="message" id="value"
                                    placeholder="{{ __('ui.type_message_here') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-button class="btn btn-primary floating-btn ajax-btn" type="submit">{{ __('ui.save_update') }}
        @lang('ui.ticket')</x-button>
</form>
@push('script')
    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/support-tickets') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    {{-- END AJAX FORM INIT --}}
@endpush
