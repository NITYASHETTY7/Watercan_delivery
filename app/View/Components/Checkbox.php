<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Checkbox extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

    public $name;
    public $type;
    public $tooltip;
    public $validation;
    public $class;
    public $value;
    public $onclick;
    public $arr;
    public $attributes;

    public function __construct($name,$type,$value,$class = null,$arr = [],$tooltip = null,$validation = null, $attributes = [], $onclick = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->tooltip = $tooltip;
        $this->validation = $validation;
        $this->class = $class;
        $this->value = $value;
        $this->onclick = $onclick;
        $this->arr = $arr;
        $this->attributes = $attributes;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.checkbox');
    }
}
