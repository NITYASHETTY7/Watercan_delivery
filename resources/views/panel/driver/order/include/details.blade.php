<div class="order-card bg-white rounded-xl border border-[#dbdbdb] shadow-sm relative overflow-hidden pb-2">
    <div class="p-4">
        <div class="flex justify-between items-start rounded-xl">
            <div class="flex gap-4">
                <div class="rounded-lg bg-gray-100 overflow-hidden flex items-center justify-center">
                    <img src="{{ asset('user/assets/images/product-img.jpg') }}" alt="Product"
                        class="w-16 h-16 object-contain"style="mix-blend-mode: multiply;" />
                </div>

                <div>
                    <h6 class="font-normal text-gray-600 leading-tight text-[13px]">
                        #ORD15275
                        <span
                            class="mt-2 inline-block text-xs font-medium px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Pending</span>
                    </h6>

                    <a
                        href="{{ route('panel.user.order.show') }}"class="font-semibold text-gray-900 leading-tight text-base mt-1">
                        Mineral Water (20L)
                    </a>
                    <p class="text-sm text-gray-500">Qty: 4 Cans</p>
                </div>
            </div>

            <div class="relative">
                <i class="fi fi-bs-menu-dots-vertical text-gray-600 cursor-pointer text-[13px]"
                    onclick="toggleMenu(this)"></i>

                <div
                    class="dropdown-menu absolute right-0 top-6 w-32 bg-white border border-gray-100 rounded-md shadow-lg hidden z-10">
                    <a
                        href="{{ route('panel.user.order.show') }}"class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        View Details
                    </a>
                </div>
            </div>
        </div>


        <div class="flex justify-between items-end text-sm mt-4">
            <div>
                <i class="fi fi-rr-steering-wheel text-gray-600 text-sm relative top-[0.1rem]"></i>
                <span>Ramesh Kumar</span>
                <p class="text-gray-500">
                    Date:
                    <span class="font-medium text-gray-800">04 Nov 2025</span>
                </p>

            </div>
            <div class="text-right">
                <p class="text-gray-500 text-sm">Payable</p>
                <p class="text-lg font-semibold text-gray-900 flex items-center justify-end">
                    <i class="fi fi-sc-indian-rupee-sign text-sm mr-1"></i>₹2,500
                </p>
            </div>
        </div>
    </div>
</div>
