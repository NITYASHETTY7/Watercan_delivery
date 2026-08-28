<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Input extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $placeholder;
    public $type;
    public $step;
    public $tooltip;
    public $payload;
    public $attributes;
    public $regex;
    public $validation;
    public $class;
    public $id;
    public $value;
    public $icon;
    public $readonly;
    public $disabled;
    public $hint;
    public $oninput;

    public function __construct($name,$type,$value,$placeholder = null,$attributes = null,$tooltip = null,$class = null,$regex = null,$validation = null,$step = null,$payload = null,$icon = null,$readonly = null,$disabled = null,$id = null,$hint = null,$oninput = null)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->step = $step;
        $this->payload = $payload;
        $this->attributes = $attributes;
        $this->tooltip = $tooltip;
        $this->value = $value;
        $this->class = $class;
        $this->id = $id;
        $this->regex = $regex != null ? regex($regex) : null;
        $this->validation = $validation != null ? validation($validation) : null;
        $this->icon = $icon;
        $this->readonly = $readonly;
        $this->disabled = $disabled;
        $this->hint = $hint;
        $this->oninput = $oninput;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input');
    }
}
