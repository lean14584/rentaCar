<?php

namespace App\Http\Livewire;

use Livewire\Component;

class SegurosPantallaController extends Component
{

	public $componentName;
    public function mount()
    {
		$this->componentName = 'Carga De Seguros,Polizas,Rto,Gastos,Cadenas de nieve y matafuegos';
    }

    public function render()
    {

        return view('livewire.segurospolizas.component')
		->extends('layouts.theme.app')
        ->section('content');
    }


}
