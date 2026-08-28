<!-- JAVASCRIPT -->
<script src="{{ asset($root_directory . 'plugins/jquery-3.6.0/jquery-3.6.0.js') }}"></script>

<!-- Main Js -->
<script src="{{ asset($root_directory . 'plugins/base/plugins.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/base/theme.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/dist/jquery.toast.min.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/select2/dist/js/select2.min.js') }}"></script>
{{-- <script src="{{ asset($root_directory . 'plugins/form/ajaxForm.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/form/index-page.js') }}"></script> --}}

{{-- JQUERY CONFIRM CDN --}}
<script src="{{ asset($root_directory . 'plugins/jquery-confirm-3.3.2/jquery-confirm.min.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/jquery.lazy.min/jquery.lazy.min.js') }}"></script>

{{-- COUNTRYCODE SELECTOR INIT --}}
<script src="{{ asset($root_directory . 'plugins/country-code/intl-tel-input.js') }}"></script>
<script src="{{ asset($root_directory . 'plugins/country-code/utils.js') }}"></script>
{{-- End COUNTRYCODE SELECTOR INIT --}}

{{-- Font Awesome CDN --}}
<script src="{{ asset($root_directory . 'plugins/fontawesome-6.5.1/all.min.js') }}"></script>

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
<script>
    $('select.select2').select2();
    $(document).on('click', '.delete-item', function(e) {
        e.preventDefault();
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
                        window.location.href = url;
                    }
                },
                close: function() {}
            }
        });
    });

    $('.uil-times').hide();
    let mobnav = 0;
    $('.toggleBtn').on('click', function() {
        $('.toggle-area').toggle(200);
    });
    $('#toggle-submenu').on('click', function() {
        $('#show-submenu').toggle(200);
    });
</script>

@if (getSetting('custom_header_script') != 0)
    <script src="{!! getSetting('custom_header_script') !!}"></script>
@endif
@if (getSetting('custom_footer_script') != 0)
    <script src="{!! getSetting('custom_footer_script') !!}"></script>
@endif

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
@endif

{{-- Newsletter Form Submission --}}
<script>
    $(document).ready(function() {
        $(document).on('submit', '.newsletter-form', function(event) {
            event.preventDefault(); // Prevent the default form submission

            var form = $(this);
            var formData = form.serialize(); // Serialize form data

            $.ajax({
                url: form.attr('action'), // Use the form's action attribute
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('.response-message').removeClass('alert alert-danger').addClass(
                        'alert alert-success').text(response.message).show();
                    form[0].reset(); // Optionally reset the form
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON.message ||
                        'An error occurred. Please try again.';
                    $('.response-message').removeClass('alert alert-success').addClass(
                        'alert alert-danger').text(errorMessage).show();
                }
            });
        });
    });
</script>
{{-- End Newsletter Form Submission --}}

{{-- OneSignalSDK --}}
<script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
<script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(async function(OneSignal) {
        await OneSignal.init({
            appId: "02949a6b-ad2f-4568-9c05-f622574b9831",
        });
    });
</script>

@include('panel.common.script.file-previewer')
