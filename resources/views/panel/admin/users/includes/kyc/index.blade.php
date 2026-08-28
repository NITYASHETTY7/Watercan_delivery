<div id="ajax-container">
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
                <x-button type="button" id="kyc_export_button" class="btn btn-light btn-sm"
                    data-table="#user_kycs_table" data-file="UserKyc"> @lang('ui.btn_excel') </x-button>
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
        <table id="user_kycs_table" class="table p-0">
            <thead>
                <tr>
                    <th style="width: 5%;" scope="col">{{ __('ui.sno') }}</th>
                    <th style="width: 25%;" class="col-2">{{ __('ui.id') }}</th>
                    <th style="width: 15%;" class="col-2" title="{{ __('ui.document_name') }}">
                        {{ __('ui.document_name') }}</th>
                    <th style="width: 15%;" class="col-2" title="{{ __('ui.name') }}">{{ __('ui.name') }}</th>
                    <th style="width: 15%;" class="col-2" title="{{ __('ui.document_no') }}">{{ __('ui.number') }}
                    </th>
                    <th style="width: 15%;" class="col-2">{{ __('ui.status') }}</th>
                    <th style="width: 20%;" class="col-2">{{ __('ui.created_at') }}</th>
                </tr>
            </thead>
            <tbody class="no-data">
                @if (@$user_kycs->count() > 0)
                    @foreach ($user_kycs as $userKyc)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> <a href="{{ route('panel.admin.user-kyc.show', secureToken($userKyc->id)) }}"
                                    class="table-link p-1">{{ $userKyc->getPrefix() }}</a> </td>
                            <td> {{ $userKyc->details['document_name'] ?? '' }} </td>
                            <td> {{ $userKyc->details['name'] ?? '' }} </td>
                            <td> {{ $userKyc->details['document_number'] ?? '' }} </td>
                            <td> <span
                                    class="badge bagde-{{ App\Models\UserKyc::STATUSES[$userKyc->status]['color'] }}">{{ App\Models\UserKyc::STATUSES[$userKyc->status]['label'] ?? '' }}</span>
                            </td>
                            <td> {{ $userKyc->created_at->format('Y-m-d') ?? '' }}</td>
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
                    {{ $user_kycs->appends(request()->except('page'))->links() }}
                </div>
            </div>
            <div class="col-lg-6 mobile-mt-20 d-flex justify-content-end">
                @if (@$user_kycs->lastPage() ?? '' > 1)
                    <div class="d-flex align-items-center" for="jumpTo">
                         <div class="mr-2 jumpTo">
                            @lang('ui.jump_to') :
                        </div>
                        <div class="d-flex align-items-center">
                            <x-input type="number" class="form-control" id="jumpTo" name="page"
                                value="{{ $user_kycs->currentPage() ?? '' }}" validation="empty" />
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $user_kycs->lastPage() ?? '' }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- push external js -->
@push('script')
    {{-- START HTML TO EXCEL INIT --}}
    <script type="text/javascript" src="https://unpkg.com/xlsx@0.15.1/dist/xlsx.full.min.js"></script>

    <script>
        function html_user_kyc_table_to_excel(type) {
            var table_core = $("#user_kycs_table").clone();
            var clonedTable = $("#user_kycs_table").clone();
            clonedTable.find('[class*="no-export"]').remove();
            clonedTable.find('[class*="d-none"]').remove();
            $("#user_kycs_table").html(clonedTable.html());

            // Report metadata
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
                    'value': "User-KYC"
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

            // Restore the original table structure
            $("#user_kycs_table").html(table_core.html());
        }

        $(document).on('click', '#kyc_export_button', function() {
            html_user_kyc_table_to_excel('xlsx');
        });
    </script>
    {{-- END HTML TO EXCEL INIT --}}
    @include('panel.admin.include.bulk_script.index')
@endpush
