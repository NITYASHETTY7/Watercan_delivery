<style>
    #previewPath {
        overflow: auto;
        max-width: -webkit-fill-available;
        max-height: -webkit-fill-available;
    }

    #previewPath {
        -ms-overflow-style: none;
    }

    .hoc {
        max-width: 100%;
    }
</style>

<div class="modal fade p-0" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="documentModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preview-modal-label">File Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0" id="preview-modal-body"style=" padding: 1px ;">
                <iframe src="" id="previewPath" style="object-fit:contain;width:100%;height:80vh;"></iframe>

            </div>

        </div>
    </div>
</div>
