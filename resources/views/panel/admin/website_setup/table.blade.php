<table id="page_table" class="table aiz-table mb-0">
    <thead>
        <tr>
            <th width="10%" class="">
                {{-- @if (getSetting('toggling_pages_activation_checkbox', @$master_setting))
                    <x-checkbox type="checkbox" :arr="[]" class="mr-2 allChecked" name="id" value="" validation="empty" />
                @endif --}}
                @lang('ui.sno')
            </th>
            <th width="10%" class="no-export"> @lang('ui.actions')
            </th>
            <th width="15%" class="no-export">@lang('ui.#')<div class="table-div"><i class="ik ik-arrow-up asc"
                        data-val="id"></i><i class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            <th width="30%" class="col_1"> @lang('ui.name') </th>
            {{-- <th width="20%" class="col_2"> @lang('ui.visibility') </th> --}}
            <th width="15%" class="no-export"><i class="icon-head" data-title="Created At"><i
                        class="fa-regular fa-clock pl-30"></i>
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                            class="ik ik-arrow-down desc" data-val="created_at"></i></div>
                </i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($websitePages->isNotEmpty())
            @foreach (@$websitePages as $websitePage)
                <tr id="{{ @$websitePage->id }}">
                    <td class="">
                        {{-- @if (getSetting('toggling_pages_activation_checkbox', @$master_setting))
                            <x-checkbox type="checkbox" :arr="[]" class="mr-2 delete_Checkbox text-center"
                                name="id" value="{{ @$websitePage->id }}" validation="empty" />
                        @endif --}}
                        {{ $loop->iteration }}
                    </td>
                    <td class="no-export">
                        <div class="dropdown d-flex">
                            <x-button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                @lang('ui.actions')
                            </x-button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                @if ($master_permissions->contains('show_page') && @$websitePage->slug)
                                    <li class="p-0"><a href="{{ route('page.slug', $websitePage->slug) }}"
                                            title="" class="btn btn-sm dropdown-item ">Show</a></li>
                                @endif
                                @if ($master_permissions->contains('page_edit_rp'))
                                    <li class="p-0"><a
                                            href="{{ route('panel.admin.website-pages.edit', secureToken($websitePage->id)) }}"
                                            title="Edit Website Page" class="btn btn-sm dropdown-item "><i
                                                class="ik ik-edit mr-2"></i>@lang('ui.edit')</a></li>
                                @endif
                                @if ($master_permissions->contains('page_delete_rp'))
                                    @if (@$websitePage->is_permanent != 1)
                                        <hr class="m-1 b-0">
                                        <li class="p-0"><a
                                                href="{{ route('panel.admin.website-pages.destroy', secureToken($websitePage->id)) }}"
                                                title="Delete Website Page"
                                                class="btn btn-sm delete-item text-danger fw-700 dropdown-item "><i
                                                    class="ik ik-trash"></i> @lang('ui.delete')</a></li>
                                    @endif
                                @endif
                            </ul>
                        </div>
                    </td>
                    <td class="col_1">{{ @$websitePage->getPrefix() }}</td>
                    <td class="col_1">{{ $websitePage->title ?? '--' }}</td>
                    {{-- <td class="col_2"><span
                            class="badge badge-{{ getPublishStatus(@$websitePage->status)['color'] }}">{{ getPublishStatus(@$websitePage->status)['name'] ?? '--' }}</span>
                    </td> --}}
                    <td>{{ $websitePage->formatted_created_at ?? '--' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
