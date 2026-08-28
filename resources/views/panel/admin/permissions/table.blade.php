<table id="permissions_table" class="table">
    <thead>
        <tr>
            <th class="no-export">{{ __('ui.actions') }} <div class="table-div"><i class="ik ik-arrow-up asc"
                        data-val="id"></i><i class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            <th> @lang('ui.permission') </th>
            <th> @lang('ui.assign_roles') </th>
            <th> @lang('ui.group_name') </th>
        </tr>
    </thead>
    <tbody>

        @if ($allPermissions->count() > 0)
            @foreach ($allPermissions as $item)
                <tr>
                    <td class="no-export">
                        <x-button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            @lang('ui.actions')
                        </x-button>
                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                            @if (env('DEV_MODE') == 1)
                                <li class="p-0">
                                    <a href="{{ route('panel.admin.permissions.destroy', secureToken($item->id)) }}"
                                        class="btn btn-sm delete-item text-danger fw-700 dropdown-item">
                                        <i class="ik ik-trash f-16 text-red"></i> @lang('ui.delete')
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </td>
                    <td title="{{ $item->name }}">{{ formatDisplayName($item->name) }}</td>
                    <td>
                        @foreach ($item->roles()->get() as $role)
                            <span class="badge badge-dark mr-1 mt-1">{{ $role->display_name }}</span>
                        @endforeach
                    </td>
                    <td>{{ $item->group ?? '--' }}</td>

                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
