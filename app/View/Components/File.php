<?php

namespace App\View\Components;

use Illuminate\View\Component;

class File extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $name;
    public $class;
    public $version;
    public $value;
    public $accept;
    public $id;
    public $validation;

    public function __construct($name, $class = null, $version = 'drag', $validation = null, $value = null, $accept = null, $id = null)
    {
        $this->name = $name;
        $this->class = $class;
        $this->validation = $validation != null ? validation($validation) : null;
        $this->value = $value;
        $this->accept = $accept;
        $this->id = $id;
        $this->version = $version;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.file');
    }
}
