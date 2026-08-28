<table id="table" class="table p-0">
    <thead>
        <tr>
            @if (!isset($print_mode))
            <th class="col_1" width="8%">
                @if (@$bulk_activation == 1)
                @if (getSetting('toggling_user_management_bulk_status_update', @$master_setting) ||
                getSetting('toggling_user_management_bulk_delete', @$master_setting) ||
                getSetting('toggling_user_management_bulk_upload', @$master_setting))
                <x-checkbox type="checkbox" :arr="[]" class="mr-2 allChecked" name="id" value="" validation="empty" />
                @endif
                @endif
                @lang('ui.sno')
            </th>
            <th class="col_1 no-export" width="8%">
                @lang('ui.actions')
            </th>
            <th class="col_2 no-export" width="8%"> @lang('ui.#')
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            @endif
            <th class="col_3" width="15%"> @lang('ui.name') 
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="first_name"></i><i class="ik ik-arrow-down desc" data-val="first_name"></i></div>
            </th>
            <th class="col_3" width="15%">
                @lang('ui.email')
            </th>
            <th class="col_3" width="15%">
                @lang('ui.phone')
            </th>
            {{-- <th class="col_7" width="8%">DOB</th> --}}
            <th class="col_7" style="width: 5%;">@lang('ui.status')</th>
            @if(request()->has('role') && request()->get('role') == 'User')
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.account_type')</th>
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.business_name')</th>
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.gst_number')</th>
            @endif
            @if(request()->has('role') && request()->get('role') == 'Driver')
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.vehicle_name')</th>
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.vehicle_type')</th>
            <th class="col_7" width="8%" style="min-width: 100px;">@lang('ui.vehicle_number')</th>
            @endif
            <th class="col_7" width="8%" style="min-width: 100px;"> @lang('ui.updated_at') </th>
            <th class="col_8" width="10%" style="min-width: 100px;"><i class="icon-head" data-title="Join At" title="Created At"><i
                        class="fa-regular fa-clock"></i></i>
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                        class="ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$users->count() > 0)
        @foreach (@$users as $user)
        <tr id="{{ @$user->id }}">
            @if (!isset($print_mode))
            <td class="col_1">
                @if (@$bulk_activation == 1)
                    @if (getSetting('toggling_user_management_bulk_status_update', @$master_setting) ||
                    getSetting('toggling_user_management_bulk_delete', @$master_setting) ||
                    getSetting('toggling_user_management_bulk_upload', @$master_setting))
                    <x-checkbox type="checkbox" :arr="[]" class="mr-2 delete_Checkbox text-center" name="id"
                        value="{{ @$user->id }}" validation="empty" />
                    @endif
                @endif
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
                            @if ($master_permissions->contains('user_edit_rp'))
                            <li class="p-0">
                                <a href="{{ route('panel.admin.users.edit', secureToken($user->id)) }}" title="Edit"
                                    class="dropdown-item"><i class="ik ik-edit mr-2">
                                    </i>@lang('ui.edit')</a>
                            </li>
                            @endif

                            <hr class="m-1 b-0">
                            @if (env('DEV_MODE') == 1)
                            <li class="p-0">
                                <a href="{{ route('panel.admin.users.destroy', secureToken($user->id)) }}"
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
                <a class="table-link p-1"
                    href="{{ route('panel.admin.users.show', [secureToken($user->id), 'active' => $user->role_name == 'Admin' ? 'password-tab' : 'account-verification']) }}">
                    {{ $user->getPrefix() }}
                </a>
            </td>
            @endif

            <td class="col_3 max-w-150">{{ Str::limit(@$user->full_name, 15) }}@if (@$user->is_verified)
                @endif
            </td>
            <td class="col_5">
                <div style="display: flex; flex-direction: column;">
                    <div>{{ @$user->email ?? '--' }}</div>
                </div>
            </td>
            <td class="col_8">{{ @$user->phone ?? '--' }}</td>
            {{-- <td class="col_8">{{ @$user->dob ?? '--' }}</td> --}}
            <td class="col_7 status-{{ $user->id }} p-2 mt-3" data-status="{{ $user->status }}">
                <span
                    class="badge badge-{{ $user->status_parsed->color }}">{{ $user->status_parsed->label }}</span>
            </td>
            @if(request()->has('role') && request()->get('role') == 'User')
                <td>
                    {{ @\App\Models\User::ACCOUNT_TYPES[@$user->account_type]['label'] ?? '--' }}
                </td>
                <td>
                    {{ @$user->business_payload ? @$user->business_payload['company_name'] : '--' }}
                </td>
                <td>
                    {{ @$user->business_payload ? @$user->business_payload['gst_number'] : '--' }}
                </td>
            @endif
            @if(request()->has('role') && request()->get('role') == 'Driver')
                <td>
                    {{ @$user->vehicle_details ? @$user->vehicle_details['vehicle_name'] : '--' }}
                </td>
                <td>
                    {{ @$user->vehicle_details ? @$user->vehicle_details['vehicle_type'] : '--' }}
                </td>
                <td>
                    {{ @$user->vehicle_details ? @$user->vehicle_details['vehicle_number'] : '--' }}
                </td>
            @endif
            <td class="col_8">{{ @$user->formatted_updated_at ?? '--' }}</td>
            <td class="col_8">{{ @$user->formatted_created_at ?? '--' }}</td>
        </tr>
        @endforeach
        @else
            <tr>
                <td class="text-center" colspan="12">@include('panel.admin.include.components.no_data_img.index')</td>
            </tr>
        @endif
    </tbody>
</table>
