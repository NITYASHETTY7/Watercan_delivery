<table id="table" class="table p-0">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th class="col_1" style="min-width: 100px;">
                    @lang('ui.sno')
                </th>
                <th class="col_1 no-export" width="8%">
                    @lang('ui.actions')
                </th>
                <th class="col_2 no-export" width="8%"> @lang('ui.#')
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i
                            class="ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_3" style="min-width: 100px;">
                @lang('ui.date')s
            </th>
            <th class="col_3" style="min-width: 100px;">
                Schedule
            </th>
            {{-- <th class="col_3" style="min-width: 100px;">
                Schedule Days
            </th> --}}
            <th class="col_3" style="min-width: 150px;">
                @lang('ui.customer')
            </th>
            <th class="col_3" style="min-width: 100px;">
                @lang('ui.branch')
            </th>
            {{-- <th class="col_3" style="min-width: 100px;">
                @lang('ui.zone')
            </th>
            <th class="col_3" style="min-width: 100px;">
                @lang('ui.pincode')
            </th> --}}
            {{-- <th class="col_3" style="min-width: 100px;">
                @lang('ui.qty')
            </th> --}}
            <th class="col_3" style="min-width: 100px;">
                @lang('ui.total')
            </th>
            <th class="col_3" style="min-width: 150px;">
                @lang('ui.driver')
            </th>
            <th class="col_3" style="min-width: 100px;">
                @lang('ui.payment_status')
            </th>
            <th class="col_7" style="min-width: 100px;"> @lang('ui.updated_at') </th>
            <th class="col_8" style="min-width: 100px;"><i class="icon-head" data-title="Join At" title="Created At"><i
                        class="fa-regular fa-clock"></i></i>
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                        class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$orders->count() > 0)
            @foreach (@$orders as $order)
                <tr id="{{ @$order->id }}">
                    @if (!isset($print_mode))
                        <td class="col_1">
                            {{ @$loop->iteration }}
                        </td>
                        <td class="col_1 no-export">
                            <div class="d-flex mb-1">
                                <div class="dropdown">
                                    <x-button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('ui.actions')
                                    </x-button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <li class="p-0">
                                            <a href="{{ route('panel.admin.orders.show', secureToken($order->id)) }}"
                                                title="Show" class="dropdown-item"><i class="ik ik-eye mr-2">
                                                </i>@lang('ui.show')</a>
                                        </li>
                                        <hr class="m-1 b-0">
                                        @if (env('DEV_MODE') == 1)
                                            <li class="p-0">
                                                <a href="{{ route('panel.admin.orders.destroy', secureToken($order->id)) }}"
                                                    title="Delete" class="dropdown-item delete-item text-danger fw-700">
                                                    <i class="ik ik-trash mr-2"> </i> @lang('ui.delete')
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="col_2 no-export">
                            <a class="fw-700" href="{{ route('panel.admin.orders.show', secureToken($order->id)) }}">
                                {{ $order->getPrefix() }}
                            </a>
                        </td>
                    @endif
                    <td class="col_8">{{ @\Carbon\Carbon::parse($order->start_date)->format('d/m/Y') ?? '--' }} -
                        {{ @\Carbon\Carbon::parse($order->end_date)->format('d/m/Y') ?? '--' }}</td>
                    <td class="col_8">{{ @\App\Models\Order::SCHEDULE_TYPES[@$order->schedule_type]['label'] ?? '--' }}
                        <br>
                        @php
                            $schedule = $order->schedule_value;

                            if (is_array($schedule)) {
                                $filtered_schedule = array_filter($schedule);
                            } else {
                                $filtered_schedule = $schedule;
                            }
                            $is_empty_schedule = empty($filtered_schedule);

                        @endphp

                        @if (!$is_empty_schedule)
                            @if (is_array($filtered_schedule))
                                {{ implode(', ', $filtered_schedule) }}
                            @else
                                {{ $filtered_schedule }}
                            @endif
                        @else
                            Every Day
                        @endif

                    </td>
                    {{-- <td class="col_8">
                        
                    </td> --}}

                    <td class="col_3 max-w-150">
                        {{ $order->user ? Str::limit(@$order->user->full_name, 15) : 'N/A' }}
                        ({{\App\Models\User::ACCOUNT_TYPES[@$order->user->account_type]['label'] ?? "N/A"}})
                        
                    </td>

                    <td class="col_3" style="min-width:150px;">
                        <span title="Branch">{{ $order->branch ? Str::limit(@$order->branch->name, 15) : 'N/A' }}</span>
                        <br>
                        <span title="Zone">
                            {{ $order->zone ? Str::limit(@$order->zone->name, 15) : 'N/A' }}
                        </span>-
                        <span title="Pincode">
                            {{ @$order->zonePincode->pincode ?? 'N/A' }}
                        </span>
                    </td>

                    {{-- <td class="col_3 max-w-150">
                        {{ $order->branch ? Str::limit(@$order->branch->name, 15) : 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ $order->zone ? Str::limit(@$order->zone->name, 15) : 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ @$order->zonePincode->pincode ?? 'N/A' }}
                    </td> --}}
                    {{-- <td class="col_3 max-w-150">
                        {{ $order->qty ?? 0 }}
                    </td> --}}
                    <td class="col_3 max-w-150">
                        {{ @format_price($order->total) ?? 0 }}
                    </td>
                    <td class="col_3 max-w-150">
                        @if ($order->assignTo)
                            {{ $order->assignTo ? Str::limit(@$order->assignTo->full_name, 15) : 'Not Assigned' }}
                        @else
                            <div class="text-danger fw-700">
                                Not Assigned
                            </div>
                        @endif
                    </td>
                    <td class="col_3 max-w-150">
                        @php
                            $paymentStatus = \App\Models\Order::PAYMENT_STATUSES[$order->payment_status] ?? [
                                'label' => 'Unknown',
                                'color' => 'secondary',
                            ];
                        @endphp
                        <span class="badge badge-{{ $paymentStatus['color'] }}">
                            {{ $paymentStatus['label'] }}
                        </span>
                    </td>
                    <td class="col_8">{{ @$order->formatted_updated_at ?? '--' }}</td>
                    <td class="col_8">{{ @$order->formatted_created_at ?? '--' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
