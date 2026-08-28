<div style="max-height:75vh;overflow:auto;">
    <div class="row">
        @if (@$zones->count() > 0)
            @foreach (@$zones as $zone)
                <div class="col-md-12 col-lg-12 mb-2" id="{{ @$zone->id }}">
                    <div class="card h-100 border border-1"style="box-shadow:none !important;">
                        <div
                            class="card-header border-bottom-0 p-2 d-flex justify-content-between align-items-center"style="background:#b8d8e191;">
                            <div class="">
                                @if (!isset($print_mode))
                                    <div class="d-flex align-items-center">
                                        <x-checkbox type="checkbox" :arr="[]"
                                            class="mr-2 delete_Checkbox text-center" name="id"
                                            value="{{ @$zone->id }}" validation="empty" />
                                        <div class="d-flex align-items-center ">
                                            <i class="ik ik-globe mr-1 text-primary"></i>
                                            <h6 class="fw-bold text-dark mb-0">
                                                {{ Str::limit(@$zone->name, 25) }}
                                            </h6>
                                        </div>
                                    </div>
                                @endif
                            </div>


                            @if (!isset($print_mode))
                                <div class="d-flex align-items-center">
                                    <div class="dropdown">
                                        <x-button class="dropdown-toggle bg-transparent border-0" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            title="Actions">
                                            <i class="ik ik-more-vertical"></i>
                                        </x-button>
                                        <ul class="dropdown-menu dropdown-menu-right multi-level" role="menu"
                                            aria-labelledby="dropdownMenu">
                                            <li class="p-0">
                                                <a href="{{ route('panel.admin.zones.edit', secureToken($zone->id)) }}"
                                                    title="Edit Zone" class="dropdown-item"><i class="ik ik-edit mr-2">
                                                    </i>@lang('ui.edit') Zone</a>
                                            </li>
                                            <li class="p-0">
                                                <a href="{{ route('panel.admin.zone-pincodes.index', ['zone_id' => secureToken($zone->id)]) }}"
                                                    title="View Pincodes" class="dropdown-item"><i
                                                        class="ik ik-map-pin mr-2">
                                                    </i>@lang('ui.zone') @lang('ui.pincode')s</a>
                                            </li>
                                            <hr class="m-1 b-0">
                                            @if (env('DEV_MODE') == 1)
                                                <li class="p-0">
                                                    <a href="{{ route('panel.admin.zones.destroy', secureToken($zone->id)) }}"
                                                        title="Delete Zone"
                                                        class="dropdown-item delete-item text-danger fw-700">
                                                        <i class="ik ik-trash mr-2"> </i> @lang('ui.delete') Zone
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="card-body py-1 px-2">
                            <div class="mb-4">
                                <h6
                                    class="text-secondary small mb-2 d-flex justify-content-between align-items-center">
                                    Associated Pincodes
                                    <a href="{{ route('panel.admin.zone-pincodes.create', ['zone_id' => secureToken($zone->id)]) }}"
                                        class="btn btn-light py-1 px-2 d-flex align-items-center gap-1"
                                        title="Add new pincode to {{ $zone->name }}">
                                        <i class="ik ik-plus icon-xs"></i>
                                        Add New
                                    </a>
                                </h6>
                                <div style="max-height: 180px; overflow: auto; padding-right: 5px;">
                                    <ul class="list-unstyled mb-0">
                                        @forelse ($zone->zonePincodes as $zonePincode)
                                            <li
                                                class="d-flex align-items-center mb-1 p-1 {{ !$loop->last ? 'border-bottom' : '' }}">
                                                <div style="flex-grow: 1;">
                                                    <a href="{{ route('panel.admin.zone-pincodes.edit', secureToken($zonePincode->id)) }}"
                                                        class="text-decoration-none text-body fw-semibold d-flex align-items-center"
                                                        title="Edit pincode {{ $zonePincode->pincode }}">
                                                        <i class="ik ik-map-pin text-muted icon-xs mr-2"></i>
                                                        <span class="text-dark">{{ $zonePincode->pincode }}</span>
                                                    </a>
                                                </div>

                                                <div class="d-flex align-items-center ml-auto">
                                                    <a href="{{ route('panel.admin.zone-pincode-users.index', [
                                                        'zone_pincode_id' => secureToken($zonePincode->id),
                                                    ]) }}"
                                                        class="badge badge-light text-dark fw-bold px-2 py-1 mr-2"
                                                        title="View users in {{ $zonePincode->pincode }}">
                                                        Users: {{ $zonePincode->zonePincodeUsers->count() ?? 0 }}
                                                    </a>

                                                    <a href="{{ route('panel.admin.zone-pincode-users.create', [
                                                        'zone_pincode_id' => secureToken($zonePincode->id),
                                                    ]) }}"
                                                        class="btn btn-sm btn-icon btn-outline-secondary p-0 d-flex align-items-center justify-content-center"
                                                        style="width: 24px; height: 24px;"
                                                        title="Add User to pincode {{ $zonePincode->pincode }}">
                                                        <i class="ik ik-user-plus icon-xs"></i>
                                                    </a>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="text-muted fst-italic text-center small py-2">No pincodes linked
                                                to
                                                this zone yet.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-top py-2">
                            <div class="d-flex justify-content-between text-muted mb-2">
                                <span>Total Pincodes: <strong>{{ $zone->zonePincodes->count() }}</strong></span>
                                <span>Total Users:
                                    <strong>{{ $zone->zonePincodes->sum(fn($p) => $p->zonePincodeUsers->count()) }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            @include('panel.admin.include.components.no_data_img.index')
                            <p class="text-muted mt-3">No zones found.</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
