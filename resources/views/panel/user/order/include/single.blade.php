@php
    $orderItem = $order->orderItems->first();
@endphp
<div class="order-card bg-white rounded-xl border border-[#dbdbdb] shadow-sm relative overflow-hidden">
    <div class="p-4">
        <div class="flex justify-between items-start rounded-xl">
            <a class="flex gap-4" href="{{ route('panel.user.order.show', secureToken($order->id)) }}">
                <div class="rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('user/assets/images/product-img.jpg') }}" alt="Product"
                        class="w-16 h-16 object-contain" style="mix-blend-mode: multiply;" />
                </div>

                <div>
                    <h6 class="font-normal text-gray-600 leading-tight text-[13px]">
                        {{ $order->getPrefix() }}
                        @php
                            $statusInfo = \App\Models\Order::STATUSES[$order->status] ?? null;
                        @endphp

                        @if ($statusInfo)
                            <span
                                class="mt-2 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-{{ $statusInfo['color'] }}-100 text-{{ $statusInfo['color'] }}-700">
                                {{ $statusInfo['label'] }}
                            </span>
                        @endif
                    </h6>

                    <div class="font-semibold text-gray-900 leading-tight text-base mt-1 block">

                        {{ @$orderItem->product->name ?? "Water Can" }} @if($order->orderItems->count() > 1) +1 @endif

                    </div>
                    <p class="text-sm text-gray-500">Qty: {{ @$orderItem->qty }}</p>
                </div>
            </a>
            @if (!request()->routeIs('panel.user.subscriptions.show'))
                <div class="relative">
                    <i class="fi fi-bs-menu-dots-vertical text-gray-600 cursor-pointer text-[13px]"
                        onclick="toggleMenu(this)"></i>

                    <div
                        class="dropdown-menu absolute right-0 top-6 w-32 bg-white border border-gray-100 rounded-md shadow-lg hidden z-10">
                        <a href="{{ route('panel.user.order.show', secureToken($order->id)) }}"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            View Details
                        </a>
                        @if($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                            <a href="{{ route('panel.user.order.invoice', secureToken($order->id)) }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                View Invoice
                            </a>
                        @endif

                    </div>

                </div>
            @endif
        </div>

        <a class="flex justify-between items-end text-sm mt-4"href="{{ route('panel.user.order.show', secureToken($order->id)) }}">
            <div>
                <i class="fi fi-rr-steering-wheel text-gray-600 text-sm relative top-[0.1rem]"></i>
                <span>{{ $order->assignTo->first_name ?? 'Not Assigned' }}</span>
                <p class="text-gray-500">
                    Date:
                    <span class="font-medium text-gray-800">{{ $order->created_at->format('d M Y') }}</span>
                </p>
            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm">Payable</p>
                <p class="text-lg font-semibold text-gray-900 flex items-center justify-end">
                    {{ format_price($order->total) }}
                </p>
            </div>
        </a>
    </div>
    {{-- @if ($order->payment_status == \App\Models\Order::PAYMENT_STATUS_UNPAID)
        <div class="px-4 py-1 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="font-semibold text-red-700 text-sm">
                    Payment Failed
                </span>
            </div>
        </div>
    @endif --}}

    <a href="{{ route('panel.user.order.show', secureToken($order->id)) }}"
        class="bg-blue-100 text-sm rounded-b-lg px-4 py-2 flex items-center justify-between text-gray-700 rounded-b-xl">
        <p class="text-sm text-gray-500">
            Payment Status:
            @if ($order->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                <span class="font-semibold text-green-600">Paid</span>
            @else
                <span class="font-semibold text-red-600">Unpaid</span>
            @endif
        </p>
        {{-- <span class="font-medium text-gray-600">Sub ID: {{ $order->getPrefix() }}</span> --}}
        {{-- <span class="font-medium flex items-center gap-1">
            <i class="fi fi-rr-steering-wheel text-gray-600 text-sm relative top-[0.1rem]"></i>
            <span>{{ $order->assignTo->full_name ?? 'Not Assigned' }}</span>
        </span> --}}
    </a>


</div>
