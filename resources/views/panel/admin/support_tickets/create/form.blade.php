<form action="{{ route('panel.admin.support-tickets.store') }}" method="post" class="ajaxForm row">
    @csrf
    <div class="col-md-4 mx-auto">
        <div class="card mb-2">
            <div class="card-header">
                <h3> @lang('ui.ticket_for') </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="user" tooltip="add_support_ticket_username" validation="required" />
                            <x-select name="user_id" id="user_id" class="form-control getUsersList" validation="required" :value="old('user_id')" label="User" option_name="name" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
                <h3> @lang('ui.priority') </h3>
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
                                    :arr="$priority_arr" validation="empty" />
                            </div>

                            <x-message name="priority" :message="@$message" validation="empty" />
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-header">
                <h3> @lang('ui.category') </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="category" validation="required" tooltip="add_support_ticket_category" />
                            <x-select name="ticket_type_id" :value="old('ticket_type_id')" label="Status" optionName="name" valueName="id" class="select2" :arr="@$categories" validation="required" id="supportTicketCategoryId" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 mx-auto sticky">

        <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" :value="'create'" />
        <div class="card mb-2">
            <div class="card-header d-flex justify-content-between">
                <h3> @lang('ui.ticket_details') </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="subject" validation="common_title" tooltip="add_support_ticket_subject" />
                            <x-select name="subject" :value="old('subject')" valueName="" validation="common_title" id="subject" class="select2 select2" label="Subject" optionName="name" :arr="App\Models\SupportTicket::SUBJECTS" />

                        </div>
                        <div class="form-group1">
                            <x-label name="body" validation="common_short_description" tooltip="add_support_ticket_body" />
                            <x-textarea regex="name" validation="common_short_description" :value="old('message')" name="message" id="value" placeholder="{{ __('ui.type_message_here') }}" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-button class="btn btn-primary floating-btn ajax-btn" type="submit"> @lang('ui.create_ticket') </x-button>
</form>
@push('script')
    <script src="{{ asset($master_root_directory . 'plugins/jquery-validate-1.19.3/jquery.validate.js') }}"></script>
    @include('panel.admin.include.script.modal_script.index')

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var redirectUrl = "{{ url('admin/support-tickets') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        })
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START GET USER INIT --}}
    <script>
        $(document).ready(function() {
            getUsers();
        })
    </script>
    {{-- END GET USER INIT --}}
@endpush
