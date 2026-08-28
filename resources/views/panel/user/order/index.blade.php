@extends('layouts.main')
@section('title', __('My Orders'))
@section('content')

<style>
    body{
        padding-bottom: 0px !important; 
    }
</style>

    <header class="pt-4 px-5">
        <h1 class="text-xl font-semibold text-gray-900">My Orders</h1>
        <p class="text-sm text-gray-500">
            View the status of your placed, delivered, and cancelled orders easily.
        </p>
    </header>

    <!-- Header Tabs -->
    <div class="px-3 mt-4">
        <div class="flex justify-between bg-gray-100 rounded-xl p-1 text-sm font-medium">
            <button id="tab-upcoming" class="tab-btn flex-1 py-2 text-center rounded-lg active">Placed</button>
            <button id="tab-completed" class="tab-btn flex-1 py-2 text-center rounded-lg">Delivered</button>
            <button id="tab-cancelled" class="tab-btn flex-1 py-2 text-center rounded-lg">Cancelled</button>
        </div>
    </div>

    <!-- Orders List -->
    <div class="px-4 pt-4 space-y-4 pb-5" id="orderList">
        @include('panel.user.order.include.details', ['orders' => $orders])
    </div>

    <!-- Load More -->
    <div class="flex justify-center mt-6 mb-8" id="loadMoreContainer" @if($placedCount <= $perPage) style="display:none" @endif>
        <button id="loadMoreBtn"
            class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm rounded-lg font-semibold shadow-sm hover:bg-blue-700 hover:text-white active:scale-[0.98] transition">
            Load More
        </button>
    </div>

@endsection

@push('script')
    <script>
        let currentPage = 1;
        let activeStatuses = [1, 2, 3]; // Default tab

        document.querySelectorAll(".tab-btn").forEach((tab) => {
            tab.addEventListener("click", function() {
                document.querySelectorAll(".tab-btn").forEach(t => t.classList.remove("active"));
                this.classList.add("active");

                if (this.id === "tab-upcoming") activeStatuses = [1, 2, 3];
                else if (this.id === "tab-completed") activeStatuses = [4];
                else if (this.id === "tab-cancelled") activeStatuses = [5];
                currentPage = 1;
                document.getElementById("loadMoreContainer").style.display = "flex"; 

                fetchOrders(activeStatuses, currentPage, true);
            });
        });

        document.getElementById("loadMoreBtn").addEventListener("click", function() {
            currentPage++;
            fetchOrders(activeStatuses, currentPage, false);
        });

        function fetchOrders(statusSet, page = 1, replace = false) {
            var sso_token = "{{ request()->get('sso_token') }}";
            fetch(`{{ route('panel.user.order.index') }}?status[]=${statusSet.join('&status[]=')}&page=${page}&sso_token=${sso_token}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const list = document.getElementById("orderList");

                    if (replace) list.innerHTML = data.html;
                    else list.insertAdjacentHTML("beforeend", data.html);

                    const loadMore = document.getElementById("loadMoreContainer");
                    
                    if (data.hasMore) {
                        loadMore.style.display = "flex"; // Show button if there is a next page
                    } else {
                        loadMore.style.display = "none"; // Hide button if this is the last page
                    }
                    
                    // OPTIONAL: Also hide the No Orders message if new content was added
                    if (replace && data.total > 0) {
                        // Remove the "No orders found" message if it exists
                        const noMoreOrders = document.getElementById("noMoreOrders");
                        if (noMoreOrders) {
                            noMoreOrders.remove();
                        }
                    }
                })
                .catch(err => console.error(err));
        }

        // Dropdown Menu Toggle
        function toggleMenu(icon) {
            const menu = icon.nextElementSibling;
            document.querySelectorAll(".dropdown-menu").forEach(m => {
                if (m !== menu) m.classList.add("hidden");
            });
            menu.classList.toggle("hidden");
        }

        document.addEventListener("click", (e) => {
            const isMenu = e.target.closest(".dropdown-menu");
            const isIcon = e.target.closest(".fi-bs-menu-dots-vertical");
            if (!isMenu && !isIcon) {
                document.querySelectorAll(".dropdown-menu").forEach(m => m.classList.add("hidden"));
            }
        });
    </script>
@endpush
