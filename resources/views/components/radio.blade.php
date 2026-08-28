@if (!empty($arr) && is_iterable($arr))
    <div class="d-flex flex-wrap gap-3"> {{-- Added a flex container for the whole group --}}
        @foreach ($arr as $arr_item)
            @php
                $itemValue = isset($arr_item['value']) ? (string) $arr_item['value'] : null;
                $itemName = isset($arr_item['name']) ? $arr_item['name'] : null;

                if (is_array($arr_item) && $itemValue !== null && $itemName !== null) {
                } elseif (is_string($arr_item)) {
                    $itemValue = $arr_item;
                    $itemName = ucfirst($arr_item);
                }
            @endphp

            @if ($itemValue !== null && $itemName !== null)
                {{-- FIX: Wrap the input and label in a flex container --}}
                <div class="d-inline-flex align-items-center mr-3 mb-2">
                    <input id="{{ $name . '_' . $itemValue }}" 
                           class="{{ $class ?? '' }} cursor-pointer" 
                           type="radio"
                           name="{{ $name }}" 
                           value="{{ $itemValue }}"
                           @if ((string) old($name) === $itemValue || (string) $value === $itemValue) checked @endif 
                           {{ $attributes }}>
                    
                    {{-- Added a small margin-left to the label for spacing --}}
                    <x-label name="{{ $itemName }}" 
                             class="mb-0 ml-2 cursor-pointer" 
                             validation="{{ $validation ?? '' }}" 
                             tooltip="{{ $tooltip ?? '' }}" />
                </div>
            @endif
        @endforeach
    </div>
@else
    {{-- Single Radio Logic --}}
    <div class="d-inline-flex align-items-center">
        <input id="{{ $id ?? '' }}" class=" {{ $class ?? '' }}" type="radio" name="{{ $name ?? '' }}"
            value="{{ $value ?? 1 }}" @if ((string) old($name) === (string) $value || (string) $value === '1') checked @endif {{ $attributes }}>
        @if ($label)
            <x-label name="{{ $label ?? '' }}" class="mb-0 ml-2" validation="{{ $validation ?? '' }}"
                tooltip="{{ $tooltip ?? '' }}" />
        @endif
    </div>
@endif