<select {{ $isMultiple == 1 ? 'multiple' : '' }} id="{{ @$id ?? '' }}" class="form-control {{ @$class }}"
    name="{{ @$name }}" @if (isset($validation['pattern']['mandatory']) && $validation['pattern']['mandatory'] == 'required') required @endif
    @if ($disabled) disabled @endif>
    <option value="" readonly>Select {{ @$label ?? '' }}</option>
    @foreach ($arr as $key => $arr_val)
        @php
            $optionValue = $valueName && $valueName == 'optionValue'
                ? $arr_val
                : ($valueName != null
                    ? $arr_val[$valueName]
                    : $key);
            $payloadAttribute = isset($payload)
                ? (isset($arr_val[$payloadValue])
                    ? $arr_val[$payloadValue]
                    : $payloadValue)
                : '';
            $isSelected = is_array($value) ? in_array($optionValue, $value) : $value == $optionValue;
        @endphp
        <option value="{{ $optionValue }}"
            @if (isset($payload)) {{ $payload }}="{{ $payloadAttribute }}" @endif
            @if ($isSelected) selected @endif>
            {{ empty($optionName) && !is_array($arr_val) ? $arr_val : $arr_val[$optionName] ?? '' }}
            @if ($multiarr != null)
                |
                @foreach ($multiarr as $option)
                    {{ $arr_val[$option] }}
                    @if (!$loop->last)
                        |
                    @endif
                @endforeach
            @endif
        </option>
    @endforeach
</select>
