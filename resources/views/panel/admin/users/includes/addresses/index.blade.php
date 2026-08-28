<div class="table-controller mb-2">
    <div class="d-flex justify-content-between">
        <div class="mr-3">
            <label for="length" class="d-flex align-items-center">
                @lang('ui.show')
                <x-select id="length" name="length" class="form-control mx-2" :arr="tableLimits()"
                    value="{{ @request()->length ?? '10' }}" label="" optionName="option" valueName="option"
                    validation="empty" />
                @lang('ui.entries')
            </label>
        </div>
        <div>
            <x-button type="button" data-table="#address_table" data-file="Address" id="address_export_button"
                class="btn btn-light btn-sm">@lang('ui.btn_excel')</x-button>
        </div>
    </div>
    <div class="d-flex justify-content-between">
        <div>
            <x-input name="search" placeholder="{{ __('ui.left_sidebar_search') }}" type="text" tooltip=""
                regex="" validation="empty" value="{{ request()->get('search') }}" />
        </div>
    </div>
</div>
<div class="table-responsive table">
    <table id="address_table" class="table p-0">
        <thead>
            <tr>
                <th width="8%"> @lang('ui.sno') </th>
                <th class="no-export" width="10%"> @lang('ui.actions') </th>
                <th class="no-export" width="10%"> @lang('ui.#') </th>
                <th width="20%"> @lang('ui.type') </th>
                <th width="52%"> @lang('ui.location') </th>
            </tr>
        </thead>
        <tbody class="no-data bg-dark-theme">
            @if ($addresses != null)
                @foreach ($addresses as $address)
                    @php
                        $address_decoded = $address->details;
                       
                    @endphp

                    <tr>
                        <td>{{ $loop->iteration ?? '--' }}</td>
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <x-button class="dropdown-toggle btn btn-secondary" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('ui.action')
                                </x-button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-item p-0"><a href="javascript:void(0)"
                                            class="btn btn-sm edit-btn editAddress fw-400" title=""
                                            data-id="{{ @$address }}" data-original-title="Edit"> <i
                                                class="ik ik-edit mr-2"></i>
                                            @lang('ui.edit')</a>
                                    </li>
                                    <li class="dropdown-item p-0"><a
                                            href="{{ route('panel.admin.addresses.destroy', @$address->id) }}"
                                            class="btn btn-sm delete-item text-danger fw-700" title=""
                                            data-original-title="delete"> <i class="ik ik-trash mr-2"></i>
                                            @lang('ui.delete')</a>
                                    </li>
                                </ul>
                            </div>
                        </td>
                        <td>
                            {{ $address->getPrefix() }}
                        </td>
                        <td>
                            {{ isset($address_decoded['type']) && $address_decoded['type'] == 0 ? 'HOME' : 'OFFICE' }}

                        </td>
                        <td>
                            {!! renderAddress($address_decoded) !!}
                        </td>


                    </tr>
                @endforeach
            @else
                <tr>
                    <td class="text-center" colspan="8">@include('panel.admin.include.components.no_data_img.index')</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div class="card-footer">
    <div class="row align-items-center">
        <div class="col-lg-6 d-flex justify-content-start">
            <div class="pagination mobile-justify-center">
                {{ $addresses->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
            @if (@$addresses->lastPage() ?? '' > 1)
                <div class="d-flex align-items-center" for="jumpTo">
                    <div class="mr-2">
                        @lang('ui.jump_to') :
                    </div>
                    <div class="d-flex align-items-center">
                        <x-input type="number" class="form-control" id="jumpTo" name="page"
                            value="{{ @$addresses->currentPage() ?? '' }}" validation="empty" />
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ @$addresses->lastPage() ?? '' }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>



<!-- push external js -->
@push('script')
    <script type="text/javascript" src="{{ asset('panel/admin/plugins/xlsx/full.min.js') }}"></script>
    {{-- START HTML TO EXCEL INIT --}}
    <script>
        function html_address_table_to_excel(type) {
            var table_core = $("#address_table").clone();
            var clonedTable = $("#address_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            clonedTable = tableHeadIconFixer(clonedTable);
            $("#address_table").html(clonedTable.html());

            var report_format = [{
                    'label': "Date Range",
                    'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                },
                {
                    'label': "Report Name",
                    'value': "User-Addresses"
                },
                {
                    'label': "Company",
                    'value': "{{ env('APP_NAME') }}"
                }
            ];

            // Get current date and time in required format (Y-M-D-HH-MM-SS)
            var now = new Date();
            var formattedDate = now.getFullYear() + "-" +
                String(now.getMonth() + 1).padStart(2, '0') + "-" +
                String(now.getDate()).padStart(2, '0') + "-" +
                String(now.getHours()).padStart(2, '0') + "-" +
                String(now.getMinutes()).padStart(2, '0') + "-" +
                String(now.getSeconds()).padStart(2, '0');

            // Get the full name from the input field (fallback to 'Unknown User' if empty)
            var fullName = "{{ auth()->check() ? str_replace(' ', '-', auth()->user()->full_name) : 'Unknown' }}";

            // Construct the file name using the desired format
            var reportName = report_format[1]['value'] + "-Exported-At-" + formattedDate + "-via-" +
                "{{ env('APP_NAME') }}" + "-By-" + fullName;

            // Create a single blank row
            var blankRow = document.createElement('tr');
            var blankCell = document.createElement('th');
            blankCell.colSpan = clonedTable.find('thead tr th').length;
            blankRow.appendChild(blankCell);

            // Append the blank row to the cloned table's thead
            clonedTable.find('thead').prepend(blankRow);
            // Iterate through the report_format array and add metadata rows to the cloned table's thead
            $.each(report_format, function(index, item) {
                var metadataRow = document.createElement('tr');
                var labelCell = document.createElement('th');
                var valueCell = document.createElement('th');

                labelCell.innerHTML = item.label;
                valueCell.innerHTML = item.value;

                metadataRow.appendChild(labelCell);
                metadataRow.appendChild(valueCell);

                clonedTable.find('thead').prepend(metadataRow);
            });

            var data = clonedTable[0]; // Use the cloned table for export

            var file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });

            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });

            // Write the file with the report name and time formatted with minus
            XLSX.writeFile(file, reportName + '.' + type);

            $("#address_table").html(table_core.html());
        }

        $(document).on('click', '#address_export_button', function() {
            html_address_table_to_excel('xlsx');
        });

        function tableHeadIconFixer(clonedTable) {

            clonedTable.find('i.icon-head').each(function() {
                var dataTitle = $(this).data('title');
                $(this).replaceWith(dataTitle);
            });
            return clonedTable;
        }
    </script>
    {{-- END HTML TO EXCEL INIT --}}


    {{-- START ADDRESS INIT --}}
    <script>
        function getStates(countryId = 101) {
            $.ajax({
                url: "{{ route('world.get-states') }}",
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#state').html(res).css('width', '100%');
                }
            })
        }

        function getCities(stateId = 101) {
            $.ajax({
                url: "{{ route('world.get-cities') }}",
                method: 'GET',
                data: {
                    state_id: stateId
                  
                },
                success: function(res) {
                    $('#city').html(res).css('width', '100%');
                }
            })
        }

        function getEditStates(countryId = 101) {
            $.ajax({
                url: "{{ route('world.get-states') }}",
                method: 'GET',
                data: {
                    country_id: countryId
                },
                success: function(res) {
                    $('#stateEdit').html(res).css('width', '100%');
                }
            })
        }

        function getEditCities(stateId = 101) {
            $.ajax({
                url: "{{ route('world.get-cities') }}",
                method: 'GET',
                data: {
                    state_id: stateId
                },
                success: function(res) {
                    $('#cityEdit').html(res).css('width', '100%');
                }
            })
        }

        // getStates();
        $(document).ready(function() {

            $('#country').on('change', function() {
                getStates($(this).val());
                
            });

            $('#state').on('change', function() {
                getCities($(this).val());
            });
            $('#countryEdit').on('change', function() {
                getEditStates($(this).val());
               
            });

            $('#stateEdit').on('change', function() {
                getEditCities($(this).val());
                
            });
        });


        function getStateAsync(countryId) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '{{ route('world.get-states') }}',
                    method: 'GET',
                    data: {
                        country_id: countryId
                    },
                    success: function(data) {
                        $('#state').html(data);
                        $('.state').html(data);
                        resolve(data)
                    },
                    error: function(error) {
                        reject(error)
                    },
                })
            })
        }

        function getCityAsync(stateId) {
            if (stateId != "") {
                return new Promise((resolve, reject) => {
                    $.ajax({
                        url: '{{ route('world.get-cities') }}',
                        method: 'GET',
                        data: {
                            state_id: stateId
                        },
                        success: function(data) {
                            $('#city').html(data);
                            $('.city').html(data);
                            resolve(data)
                        },
                        error: function(error) {
                            reject(error)
                        },
                    })
                })
            }
        }
    </script>
@endpush
