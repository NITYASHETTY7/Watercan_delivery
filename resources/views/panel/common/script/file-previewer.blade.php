{{-- File Previewer --}}
<script>
    $(document).on('click', '.preview-doc-btn', function() {
        fileName = $(this).data('name');
        let newWindow = window.open($(this).data('path'), '_blank');
        $(newWindow).on('load', function() {
            @if (auth()->check() && auth()->user())
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        cal_activity_by: " {{ auth()->id() }}",
                        cal_title: '{{ auth()->user()->full_name }} (#{ auth()->id() }) Preview this Document',
                        cal_type: '{{ get_class(auth()->user()) }}',
                        cal_type_id: " {{ auth()->id() }}",
                        cal_description: '{{ auth()->user()->full_name }} Preview this Document (' +
                            fileName + ')',
                        activity: 'Preview this Document'
                    },
                    success: function(response) {
                        setTimeout(function() {
                            $.toast({
                                heading: 'SUCCESS',
                                text: "Activity Log Captured!",
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f2a654',
                                position: 'bottom-center'
                            });
                        }, 1000);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            @endif
        });
        $('#printBtn').click(function() {
            window.print();
        });
    });

    $('#previewModal').on('hidden.bs.modal', function(e) {
        // Perform actions after the modal is completely hidden
        $('#previewPath').attr('src', '');
    });
</script>
{{-- End File Previewer --}}
