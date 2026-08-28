@forelse ($subscriptions as $subscription)
    @php
        $status = $subscription->status;
        // Ensure Order::STATUSES is correctly defined with 'label' and 'color' keys
        $statusLabel = \App\Models\Order::STATUSES[$status]['label'];
        $statusColor = \App\Models\Order::STATUSES[$status]['color'];

        $orderItem = $subscription->orderItems->first();

        $branch = $subscription->branch->name ?? '-';
        $zone = $subscription->zone->name ?? '-';
        $pincode = $subscription->zonePincode->pincode ?? '-';
    @endphp

    <div class="bg-white rounded-xl shadow-sm border border-[#dbdbdb]">
        <div class="p-4 pb-2">
            <div class="flex justify-between items-start">
                <a href="{{ route('panel.user.subscriptions.show', secureToken($subscription->id)) }}">
                    <div class="flex gap-3">
                        <div class="w-24 h-20 rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center">
                            <img src="{{ asset('user/assets/images/product-img.jpg') }}"
                                alt="Product"style="mix-blend-mode: multiply;" class="w-full h-full object-contain" />
                        </div>
                        <div>
                            <h6 class="font-normal text-gray-600 leading-tight text-[13px]">
                                {{ $subscription->getPrefix() }}
                                @php
                                    $statusInfo = \App\Models\Order::STATUSES[$subscription->status] ?? null;
                                @endphp

                                @if ($statusInfo)
                                    <span
                                        class="mt-2 inline-block text-xs font-semibold px-2 py-0.5 rounded-full bg-{{ $statusInfo['color'] }}-100 text-{{ $statusInfo['color'] }}-700">
                                        {{ $statusInfo['label'] }}
                                    </span>
                                @endif
                            </h6>

                            <div class="font-semibold text-gray-900 leading-tight text-base mt-1 block">
                                {{ @$orderItem->name ?? 'Water Can' }} @if ($subscription->orderItems->count() > 1)
                                    +1
                                @endif
                            </div>
                            <p class="text-sm text-gray-500">Qty: {{ @$orderItem->qty }}</p>
                        </div>
                    </div>

                </a>
                {{-- ADJUSTED PRICE AND STATUS LAYOUT --}}
                <div class="flex flex-col items-end pt-1">

                    <div class="relative">
                        <i class="fi fi-bs-menu-dots-vertical text-gray-600 cursor-pointer text-[13px]"
                            onclick="toggleMenu(this)"></i>

                        <div
                            class="dropdown-menu absolute right-0 top-6 w-32 bg-white border border-gray-100 rounded-md shadow-lg hidden z-10">
                            <a href="{{ route('panel.user.subscriptions.show', secureToken($subscription->id)) }}"
                                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                View Details
                            </a>
                            @if ($subscription->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                                <a href="{{ route('panel.user.order.invoice', secureToken($subscription->id)) }}"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    View Invoice
                                </a>
                            @endif

                        </div>

                    </div>
                </div>
                {{-- END ADJUSTED LAYOUT --}}

            </div>



            <a href="{{ route('panel.user.subscriptions.show', secureToken($subscription->id)) }}"
                class="flex justify-between items-end text-sm mt-4">
                <div>
                    <div class="">
                        <span class="font-medium">Plan Type:</span>
                        <span class="text-gray-800 font-semibold">
                            {{ App\Models\Order::SCHEDULE_TYPES[$subscription->schedule_type]['label'] }}
                        </span>
                    </div>
                    <div>
                        {{-- <span class="font-medium text-gray-700">Timeline:</span> --}}
                        <span>
                            {{ $subscription->start_date ? \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') : '—' }}
                            -
                            {{ $subscription->end_date ? \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') : '—' }}
                        </span>
                    </div>
                </div>

                <div class="text-right">
                    <p class="text-gray-500 text-sm">Payable</p>
                    <p class="text-lg font-semibold text-gray-900 flex items-center justify-end">
                        {{ format_price($subscription->total) }}
                    </p>
                </div>

            </a>
        </div>

        <a href="{{ route('panel.user.subscriptions.show', secureToken($subscription->id)) }}"
            class="bg-blue-100 text-sm rounded-b-lg px-4 py-2 flex items-center justify-between text-gray-700 rounded-b-xl">

            <p class="text-sm text-gray-500">
                Payment Status:
                @if ($subscription->payment_status == \App\Models\Order::PAYMENT_STATUS_PAID)
                    <span class="font-semibold text-green-600">Paid</span>
                @else
                    <span class="font-semibold text-red-600">Unpaid</span>
                @endif
            </p>
            <span class="font-medium flex items-center gap-1">
                <i class="fi fi-rr-steering-wheel text-gray-600 text-sm relative top-[0.1rem]"></i>
                <span>{{ $subscription->assignTo->first_name ?? 'Not Assigned' }}</span>
            </span>
        </a>
    </div>
@empty
    <div class="flex flex-col items-center justify-center min-h-[58vh] py-10" id="no-subscriptions">
        <img src="{{ asset('user/assets/icons/no-order.png') }}" class="w-[50px] h-auto" alt="">
        <p class="text-center text-gray-800 font-normal text-[15px] py-2">No Subscriptions Found!</p>
    </div>
@endforelse
