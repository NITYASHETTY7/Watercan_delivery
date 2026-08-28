@extends('layouts.main')
@section('title', $userKyc->getPrefix() . ' - ' . __('ui.verification') . ' ' . __('ui.show'))
@section('content')

    @php
        /**
         * UserKyc
         *
         * @category  zStarter
         *
         * @ref  zCURD
         * @author    Book My Water <info@watercane.come>
         * @license  https://watercane-dev.dze-labs.in Book My Water
         * @version  <zStarter: 1.1.0>
         * @link        https://watercane-dev.dze-labs.in
         */
        @$breadcrumb_arr = [
            [
                'name' => __('ui.verification_status'),
                'url' => route('panel.admin.users.show', secureToken($userKyc->user->id)),
                'class' => '--',
            ],
            ['name' => __('ui.show'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
    @endphp


    @push('head')
        <style>
            .error {
                color: red;
            }

            .table thead {
                background-color: #fff;
            }

            .table thead th {
                bpayout-bottom: 0px;
            }

            p {
                margin-bottom: 0px;
            }

            .bpayout-none td {
                bpayout: none;
                padding-top: 0px;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>{{ @$userKyc->getPrefix() }}</h5>
                            <span>@lang('ui.requested_at') {{ @$userKyc->created_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <!-- start message area-->

                <div class="card mb-2">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="mb-0">@lang('ui.user_kyc_information') </h3>
                        <span
                            class="badge badge-{{ App\Models\UserKyc::STATUSES[$userKyc->status]['color'] }} m-1">{{ App\Models\UserKyc::STATUSES[@$userKyc->status]['label'] }}</span>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            @if (@$userKyc->user != null)
                                <tbody>
                                    <tr>
                                        <td class="p-0"> @lang('ui.name') </td>
                                        <td class="text-right p-0">
                                            <span id="copyname">{{ $userKyc->user->full_name ?? 'Not available' }}</span>
                                            @if (@$userKyc->user->full_name != null)
                                                <span><a href="javascript:void(0)" class="btn btn-icon btn-sm btn text-copy"
                                                        title="Copy" data-clipboard-target="#copyname"><i
                                                            class="ik ik-copy" aria-hidden="true"></i></a></span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0"> @lang('ui.email') </td>
                                        <td class="text-right p-0">
                                            <span id="copyemail">{{ $userKyc->user->email ?? 'Not available' }}</span>
                                            @if (@$userKyc->user->email)
                                                <span><a href="javascript:void(0)" class="btn btn-icon btn-sm btn text-copy"
                                                        title="Copy" data-clipboard-target="#copyemail"><i
                                                            class="ik ik-copy" aria-hidden="true"></i></a></span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="p-0"> @lang('ui.phone') </td>
                                        <td class="text-right p-0">
                                            <span id="copyphone">{{ $userKyc->user->phone ?? 'Not available' }}</span>
                                            @if (@$userKyc->user->phone != null)
                                                <span><a href="javascript:void(0)" class="btn btn-icon btn-sm btn text-copy"
                                                        title="Copy" data-clipboard-target="#copyphone"><i
                                                            class="ik ik-copy" aria-hidden="true"></i></a></span>
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="mb-0"><i class="fa fa-credit-card-alt"></i>
                            @lang('ui.kyc_details') </h3>
                    </div>
                    <div class="card-body">
                        <h6 class="fw-700"> {{ App\Models\UserKyc::TYPES[$userKyc->type]['label'] }} @lang('ui.details')</h6>
                        <table class="table mt-2">
                            <tbody>
                                <tr>
                                    <td class="p-0">@lang('ui.document_name')</td>
                                    <td class="p-0 text-right">
                                        {{ $userKyc->details['document_name'] ?? '--' }}<span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-0">@lang('ui.name')</td>
                                    <td class="p-0 text-right">
                                        {{ $userKyc->details['name'] ?? '--' }}<span>
                                    </td>
                                </tr>
                                <tr class="bpayout-none">

                                    <td class="p-0">@lang('ui.document_no')</td>
                                    <td class="p-0 text-right">
                                        {{ $userKyc->details['document_number'] ?? '--' }}

                                    </td>
                                </tr>
                                <tr class="bpayout-none">
                                    <td class="p-0">@lang('ui.documents')</td>
                                    <td class="p-0 text-right">
                                        @foreach ($userKyc->getMedia('document_attachment') as $media)
                                            <a href="#" class="media-name"
                                                data-src="{{ $media->getFullUrl() }}">{{ $media->name }}</a><br>
                                        @endforeach

                                    </td>
                                </tr>
                            </tbody>
                        </table>


                        @if ($userKyc->status == \App\Models\UserKyc::STATUS_UNDER_APPROVAL)
                            <div>
                                <form action="{{ route('panel.admin.user-kyc.update-status', secureToken($userKyc->id)) }}"
                                    method="post" class="ajaxForm" class="mt-4">
                                    @csrf
                                    <x-input type="hidden" name="request_with" value="update-status" validation="empty" />
                                    @php
                                        $radio_arr = [
                                            ['name' => 'approve_mark', 'value' => 1],
                                            ['name' => 'reject_request', 'value' => 2],
                                        ];
                                    @endphp
                                    <x-radio name="status" value="{{ @$userKyc->status }}" :arr="$radio_arr"
                                        class="updateStatusBtn" validation="empty" tooltip="" data-custom="example" />

                                    <div class="form-group d-none txn-wrap mt-2">
                                        <x-label name="enter_remark" validation="empty" />
                                        <x-textarea name="confirmation_remark" placeholder="{{ __('ui.enter_remark_here') }}" type="text" regex="alpha_numeric" value="{{ old('confirmation_remark') }}" validation="empty" />
                                    </div>
                                    <div class="form-group d-none remark-wrap mt-2">
                                        <x-label name="enter_rejection_reason" validation="required" />
                                        <x-textarea name="rejection_remark" id="remarkBox" class="form-control" placeholder="{{ __('ui.enter_reason_here') }}" regex="alpha_numeric" value="{{ old('remark') }}" label="Remarks" validation="empty" />
                                    </div>

                                    <hr>
                                    <div id="show-btn" class="d-none">
                                        <div class="mt-3 d-flex justify-content-between">
                                            <div class="text-danger mt-2">
                                                <i class="ik ik-info"></i>
                                                @lang('ui.rollback')
                                            </div>
                                            <x-button class="btn btn-primary confirm-form-btn"
                                                type="submit">{{ __('ui.confirm_action') }}
                                            </x-button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @elseif($userKyc->status == \App\Models\UserKyc::STATUS_VERIFIED)
                            <span class="alert alert-success d-block">
                                {{ App\Models\UserKyc::TYPES[@$userKyc->type]['label'] }}
                                @if (isset($userKyc->details['remark']))
                                    @lang('ui.with')
                                    {{ @$userKyc->details['remark'] }}
                                @endif
                                @lang('ui.submission_request_approved') <strong>{{ @$userKyc->txn_no }}</strong> by
                                <strong>{{ @\App\Models\User::whereId(@$userKyc->details['action_by'])->first()->name ?? auth()->user()->name }}</strong>
                                At {{ @$userKyc->updated_at }}
                            </span>
                        @else
                            <span class="alert alert-danger d-block">
                                @lang('ui.submission_request_rejected') <strong>
                                    @if (isset($userKyc->details['rejection_remark']))
                                        @lang('ui.due_to')
                                        {{ $userKyc->details['rejection_remark'] ?? '--' }}
                                    @endif
                                </strong> by
                                <strong>{{ @\App\Models\User::whereId(@$userKyc->details['action_by'])->first()->name ?? auth()->user()->name }}</strong>
                                At {{ @$userKyc->updated_at ?? '--' }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@push('script')
    {{-- INCLUDE CLIPBOARD COPY CDN --}}
    <script src="{{ asset($master_root_directory . 'plugins/clipboard/clipboard.min.js') }}"></script>
    {{-- END INCLUDE CLIPBOARD COPY CDN --}}

    {{-- START COPY BUTTON INIT --}}
    <script>
        $(document).ready(function() {
            // Handle input event on the mobile number field
            $('#txn_no').on('input', function() {
                // Remove any non-numeric characters
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });
    </script>
    {{-- END COPY BUTTON INIT --}}

    {{-- COPY TEXT CODE --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var clipboard = new ClipboardJS('.text-copy');

            clipboard.on('success', function(e) {
                console.log('Copied:', e.text);
                e.clearSelection();

                // Add 'Copied' message temporarily
                var originalTitle = e.trigger.title; // Save the original title
                e.trigger.innerHTML = 'Copied!';

                setTimeout(function() {
                    e.trigger.innerHTML = '<i class="ik ik-copy"></i>'; // Restore original content
                    e.trigger.title = originalTitle; // Restore the title
                }, 1000); // Show "Copied!" for 1 second
            });

            clipboard.on('error', function(e) {
                console.error('Error copying:', e);
            });
        });
    </script>
    {{-- END COPY TEXT CODE --}}

    {{-- START UPDATE STATUS BUTTON INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null);

            if (typeof(response) != "undefined" && response !== null && response.status == "success") {
                window.location.reload();
            }
        });

        $(document).ready(function() {
            $('.transactionCreate').on('click', function() {
                var status = $(this).data('status');
                $('#status').val(status);
                $('#transactionCreate').modal('show');
            });
            $('.updateStatusBtn').on('click', function() {
                $('#show-btn').removeClass('d-none');
                if ($(this).val() == 1) {
                    $('.txn-wrap').removeClass('d-none');
                    $('#remarkBox').removeAttr('required');
                    $('#txn_no').prop('required', 'required');
                    $('.remark-wrap').addClass('d-none');
                } else {
                    $('.remark-wrap').removeClass('d-none');
                    $('#remarkBox').prop('required', 'required');
                    $('#txn_no').removeAttr('required');
                    $('.txn-wrap').addClass('d-none');
                }
            });
        });
    </script>
    {{-- END UPDATE STATUS BUTTON INIT --}}
@endpush
