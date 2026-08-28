<style>
    .modal-content-width {
        width: 170% !important;
    }

    .alert-warning-custom {
        padding: 0.75rem 1rem !important;
    }
</style>
<div class="modal fade" id="BulkStoreAgentModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="updateProfileImageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-center">
        <div class="modal-content modal-content-custom">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('ui.create_update')</h5>
                <form action="{{ route('panel.admin.support-tickets.export') }}" method="POST"
                    enctype="multipart/form-data" onsubmit="return checkCoords();">
                    @csrf
                    <div class="d-flex justify-content-between mt-0">
                        <div class="text-right">
                            <x-button type="submit" class="btn btn-link">
                                @lang('ui.download_prefill_excel')
                            </x-button>
                        </div>
                    </div>
                </form>
                <x-button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </x-button>
            </div>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="previous-month" role="tabpanel"
                    aria-labelledby="pills-setting-tab">
                    <div class="modal-body">

                        <form action="{{ route('panel.admin.bulk.tickets') }}" method="POST"
                            enctype="multipart/form-data" onsubmit="return checkCoords();">
                            @csrf
                            <div>
                                <div class="alert alert-warning alert-warning-custom">
                                    <p class="mb-0">@lang('ui.for_updating_existing') {{ @$label }}, @lang('ui.please_leave_id_column')</p>
                                </div>
                                <div class="alert alert-info alert-warning-custom">
                                    <p class="mb-0">@lang('ui.user_id_required')</p>
                                    <p class="mb-0">@lang('ui.subject_field_required')</p>
                                    <p class="mb-0">@lang('ui.message_field_required')</p>
                                    <p class="mb-0">@lang('ui.priority_field_required')
                                        @foreach(App\Models\SupportTicket::PRIORITIES as $priorityKey => $priority)
                                            <li> {{ $priority['label'] }} = {{ $priorityKey }}.</li>
                                        @endforeach
                                    </p>
                                    <p class="mb-0">@lang('ui.ticket_type_id_required')
                                        @foreach(getCategoriesByCode('SupportTicketCategories') as $ticketCategory)
                                            <li> {{ $ticketCategory->name }} = {{ $ticketCategory->id }}.</li>
                                        @endforeach
                                    </p>
                                </div>
                            </div>

                            <div class="form-group mt-5">
                                <x-label for="agents_bulk_update" class="form-label" name="update_records" /> <br>
                                <x-file name="file" version="choose" class="" validation="excel" accept=".xlsx,.xls" />
                            </div>


                            <div class="modal-footer">
                                <x-button type="submit" class="btn btn-primary">@lang('ui.update')</x-button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
