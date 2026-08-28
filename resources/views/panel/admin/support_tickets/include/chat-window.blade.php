<div class="app-message-body chat" id="chat_container_{{ $receiver ? $receiver->id : 0 }}_{{ auth()->id() ?? 0 }}_{{ $supportTicket->id ?? 0 }}">
    <div class="chat-loader-ajax">

    </div>
    <ul class="conversationDiv" style="list-style: none; padding-left: 0; margin-bottom: 0;">
        <!-- Messages get appended here -->
    </ul>

    <!-- Optional: only show this when no conversation -->
    <div class="no-conversation" style="text-align: center;">
        <div class="text-center">
            <img src="{{ asset('site/v1/img/icon/bubble-chat.png') }}" alt="No Conversation" class="no-chat-img">
            <h6 class="text-dark fs-15">Conversation not started yet!</h6>
        </div>
    </div>
    {{--@if ($supportTicket->conversations->count() > 0)
        @foreach ($supportTicket->conversations as $conversation)
            @if ($conversation->user_id == auth()->id())
                @if ($conversation->comment || @$conversation->getFirstMediaUrl('ticket_file') != '')
                    <div class="message-wrapper mb-1">
                        <div class="message chat-right message-curve-right" style=" border-radius: 10px">
                            <div class="d-flex">
                                @if (@$conversation->getFirstMediaUrl('ticket_file') != '')
                                    @if (str_contains(@$conversation->getFirstMedia('ticket_file')->getAttribute('mime_type'), 'image'))
                                        <img src="{{ @$conversation->getFirstMediaUrl('ticket_file') }}" class="img-fluid"
                                            alt="{{ $conversation->comment }}" width="50%">
                                    @else
                                        {{ @$conversation->getFirstMedia('ticket_file')->getAttribute('file_name') }}
                                    @endif
                                @endif
                            </div>

                            <p class="p-0 m-0 text-muted fs-14 fw-800"
                                style="color: #000000  !important; text-align: start;     line-height: 1.3;">
                                {!! insertCustomMarkup(@$conversation->comment ?? '') !!}
                            </p>
                            <small class="mt-1 fs-10" style="color: #1c1b1b;">
                                {{ @$conversation->user->full_name ?? '' }}
                                {{ @$conversation->created_at->diffForHumans() ?? '--' }}</small>
                        </div>
                    </div>
                @endif
            @else
                @if ($conversation->comment || @$conversation->getFirstMediaUrl('ticket_file') != '')
                    <div class="message-wrapper mb-2">
                        <div class="message chat-left">
                            @if (@$conversation->getFirstMediaUrl('ticket_file') != '')
                                @if (str_contains(@$conversation->getFirstMedia('ticket_file')->getAttribute('mime_type'), 'image'))
                                    <img src="{{ @$conversation->getFirstMediaUrl('ticket_file') }}" class="img-fluid"
                                        alt="" width="50%">
                                @else
                                    {{ @$conversation->getFirstMedia('ticket_file')->getAttribute('file_name') ?? '--' }}
                                @endif
                            @endif

                            <p class="p-0 m-0 text-black fw-bold">
                                {!! insertCustomMarkup(@$conversation->comment ?? '') !!}
                            </p>
                            <small class="text-black mt-1">
                                {{ 'At ' . @$conversation->created_at ?? '--' }}
                                By {{ @$conversation->user->full_name ?? '--' }}</small>
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    @else
        <li class="d-flex align-items-center" style="height: 35vh;">
            <div class="text-center mx-auto">
                <p> @lang('ui.no_conversation_yet')</p>
            </div>
        </li>
    @endif --}}
</div>
