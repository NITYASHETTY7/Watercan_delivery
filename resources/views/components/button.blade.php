@if (isset($href))
    <a href="{{ @$href ?? '' }}" id="{{ @$id }}" class="{{ $attributes->get('class') }}">
        {{ @$slot ?? '' }}
    </a>
@else
    <button type="{{ $type ?? 'button' }}" id="{{ @$id }}" class="{{ @$class }}" {{ $attributes->merge([]) }}>
        {{ @$slot ?? '' }}
    </button>
@endif
