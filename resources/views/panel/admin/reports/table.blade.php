<style>
    .max-w-150 {
        max-width: 150px;
        white-space: normal;         /* allow wrapping */
        word-break: break-word;      /* break long words if needed */
        overflow-wrap: anywhere;     /* modern safe break behavior */
    }

</style>

<table id="order_table" class="table p-0">
    <thead>
        <tr>
            @if (!isset($print_mode))
            <th class="col_1" style="min-width: 50px;">
                @lang('ui.sno')
            </th>
            <th class="col_2 no-export" width="8%"> @lang('ui.#')
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            @endif
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.date') 
            </th>
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.status') 
            </th>
            <th class="col_3" style="min-width: 150px;"> 
                @lang('ui.customer') 
            </th>
            <th class="col_3" style="min-width: 150px;"> 
                @lang('ui.address') 
            </th>
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.branch') 
            </th>
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.zone') 
            </th>
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.pincode') 
            </th>
            <th class="col_3" style="min-width: 150px;"> 
                @lang('ui.driver') 
            </th>
            {{-- <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.price') 
            </th> --}}
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.qty') 
            </th>
            <th class="col_3" style="min-width: 100px;"> 
                @lang('ui.total') 
            </th>
        </tr>
    </thead>
    @php
        $total_amount = 0;
        $total_txn_amount = 0;
    @endphp
    <tbody class="no-data">
        @if (@$orders->count() > 0)
            @foreach (@$orders as $order)
                @php
                    $line_total = ($order->total ?? 0);
                    $total_amount += $line_total;
                    $total_txn_amount += $order->tax_amount ?? 0;
                @endphp
                <tr id="{{ @$order->id }}">
                    @if (!isset($print_mode))
                        <td class="col_1">
                            {{ @$loop->iteration }}
                        </td>
                        <td class="col_2 no-export">
                            <a class="fw-700" href="{{ route('panel.admin.orders.show', secureToken($order->id)) }}">
                                {{ $order->getPrefix() }}
                            </a>
                        </td>
                    @endif
                    <td class="col_3 max-w-150">
                        {{ @\Carbon\Carbon::parse($order->date)->format('d-m-Y') ?? 0 }}
                    </td>
                    <td>
                        <ol class="report-stepper status-stepper"
                            data-status-text="{{ \App\Models\Order::STATUSES[$order->status]['label'] ?? 'N/A' }}">
                            @foreach (\App\Models\Order::STATUSES as $key => $status)
                                <li class="{{ $order->status >= $key ? 'completed' : '' }}">
                                    {{ $status['label'] }}
                                </li>
                            @endforeach
                        </ol>
                    </td>
                    <td class="col_3 max-w-150">
                        {{ $order->user ? Str::limit(@$order->user->full_name, 15) : 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ @$order->to ?? '--' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ $order->branch ? Str::limit(@$order->branch->name, 15) : 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ $order->zone ? Str::limit(@$order->zone->name, 15) : 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ @$order->zonePincode->pincode ?? 'N/A' }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ $order->assignTo ? Str::limit(@$order->assignTo->full_name, 15) : 'N/A' }}
                    </td>
                    {{-- <td class="col_3 max-w-150">
                        {{ @format_price($order->total) ?? 0 }}
                    </td> --}}
                    <td class="col_3 max-w-150">
                        {{ @$order->orderItems->sum('qty') ?? 0 }}
                    </td>
                    <td class="col_3 max-w-150">
                        {{ @format_price($order->total) ?? 0 }}
                    </td>
                </tr>
            @endforeach
            <tr style="background: #6a6a6a26;">
                <td class="p-1" colspan="11"><strong>Total:</strong></td>
                <td><strong>{{ format_price($total_amount) }}</strong></td>
            </tr>
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>

