<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Date extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $readonly;
    public $placeholder;
    public $type;
    public $value;
    public $regex;
    public $validation;
    public $class;
    public $id;
    public $max;

    public function __construct($name,$type,$value,$id = null,$max = null,$class = null,$regex = null,$validation = null,$placeholder = null,$readonly = null)
    {
        $this->name = $name;
        $this->readonly = $readonly;
        $this->placeholder = $placeholder;
        $this->type = $type;
        $this->value = $value;
        $this->class = $class;
        $this->id = $id;
        $this->max = $max;
        $this->regex = $regex != null ? regex($regex) : null;
        $this->validation = $validation != null ? validation($validation) : null;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.date');
    }
}