<form class="forms-sample ajaxForm" action="{{ route('panel.admin.setting.store') }}" method="post"
    enctype="multipart/form-data">
    @csrf
    <x-input type="hidden" name="group_name" value="{{ 'general_setting_date_time' }}" validation="empty" />
    <div class="form-group d-flex">
        <x-label for="" class="col-sm-3" name="date_format" validation="empty" tooltip="date_format" />
        <div class="row">
            @foreach (\App\Models\Setting::DATE_FORMATS as $dt_formats)
                <div class="col-sm-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="date_format" id="{{ @$loop->iteration }}"
                            {{ isset($dt_formats['format']) && $dt_formats['format'] == getSetting('date_format', @$setting) ? 'checked' : '' }}
                            value="{{ $dt_formats['format'] ?? '' }}">
                        <label class="form-check-label" for="{{ @$loop->iteration }}">
                            {{ isset($dt_formats['label']) ? $dt_formats['label'] : '' }}
                            ({{ $dt_formats['format'] ?? '' }})
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card-footer text-right">
        <x-button type="submit" class="btn btn-primary mr-2 ajax-btn"> @lang('ui.save_update') </x-button>
    </div>
</form>
