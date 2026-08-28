@forelse ($userAddresses as $address)
   @php
        $details = $address->details ?? [];
        $addressType = ($details['type'] ?? '0') == '0' ? 'Home' : 'Office';
        $typeColor   = ($details['type'] ?? '0') == '0' ? 'green' : 'blue';
    @endphp
    <div class="bg-white rounded-xl shadow-sm border p-4 border-[#dbdbdb] pb-3">

        <div class="flex justify-between items-start">
            <div class="flex items-center gap-2">
                <span
                    class="text-xs font-semibold px-3 py-1 rounded-full 
                    bg-{{ $typeColor }}-100 text-{{ $typeColor }}-700">
                    {{ $addressType }}
                </span>

            </div>

            <div class="relative">
                <i class="fi fi-bs-menu-dots-vertical text-gray-600 cursor-pointer text-[13px]"
                    onclick="toggleMenu(this)"></i>

                <div
                    class="dropdown-menu absolute right-0 top-6 w-32 bg-white border border-gray-100 rounded-md shadow-lg hidden z-10">
                    <a href="{{ route('panel.user.address.edit', secureToken($address->id)) }}"
                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-pen me-1"></i> Edit
                    </a>

                    {{-- <a href="{{ route('panel.user.address.destroy', secureToken($address->id)) }}"
                        class="block w-full text-left px-4 py-2 text-sm text-red-600  hover:bg-gray-100">
                        <i class="fa-solid fa-trash me-1"></i> Delete
                    </a> --}}

                </div>
            </div>
        </div>

        <div class="pt-2">
            <p class="text-sm text-gray-800 leading-relaxed break-words">
                {{ $details['address_1'] ?? '' }}
            </p>

            @if (!empty($details['address_2']))
                <p class="text-sm text-gray-800 break-words">
                    {{ $details['address_2'] }}
                </p>
            @endif

            <p class="text-sm text-gray-700 break-all">
                {{ $details['pincode'] ?? '' }}
            </p>
        </div>
    </div>
@empty
    <div class="flex flex-col items-center justify-center text-center py-10 text-gray-500 text-sm min-h-[70vh]">
        <img src="{{ asset('user/assets/icons/map.png') }}" class="w-[50px] mx-auto"
            alt="No Data">
        <p class="text-gray-800 font-normal text-[15px] py-2">
            No Address Found!
        </p>
    </div>
@endforelse

@push('script')
    <script>
        function toggleMenu(icon) {
            const menu = icon.nextElementSibling;
            document.querySelectorAll(".dropdown-menu").forEach(m => {
                if (m !== menu) m.classList.add("hidden");
            });
            menu.classList.toggle("hidden");
        }
    </script>
@endpush
