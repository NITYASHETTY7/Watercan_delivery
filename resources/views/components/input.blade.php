<div class="@if ($type != 'hidden') input-group mb-0 @endif @if (isset($hint) && $hint != null) mb-0 @endif">
    @if (isset($icon) && explode(':', $icon)[0] === 'pre')
        <div class="input-group-prepend">
            <div class="input-group-text">{!! @explode(':', $icon)[1] !!}</div>
        </div>
    @endif

    <input id="{{ $id == null ? $name : @$id }}" type="{{ $type ?? 'text' }}"
        @isset($step) step="{{ @$step ?? '' }}" @endisset
        class="form-control @error($name) is-invalid @enderror {{ @$class }}" name="{{ @$name ?? '' }}"
        value="{{ @$value ?? '' }}" placeholder="{{ @$placeholder ?? '' }}"
        @isset($regex['pattern']) pattern="{{ @$regex['pattern'] }}" @endisset
        title="{{ isset($regex['message']) ? $regex['message'] : '' }}"
        @isset($validation['pattern']['minlength']) minlength="{{ $validation['pattern']['minlength'] }}" @endisset
        @isset($validation['pattern']['maxlength']) maxlength="{{ $validation['pattern']['maxlength'] }}" @endisset
        @isset($validation['pattern']['min']) min="{{ $validation['pattern']['min'] }}" @endisset
        @isset($validation['pattern']['max']) max="{{ $validation['pattern']['max'] }}" @endisset
        @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') {{ $validation['pattern']['mandatory'] }} @endif
        
        @isset($validation['pattern']['only_digits'])
            oninput="this.value = this.value.replace(/[^0-9]/g,'')"
        @endisset

        @isset($payload) {{ @$payload ?? '' }} @endisset
        @isset($attributes) {{ @$attributes ?? '' }} @endisset
        @if ($readonly != null) readonly @endisset
           @isset($disabled) disabled @endisset
           @if ($oninput !== null) oninput="{{ @$oninput ?? '' }}" @endif>

    @if (isset($icon) && explode(':', $icon)[0] === 'post')
        <div class="input-group-prepend">
            <div class="input-group-text">{!! @explode(':', $icon)[1] !!}</div>
        </div>
    @endif
</div>

@if (isset($hint) && $hint != null)
    <div class="text-danger mb-2">
        <small>
            {{ @$hint ?? '' }}
        </small>
    </div>
@endif
