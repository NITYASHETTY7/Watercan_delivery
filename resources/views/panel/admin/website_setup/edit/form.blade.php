
<form class="ajaxForm" action="{{ route('panel.admin.website-pages.update', secureToken($websitePage->id)) }}"
    method="POST" enctype="multipart/form-data">
    @csrf
    <x-input name="request_with" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="update" />
    <x-input name="id" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty"
        value="{{ $websitePage->id }}" />
    <div class="row">
        <div class="col-md-7">
            <div class="card mb-bottom">
                <div class="card-header">
                    <h6 class="fw-600 mb-0"> @lang('ui.page_content') </h6>
                </div>
                <div class="card-body px-0 negative-margin">
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="title" validation="common_title" tooltip="edit_website_page_title" />
                            <x-input name="title" placeholder="{{ __('ui.enter') . ' ' . __('ui.title') }}"
                                type="text" tooltip="edit_website_page_title" regex="text"
                                validation="common_title" value="{{ @$websitePage->title }}" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group {{ @$errors->has('slug') ? 'has-error' : '' }}">
                            <x-label name="slug" validation="required" tooltip="add_website_slug" />
                            <x-input class="form-control w-100 w-md-auto" name="slug" type="text"
                                pattern="[a-zA-Z]+.*"
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                title="Please enter first letter alphabet and at least one alphabet character is required."
                                id="title" value="{{ @$websitePage->slug }}"
                                placeholder="{{ __('ui.enter') . ' ' . __('ui.slug') }}" validation="empty" />
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <x-label name="page_content" validation="required" tooltip="add_website_content" />
                            <div id="content-holder">
                                <div class="init-ck-editor"
                                    data-upload_url="{{ route('panel.admin.media.ckeditor.upload') . '?_token=' . csrf_token() }}">
                                    {!! @$websitePage->content ?? '--' !!}
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="card mb-bottom">
                <div class="card-header">
                    <h6 class="fw-600 mb-0"> @lang('ui.seo_field') </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between" style="margin-bottom: -15px">
                                <div class="form-group">
                                    <x-label name="meta_title" for="name" validation="common_meta_title" tooltip="edit_website_page_meta_title" />
                                </div>
                                <div>
                                    <x-button id="auto_fill_title"
                                        class="p-0 btn btn-link btn-sm text-primary float-end fw-800"><i
                                            class="ik ik-corner-left-down"></i> @lang('ui.auto_fill')
                                    </x-button>
                                </div>
                            </div>
                            <div class="form-group">
                                <x-input name="page_meta_title" id="page_meta_title"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.title') }}" type="text"
                                    tooltip="edit_website_page_meta_title" regex="text" validation="common_meta_title"
                                    value="{{ @$websitePage->meta['title'] }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="meta_keywords" validation="common_meta_keywords" tooltip="edit_website_page_keywords" />
                                <x-input name="page_keywords" id="tags"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.meta_keywords') }}" type="text"
                                    tooltip="edit_website_page_keywords" regex="text"
                                    validation="common_meta_keywords" value="{{ @$websitePage->meta['keywords'] }}" />
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <x-label name="meta_description" validation="common_meta_description" tooltip="edit_website_page_meta_des" />
                                <x-textarea regex="text" validation="common_meta_description"
                                    value="{{ @$websitePage->meta['description'] }}" name="page_meta_description"
                                    id="page_meta_description"
                                    placeholder="{{ __('ui.enter') . ' ' . __('ui.meta_description') }}" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- <div class="card mb-bottom">
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
                                <x-checkbox name="status" class="js-switch switch-input"
                                    value="{{ $websitePage->status }}" type="checkbox" tooltip=""
                                    :arr="@$checkbox_arr" validation="empty" />
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <x-button type="submit" class="btn btn-primary floating-btn ajax-btn"> @lang('ui.save_update') </x-button>
</form>
@push('script')
    {{-- START AUTOFILE TITLE JS INIT --}}
    <script>
        $('#auto_fill_title').on('click', function(e) {
            e.preventDefault();
            var title_name = $('#title').val();
            $('#page_meta_title').val(title_name);
        })
    </script>
    {{-- END AUTOFILE TITLE JS INIT --}}

    {{-- START DECOUPLEDEDITOR INIT --}}
    <script src="https://cdn.ckeditor.com/ckeditor5/37  .1.0/decoupled-document/ckeditor.js"></script>
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

    {{-- START TAGINPUT INIT --}}
    <script>
        $('#tags').tagsinput('items');
    </script>
    {{-- END TAGINPUT INIT --}}

    {{-- START AJAX FORM INIT --}}
    <script>
        $('.ajaxForm').on('submit', function(e) {
            e.preventDefault();
            var route = $(this).attr('action');
            var method = $(this).attr('method');
            var data = new FormData(this);
            ckEditors.forEach((editor, index) => {
                if (index == 0) {
                    data.append('content', editor.getData());
                } else {
                    data.append(`editor_content_${index}`, editor.getData());
                }
            });
            var redirectUrl = "{{ url('admin/website-pages') }}";
            var response = postData(method, route, 'json', data, null, null, 1, null, redirectUrl);
        });
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
        $('#website_pages').DataTable({
            responsive: true
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
