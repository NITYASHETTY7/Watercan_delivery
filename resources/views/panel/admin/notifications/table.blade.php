<table id="notification_table" class="table">
    <thead>
        <tr>
            <th> @lang('ui.sno')
            </th>
            <th>@lang('ui.notifications')</th>
            <th class="no-export">@lang('ui.actions')</th>
        </tr>
    </thead>
    <tbody class="no-data">
        @foreach ($notifications as $notification)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td>
                    @if ($notification->is_read == 0)
                        <span class="new-update"></span>
                    @endif
                    <p> {{ $notification->title }}</p> {{ $notification->notification }}
                </td>
                <td><a href="{{ route('panel.admin.notifications.update', secureToken($notification->id)) }}"
                        class="btn btn-icon btn-sm btn-outline-info"><i class="ik ik-eye text-color-white"></i></a></td>
            </tr>
        @endforeach
    </tbody>
</table>
