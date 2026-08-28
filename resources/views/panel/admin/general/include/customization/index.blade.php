<div class="">
    <form action="{{ route('panel.admin.setting.store') }}" method="POST" enctype="multipart/form-data" class="ajaxForm">
        @csrf
        <x-input name="active" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="security" />
        <x-input name="group_name" placeholder="Enter Name" type="hidden" tooltip="" regex="" validation="empty" value="appearance_custom_style" />

        <div class="form-group row">
            <x-label class="col-md-3 col-from-label" name="header_custom" validation="empty" />
            <div class="col-md-9">
                <x-textarea name="custom_header_style" rows="4" class="form-control"
                    placeholder="<style> ... </style>" :value="getSetting('custom_header_style', $master_setting ?? null)" />
                <small class="text-color-white"> @lang('ui.write_style') </small>
            </div>
        </div>
        <div class="form-group row">
            <x-label class="col-md-3 col-from-label" name="header_custom_script" validation="empty" />
            <div class="col-md-9">
                <x-textarea name="custom_header_script" rows="4" class="form-control"
                    placeholder="<script>
                        ...
                    </script>" :value="getSetting('custom_header_script', $master_setting ?? null)" />
                <small class="text-color-white"> @lang('ui.write_script') </small>
            </div>
        </div>
        <div class="form-group row">
            <x-label class="col-md-3 col-from-label" name="footer_custom" validation="empty" />
            <div class="col-md-9">
                <x-textarea name="custom_footer_script" rows="4" class="form-control"
                    placeholder="<script>
                        ...
                    </script>" :value="getSetting('custom_footer_script', $master_setting ?? null)" />
                <small class="text-color-white"> @lang('ui.script_tag') </small>
            </div>
        </div>
        <div class="card-footer text-right px-0">
            <x-button type="submit" class="btn btn-primary ajax-btn"> @lang('ui.save_update') </x-button>
        </div>
    </form>
</div>
