@extends('layouts.main')
@section('title', __('My Subscriptions'))
@section('content')

    <style>
        body{
            padding-bottom: 0px !important;
        }
        .tab-active {
            background-color: white !important;
            color: #111827;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }
    </style>



    <header class="pt-4 px-5">
        <h1 class="text-xl font-semibold text-gray-900">My Subscriptions</h1>
        <p class="text-sm text-gray-500">
            Manage and track your active, completed, and cancelled subscriptions easily.
        </p>
    </header>

    <div class="px-3 mt-4">
        <div class="flex justify-between bg-gray-100 rounded-lg p-1 text-sm font-medium">
            <button data-status="pending"
                class="tab-btn flex-1 py-2 text-center rounded-md 
                @if ($statusFilter == 'pending') tab-active @endif">
                Pending
            </button>
            <button data-status="completed"
                class="tab-btn flex-1 py-2 rounded-md text-center 
                @if ($statusFilter == 'completed') tab-active @endif">
                Completed
            </button>
            <button data-status="cancelled"
                class="tab-btn flex-1 py-2 rounded-md text-center 
                @if ($statusFilter == 'cancelled') tab-active @endif">
                Cancelled
            </button>
        </div>
    </div>

    <section id="subscriptionList" class="px-4 pt-5 space-y-4">
        @include('panel.user.subscriptions.include.partials')
    </section>

    <div class="flex justify-center mt-5 mb-8" id="loadMoreWrapper"
        @if (!$subscriptions->hasMorePages()) style="display:none" @endif>
        <button id="loadMoreBtn"
            class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm rounded-lg font-semibold shadow-sm hover:bg-blue-700 hover:text-white active:scale-[0.98] transition">
            Load More
        </button>
    </div>
@endsection

@push('script')
    <script>
        let currentPage = 1;
        // Get the active status from the initial page load (set by controller)
        let activeStatus = "{{ $statusFilter ?? 'pending' }}";
        const loadMoreBtn = document.getElementById("loadMoreBtn");
        const loadMoreWrapper = document.getElementById("loadMoreWrapper");
        const subscriptionList = document.getElementById("subscriptionList");

        // --- FETCH ORDERS FUNCTION ---
        function fetchSubscriptions(status, page = 1, replace = false) {
            // Disable button and add loading state
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';
            var sso_token = "{{ request()->get('sso_token') }}";
            fetch(`{{ route('panel.user.subscriptions.index') }}?status=${status}&page=${page}&sso_token=${sso_token}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (replace) {
                        subscriptionList.innerHTML = data.html;
                    } else {
                        subscriptionList.insertAdjacentHTML("beforeend", data.html);
                    }

                    // Update hasMore status
                    if (data.hasMore) {
                        loadMoreWrapper.style.display = "flex";
                    } else {
                        loadMoreWrapper.style.display = "none";
                    }
                })
                .catch(err => {
                    console.error("Error fetching subscriptions:", err);
                    // Optionally show an error message
                })
                .finally(() => {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Load More';
                });
        }

        // --- TAB SWITCHING LOGIC ---
        document.querySelectorAll(".tab-btn").forEach((tab) => {
            tab.addEventListener("click", function() {
                // 1. Update CSS
                document.querySelectorAll(".tab-btn").forEach(t => t.classList.remove("tab-active"));
                this.classList.add("tab-active");

                // 2. Update state variables
                activeStatus = this.getAttribute('data-status');
                currentPage = 1; // Reset page to 1 for new tab

                // 3. Reset Load More wrapper visibility to "flex" before fetching
                // (It will be hidden if the AJAX response returns hasMore: false)
                loadMoreWrapper.style.display = "flex";

                // 4. Fetch new data, replacing current list (replace = true)
                fetchSubscriptions(activeStatus, currentPage, true);
            });
        });

        // --- LOAD MORE BUTTON LOGIC ---
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener("click", function() {
                currentPage++; // Increment page number
                // Fetch next page, appending to list (replace = false)
                fetchSubscriptions(activeStatus, currentPage, false);
            });
        }

        // Dropdown Menu Toggle
        function toggleMenu(icon) {
            const menu = icon.nextElementSibling;
            document.querySelectorAll(".dropdown-menu").forEach(m => {
                if (m !== menu) m.classList.add("hidden");
            });
            menu.classList.toggle("hidden");
        }
    </script>
@endpush
