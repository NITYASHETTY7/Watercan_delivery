<script src="{{ asset('site/v1/plugins/fileSaver/fileSaver.js') }}"></script>
<script src="{{ asset('site/v1/plugins/wordExport/wordExport.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function convertAndAppendWordNewDiv(divId) {
        return new Promise((resolve, reject) => {
            var targetDiv = $('#' + divId);
            if (targetDiv.length === 0) {
                console.warn('Div with ID ' + divId + ' not found. Skipping.');
                resolve(); // Skip this div and move to the next
                return;
            }
            var route = '{{ route('capture.asset-store') }}';
            var method = 'POST';

            // Use html2canvas to capture the content of the div
            html2canvas(targetDiv[0], {
                scale: 2
            }).then(function(canvas) {
                // Convert the canvas to a Blob object (PNG format)
                canvas.toBlob(function(blob) {
                    // Create a new div containing the image tag with the Blob URL
                    var imageUrl = URL.createObjectURL(blob);
                    var formData = new FormData();
                    formData.append('image', blob, 'image.png');
                    formData.append('type', 'resize');
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

                    $.ajax({
                        contentType: false,
                        processData: false,
                        type: method,
                        url: route,
                        dataType: 'json',
                        async: true, // Ensure this is asynchronous
                        data: formData != null ? formData : {},
                        headers: {
                            "Accept": "application/json"
                        },
                        success: function(response) {
                            targetDiv.empty();
                            // Append the new div after the existing div
                            var newDiv = $(
                                    '<div style="height:auto;width:100%;object-fit:contain;" class="temp-export-elements">'
                                )
                                .html('<img src="' + response.path +
                                    '" alt="Div Image" style="max-width: 100%; height:auto;">'
                                );
                            targetDiv.append(newDiv);
                            resolve(); // Resolve the promise when done
                        },
                        error: function(response) {
                            console.log('error');
                            reject(response); // Reject the promise on error
                        }
                    });
                }, 'image/png');
            }).catch(error => {
                console.error('html2canvas error:', error);
                reject(error); // Handle and reject any html2canvas errors
            });
        });
    }

    async function ExportWord(element, fileName, holders = null) {
        $('.container').addClass('blur-effect');
        $('.container').after(`
            <div class="loading-div" style="position: fixed; top: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                <div class="card bg-theme-secondary" style="max-width: 60vh; display: block; z-index: 1;">
                    <div class="card-body">
                        <p class="export-document mb-0 text-center"><i class="fa fa-spinner fa-spin"></i> Creating Document...</p>
                    </div>
                </div>
            </div>
        `);
        $('.container').addClass('word-report-width');

        if (holders !== null) {
            try {
                await processHolders(holders);
            } catch (error) {
                console.error('Error processing holders:', error);
                $('.export-document').html('Failed to export document');
                return;
            }
        }

        $('.export-document').html('<i class="fa fa-spinner fa-spin"></i> Formatting Content');
        $('.print-doc').hide();

        try {
            // Ensure loading-div is hidden just before exporting the document
            $('.export-document').html('<i class="fa fa-check"></i> Download Complete');

            setTimeout(() => {
                $('.loading-div').remove(); // Remove the loading div

                // Export the document
                $("#" + element).wordExport(fileName);
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 1000);

            // Reload the page after the export is done

        } catch (error) {
            console.error('Error during export:', error);
            $('.export-document').html('Failed to export document');
        }
    }

    // Helper function to process each holder
    async function processHolders(holders) {
        for (const holder of holders) {
            await convertAndAppendWordNewDiv(holder);
        }
    }


    // Helper function to process each holder
    async function processHolders(holders) {
        for (const holder of holders) {
            await convertAndAppendWordNewDiv(holder);
        }
    }
</script>
