<table id="branchTable" class="table p-0">
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
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            @endif
            <th class="col_3" width="15%"> @lang('ui.name') <div class="table-div"><i class="ik ik-arrow-up asc" data-val="first_name"></i><i class="ik ik-arrow-down desc" data-val="first_name"></i></div>
            </th>
            <th class="col_3" width="15%"> @lang('ui.address') </th>
            <th class="col_3" width="15%"> @lang('ui.zones') </th>
            <th class="col_7" width="8%"> @lang('ui.updated_at') </th>
            <th class="col_8" width="10%"><i class="icon-head" data-title="Join At" title="Created At"><i
                        class="fa-regular fa-clock"></i></i>
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                        class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$branches->count() > 0)
        @foreach (@$branches as $branch)
        <tr id="{{ @$branch->id }}">
            @if (!isset($print_mode))
            <td class="col_1">
                <x-checkbox type="checkbox" :arr="[]" class="mr-2 delete_Checkbox text-center" name="id" value="{{ @$branch->id }}" validation="empty" />
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
                                <a href="{{ route('panel.admin.branches.edit', secureToken($branch->id)) }}" title="Edit" class="dropdown-item"><i class="ik ik-edit mr-2"> </i>@lang('ui.edit')</a>
                            </li>
                            <li class="p-0">
                                <a href="{{ route('panel.admin.zones.index', ['branch_id' => secureToken($branch->id)]) }}" title="Zone Management" class="dropdown-item"><i class="ik ik-layers mr-2"> </i>@lang('ui.zone') @lang('ui.management')</a>
                            </li>
                            <hr class="m-1 b-0">
                            @if (env('DEV_MODE') == 1)
                                <li class="p-0">
                                    <a href="{{ route('panel.admin.branches.destroy', secureToken($branch->id)) }}"
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
                    {{ $branch->getPrefix() }}
                </div>
            </td>
            @endif

            <td class="col_3 max-w-150">{{ Str::limit(@$branch->name, 15) }}</td>
            <td>{{$branch->address ?? "--"}}</td>
            <td class="col_3 max-w-150">
                <a href="{{ route('panel.admin.zones.index', ['branch_id' => secureToken($branch->id)]) }}" class="fw-700" title="Zone Management">
                    {{ @$branch->zones->count() ?? 0 }}
                </a>
            </td>
            
            <td class="col_8">{{ @$branch->formatted_updated_at ?? '--' }}</td>
            <td class="col_8">{{ @$branch->formatted_created_at ?? '--' }}</td>
        </tr>
        @endforeach
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
