@forelse ($tickets as $ticket)
    @php
        $status = \App\Models\SupportTicket::STATUSES[$ticket->status] ?? [
            'label' => 'Unknown',
            'custom_color' => 'gray',
        ];
        $priority = \App\Models\SupportTicket::PRIORITIES[$ticket->priority] ?? [
            'label' => 'N/A',
            'custom_color' => 'gray',
        ];
    @endphp

    <div class="bg-white rounded-xl shadow-sm border  p-4 border-[#dbdbdb] pb-2">
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center text-gray-600 text-sm font-medium mb-1">
                    {{ $ticket->getPrefix() }}
                    <span class="flex items-center ml-1 text-xs font-semibold text-{{ $priority['custom_color'] }}-700">
                        <span class="w-2 h-2 rounded-full bg-{{ $priority['custom_color'] }}-500 mr-1.5"></span>
                        {{-- {{ $priority['label'] }} --}}
                    </span>
                </div>
            </div>

            <span
                class="text-xs font-semibold px-3 py-1 bg-{{ $status['custom_color'] }}-100 text-{{ $status['custom_color'] }}-700 rounded-full self-start">
                {{ $status['label'] }}
            </span>
        </div>
        <div class="pt-2">
            <h3 class="text-base font-semibold leading-tight text-gray-900">
                {{ $ticket->subject ?? 'Untitled Ticket' }}
            </h3>
        </div>

        <div class="border-t border-gray-100 mt-4 py-2">
            <button
                class="w-full text-left flex justify-between items-center text-sm font-medium text-gray-700 hover:bg-gray-50 accordion-btn border-b-xl"
                data-listener-attached="false">
                Message
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div class="accordion-content hidden py-2 pb-3 text-sm text-gray-600">
                {!! nl2br(e($ticket->message)) !!}
                
                @if (!empty($ticket->reply))
                    <div class="mt-3 bg-gray-100 rounded-lg border border-gray-200 p-2 max-h-[190px] overflow-hidden overflow-y-auto ">
                        <strong class="text-gray-900 text-sm">Reply:</strong>
                        <p class="text-gray-700 text-sm mt-2 break-all">
                            {!! nl2br(e($ticket->reply)) !!}
                        </p>
                    </div>
                @endif
            </div>

        </div>
    </div>
@empty
    {{-- This div will only show if no tickets are loaded, either initially or in an AJAX chunk --}}
    @if ($tickets->currentPage() === 1)
        <div class="flex flex-col items-center justify-center text-center py-10 text-gray-500 text-sm min-h-[60vh]">
            <img src="{{ asset('user/assets/icons/suggestion.png') }}"
                style="mix-blend-mode: multiply;"alt="No Data" class="w-[50px] mx-auto">
            <p class="text-center text-gray-800 font-normal text-[15px] py-2">
                No Tickets Found!

            </p>
        </div>
    @endif
@endforelse
