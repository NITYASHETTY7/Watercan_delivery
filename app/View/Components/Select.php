<?php

namespace App\View\Components;

use DASPRiD\Enum\NullValue;
use Illuminate\View\Component;

class Select extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $label;
    public $value;
    public $condition;
    public $payload;
    public $payloadValue;
    public $optionName;
    public $class;
    public $id;
    public $validation;
    public $arr;
    public $multiarr;
    public $valueName;
    public $disabled;
    public $isMultiple;

    public function __construct($name, $value, $label, $condition = 'value', $id = null, $optionName = 'name', $class = null, $arr = [], $multiarr = [], $validation = null, $valueName = null, $payload = null, $payloadValue = null, $isMultiple = 0, $disabled = false)
    {
        $this->name = $name;
        $this->label = $label;
        $this->value = $value;
        $this->condition = $condition;
        $this->payload = $payload;
        $this->payloadValue = $payloadValue;
        $this->optionName = $optionName;
        $this->class = $class;
        $this->id = $id;
        $this->validation = $validation != null ? validation($validation) : null; // <-- Corrected line
        $this->arr = $arr;
        $this->multiarr = $multiarr;
        $this->valueName = $valueName;
        $this->disabled = $disabled;
        $this->isMultiple = $isMultiple;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.select');
    }
}
