<form action="{{ route('panel.admin.website-pages.store') }}" method="POST" enctype="multipart/form-data" class="ajaxForm">
    @csrf

    <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="create" />
    <div class="row">
        <div class="col-md-7">
            <div class="">
                <div class="card mb-bottom">
                    <div class="col-md-12 mt-4 border-bottom">
                        <div class="d-flex justify-content-between" style="margin-top: -10px">
                            <div class="form-group">
                                <h6 class="fw-600 mb-0"> @lang('ui.page_content') </h6>
                            </div>
                            {{-- @if (env('IS_DEV') == 1)
                                <div>
                                    <x-button id="legal"
                                        class="p-0 btn btn-link btn-sm text-primary float-end fw-800">
                                        <i class="fa-solid fa-print"></i> @lang('ui.generator')
                                    </x-button>
                                </div>
                            @endif --}}
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="title" validation="common_title" tooltip="add_website_page_title" />
                                    <x-input name="title" placeholder="{{ __('ui.enter') . ' ' . __('ui.title') }}" type="text" tooltip="add_website_page_title" regex="text" validation="common_title" value="{{ old('title') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group {{ @$errors->has('slug') ? 'has-error' : '' }}">
                                    <x-label name="slug" validation="required" tooltip="add_website_slug" />
                                    <div class="input-group d-block d-md-flex">
                                        <x-input name="slug" id="slugInput" oninput="slugFunction()" placeholder="{{ 'Slug' }}" type="hidden" tooltip="add_website_page_title" regex="text" validation="common_title" value="{{ old('title') }}" />
                                        <div class="input-group-prepend"><span class="input-group-text flex-grow-1"
                                                style="overflow: auto" id="slugOutput">{{ url('page/') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="page_content" validation="required" tooltip="add_website_content" />
                                    <div id="content-holder">
                                        <div id="toolbar-container"></div>
                                        <div id="txt_area">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-bottom">
                    <div class="card-header">
                        <h6 class="fw-600 mb-0"> @lang('ui.seo_field') </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12" style="margin-bottom: -15px">
                                <div class="d-flex justify-content-between">
                                    <div class="form-group">
                                        <x-label name="meta_title" for="page_meta_title" validation="common_meta_title" tooltip="add_seo_tags_title" />
                                    </div>
                                    <div>
                                        <x-button id="auto_fill_title"
                                            class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                                class="ik ik-corner-left-down"></i> @lang('ui.auto_fill')
                                        </x-button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <x-input name="page_meta_title" id="meta_titile" placeholder="Enter Title" type="text" tooltip="add_website_meta_title" regex="text" validation="common_meta_title" value="{{ old('page_meta_title') }}" />
                            </div>
                            <div class="col-md-12 mt-2">
                                <div class="form-group">
                                    <x-label name="meta_keywords" validation="common_meta_keywords" tooltip="add_website_keywords" />
                                    <x-input name="page_keywords" id="tags" placeholder="{{ __('ui.enter') . ' ' . __('ui.meta_keywords') }}" type="text" tooltip="add_website_keywords" regex="text" validation="common_meta_keywords" value="{{ old('page_keywords') }}" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <x-label name="meta_description" validation="common_meta_description" tooltip="add_website_meta_description" />
                                    <x-textarea regex="text" validation="common_meta_description" value="{{ old('page_meta_description') }}" name="page_meta_description" id="page_meta_description" placeholder="{{ __('ui.enter') . ' ' . __('ui.meta_description') }} " />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card mb-bottom">
                <div class="card-header d-flex justify-content-between">
                    <h6 class="fw-600 mb-0"> @lang('ui.visibility') </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @php
                                    $checkbox_arr = ['is_published'];
                                @endphp
                                <x-checkbox name="status" class="js-switch switch-input" value="1" type="checkbox" tooltip="" :arr="@$checkbox_arr" validation="empty" />
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('ui.create') </x-button>
</form>
@push('script')
    {{-- START AUTO FILL LEGAL DATA WITH BTN --}}
    <script>
        $('#auto_fill_title').on('click', function(e) {
            e.preventDefault();
            var title_name = $('#title').val();
            $('#meta_titile').val(title_name);
        })

        $('#legal').on('click', function(e) {
            e.preventDefault();
            $('#legalModal').modal('show');
        });
    </script>
    {{-- END AUTO FILL LEGAL DATA WITH BTN --}}

    {{-- START TAGINPUT INIT --}}
    <script>
        $('#tags').tagsinput('items');
    </script>
    {{-- END TAGINPUT INIT --}}

    {{-- START DECOUPLEDEDITOR INIT --}}
    <script src="{{ asset($master_root_directory . 'plugins/ckeditor5/ckeditor.js') }}"></script>
    {{-- START DECOUPLEDEDITOR INIT --}}
    <script>
        let editor;
        $(window).on('load', function() {
            $('#txt_area').addClass('ck-editor');
            DecoupledEditor
                .create(document.querySelector('.ck-editor'), {
                    ckfinder: {
                        uploadUrl: "{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}",
                    }
                })
                .then(newEditor => {
                    editor = newEditor;
                    const toolbarContainer = document.querySelector('#toolbar-container');

                    toolbarContainer.appendChild(editor.ui.view.toolbar.element);
                })
                .catch(error => {
                    console.error(error);
                });

        });
    </script>
    {{-- END DECOUPLEDEDITOR INIT --}}
    <script>
        $('.documentGenerateForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            var response = postData(method, route, 'json', data, null, null, 1, null, 'not-reload');
            console.log(response);
            if (response.status == 'success') {
                var replacedContent = response.content;
                editor.setData(replacedContent);
                $('.close').click();
            }
        });
    </script>
    {{-- END DECOUPLEDEDITOR INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script src="{{ asset($master_root_directory . 'plugins/form/ajaxForm.js') }}"></script>
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();

            // Ensure that the CKEditor content is captured correctly
            let descriptionContent = editor.getData(); // Assuming 'editor' is your CKEditor instance
            if (!descriptionContent.trim()) {
                alert("Content is required.");
                return; // Stop form submission if description is empty
            }

            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            data.append('content', descriptionContent); // Append description content

            var redirectUrl = "{{ url('/admin/website-pages/') }}";
            postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
    </script>
    </script>
    {{-- END AJAX FORM INIT --}}

    {{-- START JS HELPERS INIT --}}
    <script>
        function slugFunction() {
            var x = document.getElementById("slugInput").value;
            document.getElementById("slugOutput").innerHTML = "{{ url('/page/') }}/" + x;
        }

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/ /g, '-')
                .replace(/[^\w-]+/g, '');
        }

        $('#title').on('keyup', function() {
            $('#slugInput').val(convertToSlug($('#title').val()));
            slugFunction();
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
