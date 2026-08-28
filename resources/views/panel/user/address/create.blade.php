@extends('layouts.main')
@section('title', __('Order Tracking'))
@section('content')

    @push('head')
        <style>
            .priority-option input:checked+span {
                font-weight: 600;
            }

            .bottom-sheet {
                transform: translateY(100%);
                transition: transform 0.35s ease-in-out;
            }

            .bottom-sheet.show {
                transform: translateY(0);
            }
        </style>
    @endpush

    <div class="">

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            <header class="pt-4 px-5">
                <h1 class="text-xl font-semibold text-gray-900">Create Support Ticket</h1>
                <p class="text-sm text-gray-500">
                    Need help with your subscription or delivery? Fill in the details below to raise a support ticket.
                </p>
            </header>

            <!-- Form Section -->
            <div class="p-4 space-y-5">
                <form id="ticketForm" class="space-y-3"action="{{ route('panel.user.support-tickets.store') }}" method="POST">
                    @csrf
                    <!-- Priority -->
                    <div>
                        <input type="hidden" name="ticket_type_id" value="{{ $order->id }}">
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                        <div class="flex gap-6 opacity-70 pointer-events-none select-none">
                            @foreach (\App\Models\SupportTicket::PRIORITIES as $key => $priority)
                                <label class="flex items-center gap-2 cursor-not-allowed">
                                    <input type="radio" name="priority" value="{{$key}}"
                                        class="text-{{ $priority['color'] }}-600 rounded-full"
                                        {{ $key == \App\Models\SupportTicket::PRIORITY_HIGH ? 'checked' : '' }}>
                                    <span class="text-gray-700">{{ $priority['label'] }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>


                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                        <input type="text" id="subject" name="subject"
                            class="w-full h-11 border border-gray-300 rounded-lg px-3 text-gray-900 bg-gray-100 cursor-not-allowed focus:outline-none"
                            value="Facing an issue {{ $order->getPrefix() }}" readonly>
                    </div>
                    <!-- Message -->


                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>

                        <textarea id="message" name="message" rows="5"autofocus
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-900 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"required
                            placeholder="Describe your issue in detail"></textarea>

                    </div>

                    <!-- Action Buttons -->
                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 text-base rounded-lg transition-colors">
                            Create Ticket
                        </button>

                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
@push('script')
@endpush
