<table id="table" class="table p-0">
    <thead>
        <tr>
            @if (!isset($print_mode))
            <th class="col_1" width="8%">
                <x-checkbox type="checkbox" :arr="[]" class="mr-2 allChecked" name="id" value="" validation="empty" />
                @lang('ui.sno')
            </th>
            <th class="col_1 no-export" width="8%">
                @lang('ui.actions')
            </th>
            <th class="col_2 no-export" width="8%"> @lang('ui.#')
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik-arrow-down desc"
                        data-val="id"></i></div>
            </th>
            @endif
            <th class="col_3" width="15%"> @lang('ui.user') <div class="table-div"><i class="ik ik-arrow-up asc" data-val="user"></i><i class="ik ik-arrow-down desc" data-val="user"></i></div>
            </th>
            <th class="col_7" width="8%"> @lang('ui.updated_at') </th>
            <th class="col_8" width="10%"><i class="icon-head" data-title="Join At" title="Created At"><i
                        class="fa-regular fa-clock"></i></i>
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                        class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$zonePincodeUsers->count() > 0)
        @foreach (@$zonePincodeUsers as $zonePincodeUser)
        <tr id="{{ @$zonePincodeUser->id }}">
            @if (!isset($print_mode))
            <td class="col_1">
                <x-checkbox type="checkbox" :arr="[]" class="mr-2 delete_Checkbox text-center" name="id" value="{{ @$zonePincodeUser->id }}" validation="empty" />
                {{ @$loop->iteration }}
            </td>
            <td class="col_1 no-export">
                <div class="d-flex mb-1">
                    <div class="dropdown">
                        <x-button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            @lang('ui.actions')
                        </x-button>
                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                            <li class="p-0">
                                <a href="{{ route('panel.admin.zone-pincode-users.edit', secureToken($zonePincodeUser->id)) }}" title="Edit" class="dropdown-item"><i class="ik ik-edit mr-2"> </i>@lang('ui.edit')</a> 
                            <hr class="m-1 b-0">
                            @if (env('DEV_MODE') == 1)
                                <li class="p-0">
                                    <a href="{{ route('panel.admin.zone-pincode-users.destroy', secureToken($zonePincodeUser->id)) }}"
                                        title="Delete" class="dropdown-item delete-item text-danger fw-700">
                                        <i class="ik ik-trash mr-2"> </i> @lang('ui.delete')
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </td>
            <td class="col_2 no-export">
                <div>
                    {{ $zonePincodeUser->getPrefix() }}
                </div>
            </td>
            @endif

            <td class="col_3 max-w-150">{{ @$zonePincodeUser->user->full_name ?? 'N/A' }}
            </td>
            
            <td class="col_8">{{ @$zonePincodeUser->formatted_updated_at ?? '--' }}</td>
            <td class="col_8">{{ @$zonePincodeUser->formatted_created_at ?? '--' }}</td>
        </tr>
        @endforeach
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
