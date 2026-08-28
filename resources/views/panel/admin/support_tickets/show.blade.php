@extends('layouts.main')
@section('title', $supportTicket->getPrefix() . ' - ' . __('ui.show_support_ticket'))
@section('content')
    @php
        @$breadcrumb_arr = [
            ['name' => $label, 'url' => route('panel.admin.support-tickets.index'), 'class' => ''],
            ['name' => $supportTicket->getPrefix(), 'url' => route('panel.admin.support-tickets.show', secureToken($supportTicket->id)), 'class' => ''],
            ['name' => __('ui.show'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
        $is_chat_allowed = true;
    @endphp

    <div class="container-fluid mb-0">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ $supportTicket->getPrefix() ?? '' }}</h5>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>
        <div class="row mb-0">
            <div class="col-md-8 col-lg-8 mx-auto">
                <div class="card mb-0">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">
                            {{ @$supportTicket->user->full_name ?? '' }}
                        </h5>
                        <div>
                            <span
                                class="badge badge-{{ @\App\Models\SupportTicket::STATUSES[@$supportTicket->status]['color'] }}">{{ @\App\Models\SupportTicket::STATUSES[@$supportTicket->status]['label'] }}</span>
                            @if (@$supportTicket->status == App\Models\SupportTicket::STATUS_UNDER_WORKING)
                                <x-button class="dropdown-toggle p-0 custom-dopdown mt-2 border-0 bg-custom" type="button"
                                    id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ik ik-more-vertical pl-1"></i></x-button>

                                <ul class="dropdown-menu">
                                    <li>
                                        <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                            data-bs-target="#resolveModal">
                                            @lang('ui.marked_ticket_resolved')
                                        </button>
                                    </li>
                                </ul>

                                {{-- <form
                                    action="{{ route('panel.admin.support-tickets.status', ['id' => secureToken($supportTicket->id), 'status' => App\Models\SupportTicket::STATUS_RESOLVED]) }}"
                                    method="get" id="updateStatus">
                                    @csrf
                                </form> --}}
                            @endif

                        </div>
                    </div>
                    <div class="card-body">
                        <div style="max-height:360px;overflow:auto;overflow-x:hidden;">

                            <h6>
                                {!! nl2br(@$supportTicket->subject) !!}
                            </h6>
                            <p class="text-muted">
                                <i class="ik ik-arrow-down-right"></i>
                                {!! nl2br(@$supportTicket->message) !!}
                            </p>

                            @if (!empty($supportTicket->reply))
                                <div class="mt-2">
                                    <strong>Remark:</strong>
                                    <p class="text-dark">{!! nl2br(e($supportTicket->reply)) !!}</p>
                                </div>
                            @endif
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <a class="btn btn-link text-muted fw-700 p-0 mb-0"
                                href="tel:{{ @$supportTicket->user->phone ?? '' }}">
                                <i class="fa fa-phone"></i>
                                +{{ @$supportTicket->user->country_code }} {{ @$supportTicket->user->phone ?? '' }}
                            </a>
                            <a class="btn btn-link text-muted fw-700 p-0 mb-0"
                                href="mailto:{{ @$supportTicket->user->email ?? '' }}">
                                <i class="fa fa-envelope"></i>
                                {{ @$supportTicket->user->email ?? '' }}
                            </a>
                            <div class="text-muted fw-600" title="Last Updated At">
                                <i class="fas fa-clock"></i>
                                {{ @$supportTicket->updated_at ?? '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="resolveModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <form action="{{ route('panel.admin.support-tickets.status') }}" method="POST">
                    @csrf
                    <input type="hidden"name="id" value="{{ secureToken($supportTicket->id) }}">
                    <input type="hidden"name="status" value="{{ \App\Models\SupportTicket::STATUS_RESOLVED }}">

                    <div class="modal-header">
                        <h5 class="modal-title">Add Remark</h5>
                        <button type="button" class="close" data-bs-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <label class="form-label">Remark <span class="text-danger">*</span></label>
                        <textarea name="reply" id="remark" class="form-control" rows="4" required></textarea>

                        <p class="text-danger mt-1 d-none" id="remarkError">Remark is required.</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>

                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>




    @include('panel.admin.support_tickets.include.video.index')
@endsection
@push('script')
    <script>
        document.getElementById('submitResolve').addEventListener('click', function() {
            const remark = document.getElementById('remark');
            const error = document.getElementById('remarkError');

            if (remark.value.trim() === '') {
                error.classList.remove('d-none'); // show error
                remark.classList.add('is-invalid'); // highlight textarea
                return;
            }

            // Hide error before submit
            error.classList.add('d-none');
            remark.classList.remove('is-invalid');

            // Submit the form
            document.getElementById('updateStatusForm').submit();
        });
    </script>
@endpush
