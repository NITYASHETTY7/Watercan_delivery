<!-- Include your pptxgen library -->
<script src="{{ asset('site/v1/plugins/pptx/jszip.min.js') }}"></script>
<script src="{{ asset('site/v1/plugins/pptx/pptxgen.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Helper function: ensure an <img> is fully loaded before using naturalWidth/Height
    function ensureImageLoaded(imgEl) {
        return new Promise((resolve) => {
            if (imgEl.complete) {
                resolve();
            } else {
                imgEl.onload = () => resolve();
                imgEl.onerror = () => resolve(); // resolve even if error, fallback later
            }
        });
    }

    async function convertAndAppendNewDiv(divId) {
        var targetDiv = $('#' + divId);
        var route = '{{ route('capture.asset-store') }}';
        var method = 'POST';

        try {
            const canvas = await html2canvas(targetDiv[0]);
            const blob = await new Promise((resolve) => canvas.toBlob(resolve, 'image/png'));

            if (!blob) {
                console.error("Blob creation failed.");
                return;
            }

            var formData = new FormData();
            formData.append('image', blob, 'image.png');
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            const response = await $.ajax({
                contentType: false,
                processData: false,
                type: method,
                url: route,
                dataType: 'json',
                async: false,
                data: formData,
                headers: {
                    "Accept": "application/json"
                }
            });

            // Replace the content with the newly saved image
            targetDiv.empty();
            var newDiv = $('<div style="height:auto;width:75%;object-fit:contain;" class="temp-export-elements">')
                .html('<img src="' + response.path + '" style="width:100%" alt="Div Image">');
            targetDiv.append(newDiv);

        } catch (error) {
            console.error("An error occurred:", error);
        }
    }

    async function convertToPPT(file_name, holders = null) {
        // Show loading UI
        $('#content').addClass('blur-effect');
        $('#content').after(`
            <div style="position: fixed; top: 0; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                <div class="card bg-theme-secondary" style="max-width: 60vh; display: block; z-index: 1;">
                    <div class="card-body">
                        <p class="export_ppt_button mb-0 text-center">Loading...</p>
                    </div>
                </div>
            </div>
        `);
        $('.export_ppt_button').html('<i class="fa fa-spinner fa-spin"></i> Creating Presentation');

        // Convert specified divs to images first
        if (holders !== null) {
            for (let holder of holders) {
                await convertAndAppendNewDiv(holder);
            }
        }

        let pptx = new PptxGenJS();

        //
        // MAIN FUNCTION: Go through #ppt-content, create slides for images or tables
        //
        async function addTablesAndImagesToPresentation() {
            const tables = document.querySelectorAll('#content table');
            console.log(tables);
            for (let tableIndex = 0; tableIndex < tables.length; tableIndex++) {
                let table = tables[tableIndex];
                const images = table.querySelectorAll('img') || [];

                // --------------------------------
                // CASE 1: TABLE HAS IMAGES
                // --------------------------------
                if (images.length > 0) {
                    // SINGLE IMAGE
                    if (images.length === 1) {
                        // Single-image slide dimension
                        const slideWidth = 10;
                        const slideHeight = 5.5;
                        const margin = 0.5;

                        // Create slide
                        const singleSlide = pptx.addSlide();

                        const img = images[0];
                        const imgSrc = img.getAttribute('src');
                        await ensureImageLoaded(img); // Wait for it to fully load

                        // Directly use natural dimension
                        let pxWidth = img.naturalWidth;
                        let pxHeight = img.naturalHeight;

                        // If still no luck, default
                        if (!pxWidth || !pxHeight) {
                            pxWidth = 900;
                            pxHeight = 600;
                        }

                        // Convert to inches
                        let imgWidth = pxWidth / 96;
                        let imgHeight = pxHeight / 96;

                        // "Contain" logic
                        const contentWidth = slideWidth - 2 * margin;
                        const contentHeight = slideHeight - 2 * margin;
                        const imgRatio = imgWidth / imgHeight;
                        const contentRatio = contentWidth / contentHeight;

                        let finalWidth, finalHeight;
                        if (imgRatio > contentRatio) {
                            // wide
                            finalWidth = contentWidth;
                            finalHeight = finalWidth / imgRatio;
                        } else {
                            // tall
                            finalHeight = contentHeight;
                            finalWidth = finalHeight * imgRatio;
                        }

                        // clamp if needed
                        if (!finalWidth || finalWidth < 0) finalWidth = 0.5;
                        if (!finalHeight || finalHeight < 0) finalHeight = 0.5;

                        // Center the image
                        const finalX = margin + (contentWidth - finalWidth) / 2;
                        const finalY = margin + (contentHeight - finalHeight) / 2;

                        // DEBUG
                        console.log(
                            `[DEBUG: Single Image Table #${tableIndex}]`, {
                                imgSrc,
                                natural: {
                                    w: img.naturalWidth,
                                    h: img.naturalHeight
                                },
                                finalInches: {
                                    w: finalWidth,
                                    h: finalHeight
                                },
                                finalPosition: {
                                    x: finalX,
                                    y: finalY
                                }
                            }
                        );

                        // Add image
                        singleSlide.addImage({
                            path: imgSrc,
                            x: finalX,
                            y: finalY,
                            w: finalWidth,
                            h: finalHeight
                        });

                        // TWO IMAGES
                    } else if (images.length === 2) {
                        // Two-image/table slides: 10 x 7.5
                        const slideWidth = 10;
                        const slideHeight = 7.5;
                        const margin = 0.5;

                        const doubleSlide = pptx.addSlide();

                        for (let i = 0; i < images.length; i++) {
                            let img = images[i];
                            let imgSrc = img.getAttribute('src');

                            await ensureImageLoaded(img); // Wait for load

                            let pxWidth = img.naturalWidth;
                            let pxHeight = img.naturalHeight;

                            if (!pxWidth || !pxHeight) {
                                pxWidth = 900;
                                pxHeight = 600;
                            }

                            let imgWidth = pxWidth / 96;
                            let imgHeight = pxHeight / 96;

                            const aspectRatio = imgWidth / imgHeight;
                            const contentWidth = (slideWidth / 2) - margin;
                            const contentHeight = slideHeight - 2 * margin;
                            const contentRatio = contentWidth / contentHeight;

                            let finalWidth, finalHeight;
                            if (aspectRatio > contentRatio) {
                                // wide
                                finalWidth = contentWidth;
                                finalHeight = finalWidth / aspectRatio;
                            } else {
                                // tall
                                finalHeight = contentHeight;
                                finalWidth = finalHeight * aspectRatio;
                            }

                            // clamp
                            if (!finalWidth || finalWidth < 0) finalWidth = 0.5;
                            if (!finalHeight || finalHeight < 0) finalHeight = 0.5;

                            const finalX = margin + i * (slideWidth / 2);
                            const finalY = (slideHeight - finalHeight) / 2;

                            console.log(
                                `[DEBUG: Two Images Table #${tableIndex} - Image ${i}]`, {
                                    imgSrc,
                                    natural: {
                                        w: img.naturalWidth,
                                        h: img.naturalHeight
                                    },
                                    finalInches: {
                                        w: finalWidth,
                                        h: finalHeight
                                    },
                                    finalPosition: {
                                        x: finalX,
                                        y: finalY
                                    }
                                }
                            );

                            doubleSlide.addImage({
                                path: imgSrc,
                                x: finalX,
                                y: finalY,
                                w: finalWidth,
                                h: finalHeight
                            });
                        }
                    }

                    // --------------------------------
                    // CASE 2: TABLE WITHOUT IMAGES
                    // --------------------------------
                } else {
                    const slideWidth = 10;
                    const slideHeight = 7.5;
                    const margin = 0.5;

                    const maxContentHeight = (slideHeight - 2 * margin) * 96;

                    const rows = Array.from(table.querySelectorAll('tr'));
                    let currentTablePart = [];
                    let currentHeight = 0;

                    const addTableToNewSlide = (tableData) => {
                        let slide = pptx.addSlide();
                        slide.addTable(tableData, {
                            x: margin,
                            y: margin,
                            w: slideWidth - 2 * margin,
                            border: {
                                pt: '0',
                                color: '000000'
                            },
                            fill: 'FFFFFF',
                            fontSize: 8,
                        });
                    };

                    const calculateRowHeight = (row) => {
                        return row.getBoundingClientRect().height;
                    };

                    for (let rowIndex = 0; rowIndex < rows.length; rowIndex++) {
                        let row = rows[rowIndex];
                        const rowHeight = calculateRowHeight(row);
                        let rowData = [];
                        const cells = row.querySelectorAll('th, td');

                        cells.forEach((cell) => {
                            let cellData = {
                                text: cell.innerText,
                                options: {}
                            };
                            const colspan = cell.getAttribute('colspan');
                            if (colspan) {
                                cellData.options.colspan = parseInt(colspan);
                            }
                            rowData.push(cellData);
                        });

                        // If adding this row would exceed
                        if ((currentHeight + rowHeight) > maxContentHeight) {
                            addTableToNewSlide(currentTablePart);
                            currentTablePart = [];
                            currentHeight = 0;
                        }

                        currentTablePart.push(rowData);
                        currentHeight += rowHeight;
                    }

                    if (currentTablePart.length > 0) {
                        addTableToNewSlide(currentTablePart);
                    }
                }
            }
        }

        try {
            // Build slides (using async/await so images can load)
            $('.export_ppt_button').html('<i class="fa fa-spinner fa-spin"></i> Formatting Content');
            await addTablesAndImagesToPresentation();

            // Export to PPT
            await pptx.writeFile({
                fileName: file_name + '.pptx'
            });
            $('.export_ppt_button').html('<i class="fa fa-check"></i> Download Complete');

            setTimeout(() => {
                // window.history.back();
                window.location.reload();
            }, 2000);
        } catch (error) {
            console.error("Error creating PPT:", error);
            $('.export_ppt_button').html('Failed to export ppt');
        }
    }

    // Optional: On button click, show "fetching" message
    $(document).on('click', '.presentation-view', function() {
        $('#content').html(`
            <div class="container-fluid">
              <div class="p-5 m-5">
                <div class="card-body text-center p-5 m-5">
                  <span class="mr-1">
                    <i class="fa fa-circle fa-bounce fa-sm" style="color:#74b634;"></i>
                  </span> 
                  Fetching...
                </div>
              </div>
            </div>
        `);
    });

    // Auto-trigger if ?view_type=ppt
    $(window).on('load', function() {
        const viewType = "{{ request()->get('view_type') }}";
        if (viewType && viewType === 'ppt') {
            setTimeout(() => {
                $('.export-doc').trigger('click');
            }, 1000);
        }
    });
</script>
