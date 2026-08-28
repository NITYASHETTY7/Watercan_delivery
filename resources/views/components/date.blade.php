<input class="form-control" @isset($regex['pattern']) pattern="{{ @$regex['pattern'] ?? '' }}" @endisset
    @isset($regex['message']) title="{{ @$regex['message'] ?? '' }}" @endisset
    @isset($validation['pattern']['mandatory']) {{ $validation['pattern']['mandatory'] }} @endisset type="{{ @$type ?? '' }}"
    id="{{ @$id ?? '' }}" max="{{ @$max ?? '' }}" name="{{ @$name ?? '' }}" value="{{ @$value ?? '' }}"
    @isset($placeholder) placeholder="{{ @$placeholder ?? '' }}" @endisset
    @isset($readonly) readonly @endisset>
