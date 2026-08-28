@extends('layouts.main')
@section('title', __('Support Tickets'))
@section('content')

    <div class="max-w-5xl mx-auto">
        <header class="pt-4 px-5">
            <h1 class="text-xl font-semibold text-gray-900">My Tickets</h1>
            <p class="text-sm text-gray-500">
                Review your support tickets across Under Working and Resolved statuses.
            </p>
        </header>

        <div class="px-3 mt-4">
            <div class="flex justify-between bg-gray-100 rounded-xl p-1 text-sm font-medium" id="statusTabs">
                @foreach ($statuses as $statusId => $status)
                    <button id="tab-{{ $statusId }}"
                        class="tab-btn flex-1 py-2 text-center rounded-lg 
                        {{ isset($activeStatus) && $activeStatus == $statusId ? 'active bg-white shadow text-gray-900' : 'text-gray-500' }}"
                        data-status-id="{{ $statusId }}">
                        {{ $status['label'] }}
                    </button>
                @endforeach
            </div>
        </div>

        <div class="px-4 pt-0 space-y-3 mt-5" id="ticketListContainer">
            {{-- Initial tickets loaded via Blade --}}
            @include('panel.user.support-tickets.partials.ticket-list')
        </div>

        <div class="text-center mt-5 mb-24" id="loadMoreWrapper"
            @if (!$tickets->hasMorePages()) style="display:none" @endif>
            <button id="loadMoreBtn"
                class="px-6 py-2.5 bg-gray-200 text-gray-800 text-sm rounded-lg font-semibold shadow-sm hover:bg-blue-700 hover:text-white active:scale-[0.98] transition">
                Load More
            </button>
        </div>
    </div>

    <div class="fixed bottom-5 right-3">
        <a href="{{ route('panel.user.support-tickets.create', ['ticket_type_id' => 'new']) }}"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-lg shadow-sm transition-colors">

            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 1a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2H9v5a1 1 0 1 1-2 0V9H2a1 1 0 1 1 0-2h5V2a1 1 0 0 1 1-1z" />
            </svg>

            Raise Ticket
        </a>
    </div>


@endsection

@push('script')
    <script>
        let currentPage = 1;
        let currentStatus = '{{ $activeStatus ?? array_key_first($statuses) }}';
        const loadMoreBtn = document.getElementById('loadMoreBtn');
        const loadMoreWrapper = document.getElementById('loadMoreWrapper');
        const ticketListContainer = document.getElementById('ticketListContainer');
        const statusTabs = document.getElementById('statusTabs');

        function toggleAccordion(btn) {
            const content = btn.nextElementSibling;
            const icon = btn.querySelector('svg');
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }

        document.querySelectorAll('.accordion-btn').forEach(btn => {
            btn.addEventListener('click', () => toggleAccordion(btn));
        });


        // Use event delegation for accordion handling
        ticketListContainer.addEventListener('click', function(e) {
            const btn = e.target.closest('.accordion-btn');
            if (!btn) return;

            const content = btn.nextElementSibling;
            const icon = btn.querySelector('svg');

            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });


        function fetchTickets(page, status, append = true) {
            // Build the URL with both page and status parameters
            var sso_token = "{{ request()->get('sso_token') }}";

            const url =
                `{{ route('panel.user.support-tickets.index') }}?page=${page}&status=${status}&sso_token=${sso_token}`;

            // Set loading state
            loadMoreBtn.disabled = true;
            loadMoreBtn.textContent = 'Loading...';

            fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    // If not appending (i.e., new tab click), replace content
                    if (!append) {
                        ticketListContainer.innerHTML = data.html;
                    } else {
                        // Append new HTML for Load More
                        ticketListContainer.insertAdjacentHTML("beforeend", data.html);
                    }

                    // Update button visibility
                    if (data.hasMore) {
                        loadMoreWrapper.style.display = "block";
                    } else {
                        loadMoreWrapper.style.display = "none";
                    }

                    // Re-attach accordion listeners to the newly loaded content
                    document.querySelectorAll('#ticketListContainer .accordion-btn:not([data-listener-attached])')
                        .forEach(btn => {
                            btn.addEventListener('click', () => toggleAccordion(btn));
                            btn.setAttribute('data-listener-attached', 'true'); // Prevent attaching multiple times
                        });
                        

                })
                .catch(err => console.error("Error loading tickets:", err))
                .finally(() => {
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.textContent = 'Load More';
                });
        }

        // Attach event listener for Load More button
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', function() {
                currentPage++;
                // Pass the current status when loading more
                fetchTickets(currentPage, currentStatus, true);
            });
        }

        // --- Status Tab Click Handler ---
        if (statusTabs) {
            statusTabs.addEventListener('click', function(event) {
                const btn = event.target.closest('.tab-btn');
                if (!btn) return;

                const newStatus = btn.dataset.statusId;

                // 1. Update active classes visually
                statusTabs.querySelectorAll('.tab-btn').forEach(tab => {
                    tab.classList.remove('active', 'bg-white', 'shadow', 'text-gray-900');
                    tab.classList.add('text-gray-500');
                });
                btn.classList.add('active', 'bg-white', 'shadow', 'text-gray-900');
                btn.classList.remove('text-gray-500');

                // 2. Reset state variables
                currentPage = 1;
                currentStatus = newStatus;

                // 3. Fetch new data (resetting the list, not appending)
                fetchTickets(currentPage, currentStatus, false);
            });
        }
    </script>
@endpush
