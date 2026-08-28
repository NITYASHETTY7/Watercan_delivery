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
            <x-button type="button" data-table="#bank_table" data-file="Bank" id="bank_export_button"
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
<div class="table-responsive">
    <table id="bank_table" class="table">
        <thead>
            <tr>
                <th width="6%"> @lang('ui.sno')</th>
                <th class="no-export" width="6%">@lang('ui.actions')</th>
                <th width="23%"> @lang('ui.bank')</th>
                <th width="25%" title="Account Holder Name">@lang('ui.ahn')</th>
                <th width="12%">@lang('ui.account_number')</th>
                <th width="10%">@lang('ui.ifsc_code')</th>
                <th width="6%">@lang('ui.branch')</th>
                <th width="6%"> @lang('ui.type')</th>
            </tr>
        </thead>

        <tbody class="no-data">
            @if ($bankDetails && $bankDetails->count() > 0)
                @foreach ($bankDetails as $bankDetail)
                    <tr>
                        <td title="{{ $bankDetail->getPrefix() }}">{{ @$loop->iteration }}</td>
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <x-button class="dropdown-toggle btn btn-secondary" type="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    @lang('ui.action')
                                </x-button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                    <li class="dropdown-item p-0"><a href="javascript:void(0)"
                                            class="btn btn-sm edit-btn editPayoutDetailBtn fw-400" title=""
                                            data-row="{{ @$bankDetail }}" data-original-title="Edit"> <i
                                                class="ik ik-edit mr-2"></i> @lang('ui.edit')</a>
                                    </li>
                                    <a href="{{ route('panel.admin.user-banks.destroy', secureToken($bankDetail->id)) }}"
                                        title="Delete Bank" class="dropdown-item  delete-item">
                                        <li class="p-0 text-danger fw-700"><i
                                                class="ik ik-trash mr-2"></i>@lang('ui.delete')</li>
                                    </a>

                                </ul>
                            </div>
                        </td>
                        <td class="col_3">
                            {{ @\App\Models\UserBank::BANK_NAMES[$bankDetail->bank_id]['label'] ?? '--' }}
                        </td>
                        <td>{{ Str::limit($bankDetail->account_holder_name, 12) }} </td>
                        <td>{{ $bankDetail->account_number ?? '' }}</td>
                        <td>{{ $bankDetail->bank_ifsc_code ?? '' }}</td>
                        <td>{{ $bankDetail->branch ?? '' }}</td>
                        <td>{{ $bankDetail->account_type == 0 ? 'Current ' : 'Saving' }}</td>
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
                {{ @$bankDetails->appends(request()->except('page'))->links() ?? '' }}
            </div>
        </div>
        <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
            @if (@$bankDetails->lastPage() ?? '' > 1)
                <div class="d-flex align-items-center" for="jumpTo">
                    <div class="mr-2">
                        @lang('ui.jump_to') :
                    </div>
                    <div class="d-flex align-items-center">
                        <x-input type="number" class="form-control" id="jumpTo" name="page"
                            value="{{ @$bankDetails->currentPage() ?? '' }}" validation="empty" />
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ @$bankDetails->lastPage() ?? '' }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- push external js -->
@push('script')
    <script type="text/javascript" src="{{ asset('panel/admin/plugins/xlsx/full.min.js') }}"></script>

    {{-- STATRT ADD EDIT bankDetails INIT --}}
    <script>
        $(document).on('click', '#addbankDetailBtn', function() {
            $('#addBankDetailsModal').modal('show');
        });
        $(document).on('click', '.close', function() {
            $('#bankDetailsModalCenter').modal('hide');
        });
    </script>
    <script>
        $(document).on('click', '#editbankDetailBtn', function() {
            $('#editBankDetailsModal').modal('show');
        });
        $(document).on('click', '.close', function() {
            $('#editBankDetailsModal').modal('hide');
        });
    </script>



    {{-- END ADD EDIT bankDetails INIT --}}

    {{-- START HTML TO EXCEL INIT --}}

    <script>
        function html_bank_table_to_excel(type) {
            var table_core = $("#bank_table").clone();
            var clonedTable = $("#bank_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#bank_table").html(clonedTable.html());

            // Use in reverse format beacuse we are prepending it.
            var report_format = [{
                    'label': "Status",
                    'value': "{{ request()->get('status') ?? 'All Status' }}"
                },
                {
                    'label': "Date Range",
                    'value': "{{ request()->get('from') ?? 'N/A' }} - {{ request()->get('to') ?? 'N/A' }}"
                },
                {
                    'label': "Report Name",
                    'value': "User-Bank-Details"
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
            var reportName = report_format[2]['value'] + "-Exported-At-" + formattedDate + "-via-" +
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

            $("#bank_table").html(table_core.html());
        }

        $(document).on('click', '#bank_export_button', function() {
            html_bank_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
@endpush
