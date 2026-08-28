<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Label extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $id;
    public $tooltip;
    public $validation;
    public $class;

    public function __construct($name,$tooltip = null,$class = null,$validation = null,$id=null)
    {
        $this->name = $name;
        $this->id = $id != null ? $id : $name;
        $this->tooltip = $tooltip;
        $this->class = $class;
        $this->validation = $validation != null ? validation($validation) : null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.label');
    }
}
