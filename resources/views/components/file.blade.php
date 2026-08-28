@if ($version == 'drag')
    <div class="{{ $class }} input-images" alt="Add {{ $name }}" data-input-name="{{ $name }}"
        data-label="Drag & Drop {{ formatDisplayName($name) }} here or click to browse"
        data-input-accept="{{ $validation && isset($validation['dimension']) ? $validation['dimension'] : '' }}">
    </div>
    <small class="text-danger fw-700">
        @if ($validation && isset($validation['pattern']))
            @lang('ui.file_allowed')
            {{ str_replace('image/', '', $validation['pattern']['allowed_extensions'] ?? '') }}
        @endif
        <br>
        @lang('ui.file_dimensions')
        {{ $validation['dimension'] ?? '' }}
    </small>
@elseif ($version == 'choose')
    <div class="form-group mt-1">
        <input type="file" name="{{ $name }}" class="form-control file-uploader" id="{{ $id ?? $name }}"
            @if ($accept) accept="{{ $accept }}" @endif
            @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') {{ $validation['pattern']['mandatory'] }} @endif style="padding:5px;">
        @if (isset($validation['pattern']['allowed_extensions']))
            <small class="text-danger text-left">
                @lang('ui.file_allowed')
                {{ str_replace('image/', '', $validation['pattern']['allowed_extensions'] ?? '') }}
            </small>
        @endif
    </div>
@elseif ($version == 'image')
    <div class="form-group mt-1">
        <input type="file" name="{{ $name }}" class="form-control file-uploader" id="{{ $id ?? $name }}"
            @if ($accept) accept="{{ $accept }}" @endif style="padding:5px;"
            @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') {{ $validation['pattern']['mandatory'] }} @endif>
        <small class="text-danger text-left">
            @lang('ui.file_allowed')
            .jpg, .jpeg, .png
        </small>
    </div>
@else
    <div class="form-group mt-1">
        <input type="file" name="{{ $name }}" class="form-control file-uploader" id="{{ $id ?? $name }}"
            @if ($accept) accept="{{ $accept }}" @endif style="padding:5px;"
            @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') {{ $validation['pattern']['mandatory'] }} @endif>
        <small class="text-danger text-left">
            {{-- @lang('ui.file_allowed') --}}
        </small>
    </div>
@endif
