<table id="table" class="table">
    <thead>
        <tr>

            @if (!isset($print_mode))
                <th class="col_1 no-export">
                    <x-checkbox type="checkbox" :arr="[]" validation="empty" class="mr-2 allChecked" name="id" value="" />
                </th>
            @endif
            <th class="col_2 no-export" width="8%"> @lang('ui.#')
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik-arrow-down desc"
                        data-val="id"></i></div>
            </th>
            <th class="col_2">@lang('ui.ip_address') </th>
            <th class="col_4">@lang('ui.last_activity')<div class="table-div"><i class="ik ik-arrow-up asc"
                        data-val="created_at"></i><i class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
            <th class="col_4">@lang('ui.logout')</th>
        </tr>
    </thead>
    <tbody>
        @if (@$sessions->count() > 0)
            @foreach (@$sessions as $session)
                <tr id="{{ @$session->id }}">
                    <td class="no-export">
                        <x-checkbox type="checkbox" :arr="[]" validation="empty"
                            class="mr-2 delete_Checkbox text-center" name="id" value="{{ @$session->id }}" />
                    </td>
                    <td class="no-export"><a>{{ @$loop->iteration }} </a></td>

                    <td class="col_3">{{ @$session->ip_address ?? 'N/A' }}</td>
                    <td class="col_4 ml-2">
                        {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->toDateTimeString() }}
                    </td>

                    <td class="col_3">
                        <div class="d-flex justify-content-right">
                            <a href="{{ route('panel.admin.users.sessionDelete', $session->id) }}"
                                class="btn btn-outline-danger mr-2 confirm"
                                title="@lang('ui.logout')">@lang('ui.logout')</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center no-export" colspan="8">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
