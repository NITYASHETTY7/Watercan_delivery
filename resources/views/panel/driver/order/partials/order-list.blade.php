@forelse ($orders as $order)
    @php
        $statusClasses = [
            \App\Models\Order::STATUS_PENDING => 'bg-yellow-100 text-yellow-700',
            \App\Models\Order::STATUS_ASSIGNED => 'bg-blue-100 text-blue-700',
            \App\Models\Order::STATUS_INROUTE => 'bg-purple-100 text-purple-700',
            \App\Models\Order::STATUS_DELIVERED => 'bg-green-100 text-green-700',
        ];
        $statusLabels = [
            \App\Models\Order::STATUS_PENDING => 'Pending',
            \App\Models\Order::STATUS_ASSIGNED => 'Assigned',
            \App\Models\Order::STATUS_INROUTE => 'In Route',
            \App\Models\Order::STATUS_DELIVERED => 'Delivered',
        ];
        
        $currentStatusClass = $statusClasses[$order->status] ?? 'bg-gray-100 text-gray-700';
        $currentStatusLabel = $statusLabels[$order->status] ?? 'Unknown';
    @endphp

    <div class="order-card bg-white rounded-xl border border-[#dbdbdb] shadow-sm relative overflow-hidden pb-2 mb-2">
        <div class="p-4">
            <div class="flex justify-between items-start rounded-xl">
                <a href="{{ route('panel.driver.order.show', secureToken($order->id)) }}"class="flex gap-4 items-start">
                    <div class="rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center min-w-16">
                        <img src="{{ asset('user/assets/images/product-img.jpg') }}" alt="Product"
                            class="w-16 h-16 min-w-16 object-contain" style="mix-blend-mode: multiply;" />
                    </div>

                    <div>
                        <h6 class="font-normal text-gray-600 leading-[1px] text-[13px]">
                            {{ $order->getPrefix() }}
                            <span
                                class="inline-block text-xs font-medium px-2 py-0.5 rounded-full {{ $currentStatusClass }}">
                                {{ $currentStatusLabel }}
                            </span>
                        </h6>

                        <p
                            class="font-normal text-gray-900 leading-normal text-[15px] mt-1 break-all">
                            {{ $order->to ?? 'Delivery Address' }}
                        </p>
                        <p class="text-sm text-gray-500">Qty: {{ $order->orderItems->sum('qty') ?? 1 }}</p>
                    </div>
                </a>

                <div class="relative">
                    <i class="fi fi-bs-menu-dots-vertical text-gray-600 cursor-pointer text-[13px]"
                        onclick="toggleMenu(this)"></i>

                    <div
                        class="dropdown-menu absolute right-0 top-6 w-32 bg-white border border-gray-100 rounded-md shadow-lg hidden z-10">
                        <button
                            onclick="window.location='{{ route('panel.driver.order.show', secureToken($order->id)) }}'"
                            class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            View Details
                        </button>
                    </div>
                </div>
            </div>

            <a  href="{{ route('panel.driver.order.show', secureToken($order->id)) }}" class="flex justify-between items-end text-sm mt-4">
                <div>
                    <p class="text-gray-500">
                        Date:
                        <span class="font-medium text-gray-800">
                            {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}
                        </span>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-gray-500 text-sm">Payable</p>
                    <p class="text-lg font-semibold text-gray-900 flex items-center justify-end">
                        <i class="fi fi-sc-indian-rupee-sign text-sm mr-1"></i>{{ format_price($order->total ?? 0) }}
                    </p>
                </div>
            </a>
        </div>
    </div>

@empty
    @if ($orders->currentPage() === 1)
        <div class="flex flex-col items-center justify-center min-h-[60vh] py-10">
            <img src="{{asset('user/assets/icons/no-order.png')}}" class="w-[50px] h-auto" alt="">
            <p class="text-center text-gray-800 font-normal text-[15px] py-2" >No Delivery Yet!</p>
        </div>
    @endif
@endforelse