<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\ComponentAttributeBag;

class Radio extends Component
{
    public $name;
    public $type;
    public $value;
    public $class;
    public $arr;
    public $label;
    public $id;
    public $validation;
    public $tooltip;

    /** @var ComponentAttributeBag|null */
    public $attributes;

    public function __construct(
        $name,
        $type = 'radio',
        $class = null,
        $arr = [],
        $value = null,
        $label = null,
        $id = null,
        $validation = null,
        $tooltip = null,
        $attributes = null
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->class = $class;
        $this->arr = $arr;
        $this->label = $label;
        $this->id = $id != null ? $id : $name;
        $this->value = $value;
        $this->validation = $validation;
        $this->tooltip = $tooltip;

        // Convert attributes to a ComponentAttributeBag if passed as an array
        $this->attributes = $attributes instanceof ComponentAttributeBag
            ? $attributes
            : new ComponentAttributeBag((array) $attributes);
    }

    public function render()
    {
        return view('components.radio');
    }
}
