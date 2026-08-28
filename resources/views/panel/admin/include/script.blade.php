<!-- jQuery -->
<script src="{{ asset($root_directory . 'plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>
<!-- jQuery Validation -->
<script src="{{ asset($root_directory . 'plugins/jquery-validate-1.19.3/jquery.validate.min.js') }}"></script>
<!-- Core Libraries -->

<script src="{{ asset($root_directory . 'plugins/bootstrap-bundle/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/js/all.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/dist/js/theme.js') }}"></script>


<!-- DataTables -->
<script src="{{ asset($root_directory . 'plugins/DataTables/Cell-edit/dataTables.cellEdit.js') }}"></script>

<!-- NProgress -->
<script src="{{ asset($root_directory . 'plugins/nprogress/nprogress.js') }}"></script>

<!-- Form Handling -->
<script src="{{ asset($root_directory . 'plugins/form/ajaxForm.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/form/index-page.js') }}"></script>

<!-- Confirmation Dialog -->
<script src="{{ asset($root_directory . 'plugins/jquery-confirm-3.3.2/jquery-confirm.min.js') }}"></script>

<!-- FontAwesome -->
<script src="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/all.min.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset($root_directory . 'plugins/select2/dist/js/select2.min.js') }}"></script>

<!-- Country Code Selector -->
<script src="{{ asset($root_directory . 'plugins/country-code/intl-tel-input.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/base/leaflet.js') }}"></script>

<!-- Uncomment if required -->
<!-- <script src="{{ asset($root_directory . 'plugins/country-code/utils.js') }}"></script> -->

<!-- Bootstrap Tags Input -->
<script src="{{ asset($master_root_directory . 'plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}">
</script>

<!-- CKEditor -->
<script src="{{ asset($master_root_directory . 'plugins/ckeditor5/ckeditor.js') }}"></script>

<!-- XLSX -->
<script src="{{ asset($master_root_directory . 'plugins/xlsx/full.min.js') }}"></script>

<!-- Role Handling -->
<script src="{{ asset($root_directory . 'plugins/role/get-role.js') }}"></script>

<script>
    var paceOptions = {
        ajax: true
    }
</script>

<script src="{{ asset($root_directory . 'plugins/jquery-toast-plugin/dist/jquery.toast.min.js') }}"></script>

<script src="{{ asset($root_directory . 'plugins/js/main.js') }}"></script>
{{-- start ------------ important js code must include in all backend pages  --}}

<!-- Include Required Prerequisites -->

<script src="{{ asset($root_directory . 'plugins/momentjs/moment.min.js') }}"></script>

<!-- Include Date Range Picker -->
<script type="text/javascript" src="{{ asset($root_directory . 'plugins/date-picker/daterangepicker.js') }}"></script>

{{-- File Uploader Script --}}
@include('panel.common.script.file-upload')
{{-- End File Uploader Script --}}

@stack('script')

@if (getSetting('voice_input') == 1)
    <script src="{{ asset($root_directory . 'plugins/speech-recognition/speechRecognition.js') }}"></script>
@endif

<script type="text/javascript">
    var ajaxMessage = '.ajax-message';
    $('select.select2').select2();
    $(function() {
        let dateInterval = getQueryParameter('date_filter');
        let start = moment().startOf('isoWeek');
        let end = moment().endOf('isoWeek');
        if (dateInterval) {
            dateInterval = dateInterval.split(' - ');
            start = dateInterval[0];
            end = dateInterval[1];
        }
        $('#date_filter').daterangepicker({
            "showDropdowns": true,
            "showWeekNumbers": true,
            "alwaysShowCalendars": true,
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY-MM-DD',
                firstDay: 1,
            },
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                    'month').endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
                'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year')
                    .endOf('year')
                ],
                'All time': [moment().subtract(30, 'year').startOf('month'), moment().endOf('month')],
            }
        });
    });

    function getQueryParameter(name) {
        const url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        const regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            fallbackCopyTextToClipboard(text);
            return;
        }
        navigator.clipboard.writeText(text).then(function() {
            console.log('Async: Copying to clipboard was successful!');
        }, function(err) {
            console.error('Async: Could not copy text: ', err);
        });
    }
</script>
{{-- Date Range Filter JS Code End --}}

<script>
    function menuSearch() {
        var filter, item;
        filter = $("#menu-search").val().trim().toLowerCase();
        items = $("#main-menu-navigation").find("a");
        items = items.filter(function(i, item) {
            if ($(item).html().trim().toLowerCase().indexOf(filter) > -1 && $(item).attr('href') !== '#') {
                return item;
            }
        });
        if (filter !== '') {
            $("#main-menu-navigation").addClass('d-none');
            $("#search-menu-navigation").html('')
            if (items.length > 0) {
                for (i = 0; i < items.length; i++) {
                    const text = $(items)[i].innerText;
                    const link = $(items[i]).attr('href');
                    $("#search-menu-navigation").append(
                        `<div class="nav-item"><a href="${link}" class="a-item"><i class="ik ik-more-horizontal"></i><span>${text}</span></a></li`
                    );
                }
            } else {
                $("#search-menu-navigation").html(
                    `<div class="nav-item"><span	class="text-center text-muted d-block"> @lang('ui.nothing_found') </span></div>`
                );
            }
        } else {
            $("#main-menu-navigation").removeClass('d-none');
            $("#search-menu-navigation").html('')
        }
    }

    function refreshCheckboxes() {
        var elem = Array.prototype.slice.call(document.querySelectorAll('.tbl-js-switch'));
        elem.forEach(function(html) {
            var switchery = new Switchery(html, {
                color: '#4099ff',
                jackColor: '#fff',
                size: 'small'
            });
        });
    }

    $('.sidebar-content').animate({
        scrollTop: $('.active').offset().top - 70
    }, 1000);
</script>

{{-- end ------------ important js code must include in all backend pages  --}}

@if (session('success'))
    <script>
        $.toast({
            heading: 'SUCCESS',
            text: "{{ session('success') }}",
            showHideTransition: 'slide',
            icon: 'success',
            loaderBg: '#f96868',
            position: 'top-right'
        });
    </script>
@endif


@if (session('error'))
    <script>
        $.toast({
            heading: 'ERROR',
            text: "{{ session('error') }}",
            showHideTransition: 'slide',
            icon: 'error',
            loaderBg: '#f2a654',
            position: 'top-right'
        });
    </script>
@endif

@if (session('warning'))
    <script>
        $.toast({
            heading: 'WARNING',
            text: "{{ session('warning') }}",
            showHideTransition: 'slide',
            icon: 'warning',
            loaderBg: '#f2a654',
            position: 'top-right'
        });
    </script>
@endif
{{-- <script>
    $(document).ready(function(){
    if (!sessionStorage.getItem('tabOpened')) {
        $.ajax({
            url: "{{route('set.session')}}", // Replace this with your API endpoint
            method: "GET",
            dataType: "json",
            success: function(response) {
                // Set session storage to prevent setting the session again on page refresh
                sessionStorage.setItem('tabOpened', true);
            },
            error: function(xhr, status, error) {
                console.error('Error occurred while setting session');
            }
        });
    };
    // Send AJAX request to unset session value when the tab is closed
    window.addEventListener('beforeunload', function(event) {
            $.ajax({
                url: "{{route('unset.session')}}", // Replace this with your API endpoint
                method: "GET",
                dataType: "json",
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error occurred while setting session');
                }
            });
        });
    })
</script> --}}
<script>
    function exportTableHeadIconFixer(clonedTable) {
        clonedTable.find('i.icon-head').each(function() {
            var dataTitle = $(this).data('title') || $(this).attr('title') || 'Column';
            $(this).replaceWith(dataTitle);
        });
        return clonedTable;
    }

    function exportTableToExcel({
        tableSelector = "#table",
        type = "xlsx",
        moduleName = "Report",
        fullName = "Unknown User",
        appName = "MyApp",
        report_format = []
    }) {
        // Ensure report_format is an array
        report_format = Array.isArray(report_format) ? report_format : [];

        // Clone the table
        const $originalTable = $(tableSelector);
        const table_core = $originalTable.clone();
        let clonedTable = $originalTable.clone();

        // Clean export table
        clonedTable.find('[class*="no-export"]').remove();
        clonedTable.find('[class*="d-none"]').remove();
        clonedTable = exportTableHeadIconFixer(clonedTable);

        // Timestamp for filename
        const now = new Date();
        const formattedDate = now.getFullYear() + "-" +
            String(now.getMonth() + 1).padStart(2, '0') + "-" +
            String(now.getDate()).padStart(2, '0') + "-" +
            String(now.getHours()).padStart(2, '0') + "-" +
            String(now.getMinutes()).padStart(2, '0') + "-" +
            String(now.getSeconds()).padStart(2, '0');

        let reportName = `${moduleName}-Exported-At-${formattedDate}-via-${appName}-By-${fullName}`;
        reportName = reportName.replace(/[\\/:*?"<>|]/g, '-'); // Sanitize file name

        // Insert blank row for spacing
        const blankRow = document.createElement('tr');
        const blankCell = document.createElement('th');
        blankCell.colSpan = clonedTable.find('thead tr').first().find('th').length;
        blankRow.appendChild(blankCell);
        clonedTable.find('thead').prepend(blankRow);

        // Insert metadata rows
        $.each(report_format, function(index, item) {
            const metadataRow = document.createElement('tr');
            const labelCell = document.createElement('th');
            const valueCell = document.createElement('th');

            labelCell.innerHTML = item.label;
            valueCell.innerHTML = item.value;

            metadataRow.appendChild(labelCell);
            metadataRow.appendChild(valueCell);
            clonedTable.find('thead').prepend(metadataRow);
        });

        try {
            const data = clonedTable[0];
            const file = XLSX.utils.table_to_book(data, {
                sheet: "sheet1"
            });

            XLSX.write(file, {
                bookType: type,
                bookSST: true,
                type: 'base64'
            });

            XLSX.writeFile(file, `${reportName}.${type}`);
        } catch (error) {
            console.error("Export failed:", error);
            alert("Failed to export table. Please try again.");
        }
    }

    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        let is_ajax = $(this).data('is_ajax');
        let callback = $(this).data('callback');
        let method = $(this).data('method');
        let msg = $(this).data('msg') ?? "You won't be able to revert back!";
        $.confirm({
            draggable: true,
            title: 'Are You Sure!',
            content: msg,
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Delete',
                    btnClass: 'btn-red',
                    action: function() {
                        if (is_ajax == 1) {
                            getData(method, url, "json", null, callback = null, event = null,
                                toast = 1);
                        } else {
                            window.location.href = url;
                        }
                    }
                },
                close: function() {}
            }
        });
    });
    $(document).on('click', '.confirm', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var msg = $(this).data('msg') ?? "You won't be able to revert back!";
        $.confirm({
            draggable: true,
            title: 'Are You Sure!',
            content: msg,
            type: 'blue',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Confirm',
                    btnClass: 'btn-blue',
                    action: function() {
                        window.location.href = url;
                    }
                },
                close: function() {}
            }
        });
    });
    $(document).on('click', '.confirm-form-btn', function(e) {
        e.preventDefault();
        let $this = $(this);
        let msg = $(this).data('msg') ?? "You won't be able to revert back!";
        $.confirm({
            draggable: true,
            title: 'Are You Sure!',
            content: msg,
            type: 'blue',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Confirm',
                    btnClass: 'btn-blue',
                    action: function() {
                        $this.closest('form').submit();
                    }
                },
                close: function() {}
            }
        });
    });

    function updateURL(key, val) {
        var url = window.location.href;
        var reExp = new RegExp("[\?|\&]" + key + "=[0-9a-zA-Z\_\+\-\|\.\,\;]*");

        if (reExp.test(url)) {
            // update
            var reExp = new RegExp("[\?&]" + key + "=([^&#]*)");
            var delimiter = reExp.exec(url)[0].charAt(0);
            url = url.replace(reExp, delimiter + key + "=" + val);
        } else {
            // add
            var newParam = key + "=" + val;
            if (!url.indexOf('?')) {
                url += '?';
            }

            if (url.indexOf('#') > -1) {
                var urlparts = url.split('#');
                url = urlparts[0] + "&" + newParam + (urlparts[1] ? "#" + urlparts[1] : '');
            } else {
                url += "?" + newParam;
            }
        }
        window.history.pushState(null, document.title, url);
    }

    $(document).on('click', '.delete-media', function(e) {
        e.preventDefault();
        let parent = $(this).parent('.media-div');
        let url = $(this).attr('href');
        let msg = $(this).data('msg') ?? "You won't be able to revert back!";
        $.confirm({
            draggable: true,
            title: 'Are You Sure!',
            content: msg,
            type: 'red',
            typeAnimated: true,
            buttons: {
                tryAgain: {
                    text: 'Delete',
                    btnClass: 'btn-red',
                    action: function() {
                        getData("get", url, "json", null, callback = null, event = null, toast = 1);
                        $(parent).remove();
                    }
                },
                close: function() {}
            }
        });
    });

   function getUsers() {
    $('.getUsersList').each(function() {

        let placeholder = $(this).data('placeholder') || 'Select Driver';

        // Detect if this select is inside a modal
        let parentModal = $(this).closest('.modal');
        let dropdownParent = parentModal.length ? parentModal : $('body');

        $(this).select2({
            dropdownParent: dropdownParent,
            placeholder: placeholder,
            language: {
                searching: function() {
                    return "Search For Users";
                }
            },
            ajax: {
                type: 'POST',
                url: "{{ route('panel.admin.users.get-users') }}",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    return {
                        query: params.term,
                        "_token": "{{ csrf_token() }}",
                    };
                },
                processResults: function(response) {
                    return {
                        results: $.map(response, function(item) {
                            return {
                                text: item.name + " | #UID" + item.id + (item.email ? " | " + item.email : ""),
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
    });
}



    function sendAjaxRequest({
        url,
        method = 'post',
        data,
        onSuccess
    }) {
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: onSuccess,
            error: function(error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    $(document).on('click', '.off-canvas', function(e) {
        e.stopPropagation();
        var type = $(this).data('type');
        $('.side-slide').animate({
            right: type == 'close' ? "-100%" : "0px"
        }, 200);
    });
    $(document).on('.close.off-canvas', function() {
        var type = $(this).data('type');
        $('.side-slide').animate({
            right: type == 'close' ? "-100%" : "0px"
        }, 200);
    });
</script>

{!! getSetting('plugin_script') !!}
{{-- inspect block --}}
@if (env('APP_ENV') == 'production')
    <script>
        // Disabled right click and copy
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault(); // Disable right-click context menu
        });
        document.addEventListener('keydown', function(e) {
            // Disable F12 key (Developer Tools shortcut)
            if (e.key === 'F12') {
                e.preventDefault();
            }
        });

        document.addEventListener('keydown', function(e) {
            // Disable copy shortcuts (Ctrl+C, Command+C)
            if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
                e.preventDefault();
            }
        });
    </script>

    {{-- START TIMER WRAPPER --}}
    {{--    <script> --}}
    {{--        // Set the countdown time in seconds --}}
    {{--        @if (getTenderSecondsLeft($item, 'auction_start_date') > 0) --}}
    {{--        var countdown = {{ max(0, getTenderSecondsLeft($item, 'auction_start_date')) }}; --}}
    {{--        @else --}}
    {{--        var countdown = {{ max(0, getTenderSecondsLeft($item, 'auction_end_date')) }}; --}}
    {{--        @endif --}}
    {{--        function updateCountdown{{$item->id}}() { --}}
    {{--            var hours = Math.floor(countdown / 3600); --}}
    {{--            var minutes = Math.floor((countdown % 3600) / 60); --}}
    {{--            var seconds = countdown % 60; --}}

    {{--            // Display the countdown in the specified span --}}
    {{--            $("#countdown").text(pad(hours) + ":" + pad(minutes) + ":" + pad(seconds)); --}}

    {{--            // Update the countdown time --}}
    {{--            countdown--; --}}

    {{--            // Recursive call after 1 second --}}
    {{--            if (countdown >= 0) { --}}
    {{--                setTimeout(function () { --}}
    {{--                    updateCountdown{{$item->id}}(); --}}
    {{--                }, 1000); --}}
    {{--            } else { --}}
    {{--                // Optionally handle expired countdown --}}
    {{--                $("#countdown-{{$item->id}}").text("Expired"); --}}
    {{--            } --}}
    {{--            if (countdown == 0) { --}}
    {{--                setTimeout(function () { --}}
    {{--                    location.reload(); --}}
    {{--                }, 1000); --}}
    {{--            } --}}
    {{--        } --}}

    {{--        // Initial call to start the countdown --}}
    {{--        updateCountdown{{$item->id}}(); --}}
    {{--    </script> --}}
    {{-- END TIMER WRAPPER --}}
@endif
@include('panel.common.script.file-previewer')
