<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResultadoPrediccion extends Component
{

    public $prediccion;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($prediccion)
    {
        $this->prediccion = $prediccion;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.resultado-prediccion');
    }
}
