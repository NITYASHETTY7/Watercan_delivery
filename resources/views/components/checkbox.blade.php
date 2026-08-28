@if (count($arr) > 0)
    @foreach ($arr as $arr_item)
        <input id="{{ @$arr_item ?? '' }}" class="py-2 {{ @$class }}" type="{{ @$type ?? 'checkbox' }}"
            name="{{ @$name ?? '' }}" value="{{ is_array($value) ? in_array($arr_item, $value) : 1 }}"
            @if (is_array($value) ? in_array($arr_item, $value) : $value == 1) checked @endif
            @if (isset($attributes) && is_array($attributes)) @foreach ($attributes as $attr_key => $attribute) data-{{ $attr_key }}="{{ $attribute }}" @endforeach @endif>
        <x-label name="{{ @$arr_item ?? '' }}" validation="{{ @$validation }}"
            tooltip="{{ $tooltip !== null ? $tooltip : '' }}" />
    @endforeach
@else
    <input id="{{ @$name }}" class="py-2 {{ @$class }}" type="{{ @$type ?? 'checkbox' }}"
        name="{{ @$name }}" onclick="{{ $onclick }}" value="{{ $value != 0 ? $value : 1 }}"
        @if (!str_contains($class, 'delete_Checkbox')) @if (@$value == 1) checked @endif @endif
    @if (isset($attributes) && is_array($attributes)) @foreach ($attributes as $attr_key => $attribute) data-{{ $attr_key }}="{{ $attribute }}" @endforeach @endif
    >
@endif
