<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Textarea extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $placeholder;
    public $value;
    public $regex;
    public $validation;
    public $class;
    public $id;
    public $rows;
    public $cols;

    public function __construct($name,$placeholder,$value,$id = null,$class = null,$regex = null,$validation = null,$rows = 5,$cols=5)
    {
        $this->name = $name;
        $this->placeholder = $placeholder;
        $this->value = $value;
        $this->class = $class;
        $this->rows = $rows;
        $this->cols = $cols;
        $this->id = $id;
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
        return view('components.textarea');
    }
}
