@extends('layouts.main')
@section('title', __('My Orders'))
@section('content')
    <header class="pt-4 px-5">
        <h1 class="text-xl font-semibold text-gray-900">Delivery Schedule</h1>
        <p class="text-sm text-gray-500">
            Manage and view the status of orders assigned for delivery.
        </p>
    </header>
    <div class="px-4 mt-4">
        <div class="flex justify-between bg-gray-100 rounded-xl p-1 text-sm font-medium">
            <button id="tab-pending" data-status="pending" class="tab-btn flex-1 py-2 text-center rounded-lg active">
                Pending
            </button>
            <button id="tab-completed" data-status="completed" class="tab-btn flex-1 py-2 text-center rounded-lg">
                Completed
            </button>
        </div>
    </div>

    <div class="px-4 pt-4 space-y-4" id="orderList">
        @include('panel.driver.order.partials.order-list')
    </div>

    <div class="flex justify-center mt-6 mb-10" id="loadMoreWrapper"
        @if (!$orders->hasMorePages()) style="display:none" @endif>
        <button id="loadMoreBtn"
            class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm rounded-lg font-semibold shadow-sm hover:bg-blue-700 hover:text-white active:scale-[0.98] transition">
            Load More
        </button>
    </div>
@endsection

@push('script')
    <script>
        let currentPage = 1;
        let activeStatus = 'pending';
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadMoreWrapper = document.getElementById('loadMoreWrapper');
        const orderList = document.getElementById('orderList');
        const tabs = document.querySelectorAll(".tab-btn");

        // --- FETCH ORDERS FUNCTION ---
        function fetchOrders(status, page = 1, replace = false) {
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            var sso_token = "{{ request()->get('sso_token') }}";

            fetch(`{{ route('panel.driver.order.index') }}?status=${status}&page=${page}&sso_token=${sso_token}`, {
                
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (replace) {
                        orderList.innerHTML = data.html;
                    } else {
                        orderList.insertAdjacentHTML("beforeend", data.html);
                    }

                    // Update button visibility
                    if (data.hasMore) {
                        loadMoreWrapper.style.display = "flex";
                    } else {
                        loadMoreWrapper.style.display = "none";
                    }
                })
                .catch(err => console.error("Error loading orders:", err))
                .finally(() => {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Load More';
                });
        }

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                tabs.forEach((t) => t.classList.remove("active"));
                tab.classList.add("active");

                // State update
                activeStatus = tab.getAttribute('data-status');
                currentPage = 1;
                loadMoreWrapper.style.display = "flex";
                fetchOrders(activeStatus, currentPage, true);
            });
        });

        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", function() {
                currentPage++;
                fetchOrders(activeStatus, currentPage, false);
            });
        }

        function toggleMenu(icon) {
            const menu = icon.nextElementSibling;
            const allMenus = document.querySelectorAll(".dropdown-menu");
            allMenus.forEach((m) => {
                if (m !== menu) m.classList.add("hidden");
            });
            menu.classList.toggle("hidden");
        }

        document.addEventListener("click", (e) => {
            const isMenu = e.target.closest(".dropdown-menu");
            const isIcon = e.target.closest(".fi-bs-menu-dots-vertical");
            if (!isMenu && !isIcon) {
                document.querySelectorAll(".dropdown-menu").forEach((m) => m.classList.add("hidden"));
            }
        });
    </script>
@endpush
