<style>
    .table-div {
        float: none;
    }
</style>

<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th class="no-export" width="8%">
                    @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting) ||
                            getSetting('toggling_support_ticket_bulk_delete', @$master_setting))
                        <x-checkbox type="checkbox" :arr="[]" validation="empty" class="mr-2 allChecked"
                            name="id" value="" />
                    @endif
                    @lang('ui.sno')
                </th>
                <th class="no-export" width="8%">
                    @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting) ||
                            getSetting('toggling_support_ticket_bulk_delete', @$master_setting))
                        @lang('ui.action')
                    @endif
                </th>
            @endif
            <th class="col_2 no-export" width="13%"> @lang('ui.#') <div class="table-div"> <i class="icon-head"
                        title=""></i>
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i
                            class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            <th class="col_2" width="25%"> @lang('ui.name') <div class="table-div"> <i class="icon-head"
                        title=""></i>
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="user_id"></i><i
                            class="ik ik-arrow-down desc" data-val="user_id"></i></div>
            </th>
            {{-- <th class="col_3" width="15%"> @lang('ui.assign_to_admin') </th> --}}

            <th class="col_4" width="25%"> @lang('ui.subject') </th>
            
            <th class="col_3" width="10%"> @lang('ui.status') </th>
            <th class="col_5 no-export" width="10%"> <i class="fa-regular fa-clock" title="Created At"></i>
                <div class="table-div"> <i class="icon-head" data-title="Created At"></i>
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                            class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$supportTickets->count() > 0)
            @foreach (@$supportTickets as $supportTicket)
                <tr id="{{ @$supportTicket->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            @if (getSetting('toggling_support_ticket_bulk_upload', @$master_setting) ||
                                    getSetting('toggling_support_ticket_bulk_delete', @$master_setting))
                                <x-checkbox type="checkbox" :arr="[]" validation="empty" type="checkbox"
                                    class="mr-2 delete_Checkbox text-center" name="id"
                                    :value="@$supportTicket->id" />
                            @endif
                            {{ @$loop->iteration }}
                        </td>
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <x-button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    @lang('ui.actions')
                                </x-button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    @if ($master_permissions->contains('ticket_delete_rp'))
                                        <li class="p-0">
                                            <a href="{{ route('panel.admin.support-tickets.destroy', secureToken($supportTicket->id)) }}"
                                                title="Delete Support Ticket"
                                                class="btn btn-sm delete-item text-danger fw-700 mr-1 dropdown-item"><i
                                                    class="ik ik-trash"></i> @lang('ui.delete')
                                            </a>
                                        <li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td class="no-export">
                            <a href="@if ($master_permissions->contains('ticket_show_rp')) {{ route('panel.admin.support-tickets.show', secureToken($supportTicket->id)) }}?active=details @endif"
                                class="table-link d-flex align-items-center">
                                @if (@$supportTicket->priority_parsed->color == 'yellow')
                                    <i title="Medium"
                                        class="fa fa-circle text-{{ @$supportTicket->priority_parsed->color ?? '--' }} fw-700 mr-1"></i>
                                @elseif(@$supportTicket->priority_parsed->color == 'red')
                                    <i title="High"
                                        class="fa fa-circle text-{{ @$supportTicket->priority_parsed->color ?? '--' }} fw-700 mr-1"></i>
                                @elseif(@$supportTicket->priority_parsed->color == 'green')
                                    <i title="Low"
                                        class="fa fa-circle text-{{ @$supportTicket->priority_parsed->color ?? '--' }} fw-700 mr-1"></i>
                                @else
                                    <i
                                        class="fa fa-circle text-{{ @$supportTicket->priority_parsed->color ?? '--' }} fw-700 mr-1"></i>
                                @endif
                                {{ @$supportTicket->getPrefix() ?? '--' }}
                            </a>
                        </td>
                    @endif

                    <td>{{ @$supportTicket->user->full_name ?? 'Not Available' }}
                        ({{ (UserRole($supportTicket->user->id)->display_name ?? 'User') === 'User' ? 'Customer' : UserRole($supportTicket->user->id)->display_name }})
                    </td>

                    {{-- <td>
                        @if ($supportTicket->assignedAdmin)
                            {{ $supportTicket->assignedAdmin->full_name }}
                        @else
                            @lang('ui.not_assigned')
                        @endif
                    </td> --}}

                    <td>
                        {{ @$supportTicket->subject ?? 'N/A' }}
                    </td>
                    <td>
                        <span
                            class="badge badge-{{ @$supportTicket->status_parsed->color }} m-1">{{ @$supportTicket->status_parsed->label ?? '--' }}
                        </span>
                    </td>
                    <td class="no-export">{{ @$supportTicket->formatted_created_at ?? '--' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
