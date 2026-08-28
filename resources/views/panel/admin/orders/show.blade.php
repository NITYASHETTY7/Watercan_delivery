@extends('layouts.main')
@section('title', $order->getPrefix() . ' - ' . __('ui.order_show'))
@section('content')
    @php
        /**
         * Order
         *
         * @category  zStarter
         *
         * @ref  zCURD
         * @author    Book My Water <info@watercane.come>
         * @license  https://watercane-dev.dze-labs.in Book My Water
         * @version  <zStarter: 1.1.0>
         * @link        https://watercane-dev.dze-labs.in
         */
        use Carbon\Carbon;
        $carbonObj = new Carbon();
        $breadcrumb_arr = [
            ['name' => $label, 'url' => route('panel.admin.orders.index'), 'class' => '--'],
            ['name' => $order->getPrefix(), 'url' => route('panel.admin.orders.index'), 'class' => '--'],
            ['name' => __('ui.show'), 'url' => 'javascript:void(0);', 'class' => 'active'],
        ];
        @$from = @$order->from;
        @$to = @$order->to;
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
                border-bottom: 0px;
            }

            .select2-selection__rendered {
                width: 150px;
            }

            .select2-container {
                width: 220px !important;
            }

            .select2.select2-container--default .select2-selection--single .select2-selection__rendered {
                width: 200px !important;
            }

            .select2-container {
                width: 100% !important;
            }

            .remove-border-top th {
                border-top: 0;
            }
        </style>
    @endpush

    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end mb-4">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-grid bg-blue"></i>
                        <div class="d-inline">
                            <h5>Order ID: {{ @$order->getPrefix() }}</h5>
                            <span>Transaction No:
                                <strong class="text-muted">{{ @$order->txn_no ?? 'N/A' }}</strong>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 d-sm-flex d-lg-block">
                    @include('panel.admin.include.breadcrumb.index')
                </div>
            </div>
        </div>

        @if (!$order->assign_to)
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info mb-4">
                        <i class="ik ik-info"></i> No driver has been assigned to this order yet.
                    </div>
                </div>
            </div>
        @endif

        @include('panel.admin.include.message')

        <div class="row">


            <div class="col-md-6 pr-md-0">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fa fa-cart-arrow-down mr-1"></i> Product Details</h3>
                        <div>
                            Payment Status:
                            <span
                                class="badge badge-{{ @\App\Models\Order::PAYMENT_STATUSES[@$order->payment_status]['color'] }} p-2 m-1 status-change">
                                {{ @\App\Models\Order::PAYMENT_STATUSES[@$order->payment_status]['label'] }}
                            </span>
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        <div class="mb-4" style="min-height: 100px !important;">
                            <table class="table table-hover">
                                <thead>
                                    <tr class="remove-border-top">
                                        <th class="col_1 no-export"> S.No. </th>
                                        <th> Item Name </th>
                                        <th> Quantity </th>
                                        <th> Rate (per unit) </th>
                                        <th> Total </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Loop through the collection of order items --}}
                                    @foreach ($orderItems as $orderItem)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $orderItem->product->name ?? 'N/A' }}</td>
                                            <td>{{ $orderItem->qty }}</td>
                                            <td>{{ format_price($orderItem->rate) }}</td>
                                            <td>{{ format_price($orderItem->price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-lg-6">
                                {{-- Empty for layout --}}
                            </div>
                            <div class="col-lg-6">
                                <table class="table table-sm pricing-table">
                                    <tr>
                                        <th class="border-top-0 p-2"> Sub Total (Excl. Tax) </th>
                                        <td class="text-right border-top-0 p-2">
                                            <span
                                                style="font-family: DejaVu Sans; sans-serif;"></span>{{ format_price($order->sub_total ?? 0) }}
                                        </td>
                                    </tr>
                                    @php
                                        $taxPercent = $order->tax_percent ?? 18;
                                        $halfPercent = $taxPercent / 2;

                                        $taxAmount = $order->tax_amount ?? 0;
                                        $halfAmount = $taxAmount / 2;
                                    @endphp

                                    <tr>
                                        <td class="p-2">CGST ({{ $halfPercent }}%)</td>
                                        <td class="text-right p-2">
                                            <span
                                                style="font-family: DejaVu Sans; sans-serif;"></span>{{ format_price($halfAmount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="p-2">SGST ({{ $halfPercent }}%)</td>
                                        <td class="text-right p-2">
                                            <span
                                                style="font-family: DejaVu Sans; sans-serif;"></span>{{ format_price($halfAmount) }}
                                        </td>
                                    </tr>

                                    <tr class="bg-light">
                                        <th class="p-2">
                                            <h6 class="fw-700 mb-0"> Grand Total</h6>
                                        </th>
                                        <td class="text-right p-2">
                                            <h6 class="fw-700 mb-0">{{ format_price(@$order->total ?? '--') }}</h6>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <div class="col-md-12">
                            <div class="row ">
                                <div class="col-md-6 px-0">
                                    <span class="text-muted ">Order Date <br></span>
                                    <strong class="text-color-white">
                                        {{ @$carbonObj->parse(@$order->date)->format('jS M Y') }}
                                    </strong>
                                    <div class="mt-2">
                                        <span class="text-muted">Last Updated At <br></span>
                                        <strong
                                            class="text-color-white">{{ @$order->updated_at ? $order->updated_at->format('d-m-Y H:i:s') : '--' }}
                                        </strong>
                                    </div>
                                    @if (@$carbonObj->parse(@$order->date)->addDays(7) < now())
                                        <br><span class="fw-700 text-warning">Order Delayed</span>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-0">
                                        <span class="fw-700">Payment Mode:</span>
                                        Razorpay
                                    </p>
                                    <p class="mb-2">
                                        <span class="fw-700"> Type:</span>
                                        {{ @\App\Models\Order::TYPES[@$order->type]['label'] ?? '--' }}
                                    </p>

                                    {{-- Subscription Details --}}
                                    @if ($order->type == App\Models\Order::TYPE_SUBSCRIPTION)
                                        <hr class="my-2">
                                        <span class="fw-700 mb-1 d-block">
                                            Subscription Details
                                        </span>
                                        <p class="mb-0">Date:
                                            {{ @\Carbon\Carbon::parse($order->start_date)->format('d/m/Y') ?? '--' }} -
                                            {{ @\Carbon\Carbon::parse($order->end_date)->format('d/m/Y') ?? '--' }}
                                        </p>
                                        <p class="mb-0">Subscription Type:
                                            {{ @\App\Models\Order::SCHEDULE_TYPES[@$order->schedule_type]['label'] ?? '--' }}
                                        </p>

                                        {{-- Conditional Schedule Display --}}
                                        @if (@$order->schedule_value && !empty($order->schedule_value))
                                            <p class="mb-2">
                                                @php
                                                    $scheduleHeading =
                                                        @$order->schedule_type ==
                                                        \App\Models\Order::SCHEDULE_TYPE_MONTHLY
                                                            ? 'Schedule Dates'
                                                            : 'Schedule Days';

                                                    // Handle schedule_value, which can be a JSON string or array
                                                    $schedule = is_array($order->schedule_value)
                                                        ? $order->schedule_value
                                                        : json_decode($order->schedule_value, true) ??
                                                            $order->schedule_value;

                                                    $filtered_schedule = is_array($schedule)
                                                        ? array_filter($schedule)
                                                        : $schedule;
                                                    $is_empty_schedule = empty($filtered_schedule);
                                                @endphp

                                                <span class="fw-600">{{ $scheduleHeading }}:</span>

                                                @if (!$is_empty_schedule)
                                                    @if (is_array($filtered_schedule))
                                                        {{ implode(', ', $filtered_schedule) }}
                                                    @else
                                                        {{ $filtered_schedule }}
                                                    @endif
                                                @else
                                                    Every Day
                                                @endif
                                            </p>
                                        @endif
                                    @endif

                                </div>
                                <div class="col-md-12 px-0">
                                    @if ($order->remark)
                                        <hr class="my-2">
                                        <span class="fw-700">
                                            Remark:</span>
                                        <p class="mt-1 mb-0">{!! nl2br(e($order->remark)) !!}</p>
                                    @endif
                                </div>
                            </div>
                            <hr class="">
                        </div>

                        <div class="d-md-flex d-sm-block justify-content-between align-items-center mt-4">
                            <div class="d-flex flex-wrap" style="gap:1rem;">

                                {{-- Payment Status Update --}}
                                @if (@$order->payment_status != App\Models\Order::PAYMENT_STATUS_PAID)
                                    @php
                                        $paymentStatusOptions = [
                                            \App\Models\Order::PAYMENT_STATUS_PAID => __('ui.paid'),
                                        ];
                                    @endphp
                                    <div class="mr-3 mb-2 mb-md-0" style="min-width: 180px;">
                                        <form
                                            action="{{ route('panel.admin.orders.payment-status-update', secureToken($order->id)) }}"
                                            method="get" id="updatePaymentStatus">
                                            @csrf
                                            <x-select name="payment_status" id="payment_status"
                                                class="form-control select2 w-100" label="Update Payment Status"
                                                :value="$order->payment_status ?? old('payment_status')" :arr="$paymentStatusOptions" optionName="" validation="empty" />
                                        </form>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6 pr-md-0">

                <div class="card mb-3 pb-2">
                    <div class="card-header justify-content-between">
                        <h6 class="mb-0">Customer, Locations & Driver Details</h6>
                        <div class="d-flex align-items-center">
                            <span
                                class="badge badge-{{ @$order->status_parsed->boot_color }} p-2 status-change text-white">
                                {{ @$order->status_parsed->label }}
                                <i class="fa fa-info-circle" title="Order Status"></i>
                            </span>

                            @if ($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                                <x-button class="dropdown-toggle p-0 btn btn-light" type="button" id="dropdownMenu1"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                        class="ik ik-more-vertical pl-1"></i></x-button>

                                <ul class="dropdown-menu multi-level mr-30" role="menu" aria-labelledby="dropdownMenu">

                                    <a href="{{ route('panel.admin.orders.invoice', secureToken(@$order->id)) }}"
                                        class="dropdown-item bulk-action text-primary fw-700"><i
                                            class="fa fa-print ml-1"></i>
                                        View Invoice
                                    </a>
                                </ul>
                            @endif
                        </div>

                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 border-right">

                                <h6 class="fw-700 mb-2">
                                    <i class="fa fa-user-circle text-muted text-primary mr-1"></i> Customer
                                    ({{ @$order->user->name ?? '--' }})
                                </h6>
                                <div class="">
                                    <i class="fa fa-phone mr-1 text-muted"></i>
                                    <a class="text-muted" href="tel:{{ @$order->user->phone }}">
                                        {{ @$order->user->phone ?? '--' }}</a>
                                    <br>
                                    <i class="fa fa-envelope mr-1 text-muted"></i>
                                    <a class="text-muted" href="mailto:{{ @$order->user->email }}">
                                        {{ @$order->user->email ?? '--' }}</a>
                                </div>
                                <span class="text-muted mb-3"title="Individual">
                                    {{ \App\Models\User::ACCOUNT_TYPES[@$order->user->account_type]['label'] ?? 'Not Available' }}
                                </span>
                                <hr class="my-2">
                                <div class="mb-2">
                                    <span class="text-muted"><i class="fa fa-map-marker mr-1"></i> From
                                        (Pickup/Shipping)</span>
                                    <address class="mb-0">{{ $order->from }}</address>
                                </div>
                                <div class="mt-50">
                                    <span class="text-muted"><i class="fa fa-map-marker mr-1"></i> To
                                        (Delivery)</span>
                                    <address class="mb-0">{{ $order->to }}</address>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-700 mb-2">
                                    <i class="fa fa-id-card text-info mr-1"></i> Driver
                                </h6>
                                @if (@$order->assignTo)
                                    <h6 class="fw-700 mb-2">
                                        {{ @$order->assignTo->first_name }} {{ @$order->assignTo->last_name }}
                                    </h6>
                                    <div class="mb-3">
                                        <i class="fa fa-phone mr-1 text-muted"></i>
                                        <a class="text-muted" href="tel:{{ @$order->assignTo->phone }}">
                                            {{ @$order->assignTo->phone ?? '--' }}
                                        </a><br>
                                        <i class="fa fa-envelope mr-1 text-muted"></i>
                                        <a class="text-muted" href="mailto:{{ @$order->assignTo->email }}">
                                            {{ @$order->assignTo->email ?? '--' }}
                                        </a>
                                    </div>
                                    <hr class="my-2">
                                    <span class="text-muted mb-1 d-block">
                                        <i class="fa fa-car text-success mr-1"></i> Vehicle Details
                                    </span>
                                    <address class="mb-0">
                                        <strong class="text-color-white">
                                            {{ @$order->assignTo->vehicle_details['vehicle_number'] ?? '--' }}
                                        </strong><br>
                                        <span>{{ @$order->assignTo->vehicle_details['vehicle_name'] ?? '--' }}</span><br>
                                        <span>{{ @$order->assignTo->vehicle_details['vehicle_type'] ?? '--' }}</span><br>
                                    </address>
                                    @if ($order->status == App\Models\Order::STATUS_DELIVERED)
                                        <div>
                                            <span class="text-muted mb-1 d-block">
                                                Delivery Challan (DC)
                                            </span>


                                            @php
                                                $dcUrl = $order->getFirstMediaUrl('delivery_challan');
                                            @endphp
                                            @if ($dcUrl)
                                                <a href="{{ $dcUrl }}" target="_blank">
                                                    <i class="fas fa-eye"></i> Preview
                                                </a>
                                            @else
                                                <p class="text-muted mt-2">No delivery challan uploaded.</p>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <p class="text-muted mb-0">
                                        <i class="fa fa-user-tie mr-1"></i> No driver assigned!
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right pb-3">
                        @if (@$order->status != App\Models\Order::STATUS_CANCELLED && @$order->status != App\Models\Order::STATUS_DELIVERED)
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#assignDriverModal">
                                <i class="fa fa-user-tie mr-1"></i> Assign/Re-assign Driver
                            </button>
                        @endif
                    </div>
                </div>

                <div class="card mb-3 pb-2">
                    <div class="card-header mb-0">
                        <h6 class="mb-0">Order Actions</h6>
                    </div>
                    <div class="card-body">
                        @if (in_array($order->status, [\App\Models\Order::STATUS_CANCELLED_BY_ADMIN]))
                            <div class="">
                                <p class="font-semibold text-danger mb-0"><i class="fas fa-ban mt-0.5"></i> Cancellation Reason
                                </p>
                                <p class="">{{ $order->rejection_reason }}</p>
                            </div>
                        @endif
                        {{-- Order Status Update Logic --}}
                        <hr class="my-2">
                        @php
                            $statusOptions = [];
                            switch ($order->status) {
                                case \App\Models\Order::STATUS_PENDING:
                                    $statusOptions = [
                                        \App\Models\Order::STATUS_CANCELLED_BY_ADMIN => __('ui.cancelled'),
                                    ];
                                    break;
                                case \App\Models\Order::STATUS_ASSIGNED:
                                    $statusOptions = [
                                        \App\Models\Order::STATUS_INROUTE => __('ui.in_route'),
                                        \App\Models\Order::STATUS_CANCELLED_BY_ADMIN => __('ui.cancelled'),
                                    ];
                                    break;
                                case \App\Models\Order::STATUS_INROUTE:
                                    $statusOptions = [
                                        \App\Models\Order::STATUS_DELIVERED => __('ui.delivered'),
                                        \App\Models\Order::STATUS_CANCELLED_BY_ADMIN => __('ui.cancelled'),
                                    ];
                                    break;
                                case \App\Models\Order::STATUS_DELIVERED:
                                    $statusOptions = [
                                        \App\Models\Order::STATUS_CANCELLED_BY_ADMIN => __('ui.cancelled'),
                                    ];
                                    break;
                                default:
                                    $statusOptions = [];
                            }
                        @endphp

                        @if (@$order->status != App\Models\Order::STATUS_CANCELLED && @$order->status != App\Models\Order::STATUS_DELIVERED && @$order->status != App\Models\Order::STATUS_CANCELLED_BY_ADMIN)
                            <div style="min-width: 180px;">
                                <form action="{{ route('panel.admin.orders.status-update', secureToken(@$order->id)) }}"
                                    method="POST" id="updateStatusForm">
                                    @csrf
                                    {{-- Status Select --}}
                                    <div class="form-group">
                                        <label for="status_dropdown">@lang('ui.update_status')</label>
                                        <select name="status" id="status_dropdown" class="form-control select2 w-100"
                                            required>
                                            <option value=""> @lang('ui.select_order_status')</option>
                                            @foreach ($statusOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Rejection Reason - Hidden by default --}}
                                    <div id="rejection_reason_container" class="form-group mt-3" style="display: none;">
                                        <label for="rejection_reason" class="text-danger font-weight-bold">
                                            @lang('ui.rejection_reason') / @lang('ui.cancel_reason') <span class="text-danger">*</span>
                                        </label>
                                        <textarea name="rejection_reason" id="rejection_reason" class="form-control"
                                            placeholder="Please enter the reason for cancellation..." rows="3"></textarea>
                                        <div id="rejection_reason_error" class="text-danger mt-1" style="display:none;">
                                            Rejection / cancellation reason is required.
                                        </div>
                                    </div>

                                    {{-- Submit Button --}}
                                    <div class="mt-3 text-right">
                                        <button type="submit" class="btn btn-primary confirm-form-btn">
                                            @lang('ui.update_order_status')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0">
                                <i class="fa fa-info-circle"></i> @lang('ui.no_actions_available')
                            </div>
                        @endif
                    </div>
                </div>



            </div>
        </div>
    </div>

    @include('panel.admin.orders.includes.assign-driver')

@endsection
@push('script')
    {{-- START SELECT 2 INIT --}}

    <script>
        $('select.select2').select2();
    </script>
    {{-- END SELECT 2 INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        $(document).ready(function() {
            // Handle status change and submit the respective form
            $('#status').on('change', function() {
                $('#updateStatus').submit();
            });

            $('#payment_status').on('change', function() {
                $('#updatePaymentStatus').submit();
            });
        });
    </script>
    {{-- END JS HELPERS INIT --}}

    {{-- START GETUSERS INIT --}}
    <script>
        $(document).ready(function() {
            getUsers();

            // Preselect user if editing existing record
            let userId = "{{ $order->assign_to ?? '' }}";
            let userName = "{{ $order->assignTo->name ?? '' }}";
            let userEmail = "{{ $order->assignTo->email ?? '' }}";

            if (userId && userName) {
                // Add an option for the preselected user
                let option = new Option(`${userName} | #UID${userId} | ${userEmail}`, userId, true, true);
                $('.getUsersList').append(option).trigger('change');
            }
        });
    </script>

    {{-- Script to handle Rejection Reason visibility --}}
    <script>
        $(document).ready(function() {

            const CANCELLED_STATUS = "{{ \App\Models\Order::STATUS_CANCELLED_BY_ADMIN }}";

            // Status change handler
            $('#status_dropdown').on('change', function() {
                const selectedValue = $(this).val();

                if (selectedValue == CANCELLED_STATUS) {
                    $('#rejection_reason_container').slideDown();
                    $('#rejection_reason').prop('required', true);
                } else {
                    $('#rejection_reason_container').slideUp();
                    $('#rejection_reason')
                        .prop('required', false)
                        .val('');

                    // Hide error when not cancelled
                    $('#rejection_reason_error').hide();
                }
            });

            // Submit handler
            $('#updateStatusForm').on('submit', function(e) {
                const selectedValue = $('#status_dropdown').val();
                const reason = $('#rejection_reason').val().trim();

                if (selectedValue == CANCELLED_STATUS && reason === '') {
                    e.preventDefault();
                    $('#rejection_reason_error').show();
                    $('#rejection_reason').focus();
                    return false;
                }

                // Hide error if valid
                $('#rejection_reason_error').hide();
            });

            // Hide error while typing
            $('#rejection_reason').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('#rejection_reason_error').hide();
                }
            });

        });
    </script>



    {{-- END GETUSERS INIT --}}
@endpush
