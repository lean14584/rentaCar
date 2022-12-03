<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ReportesController extends Component
{

	public $componentName;
    public function mount()
    {
		$this->componentName = 'Reportes Sistema Renta Car';
    }

    public function render()
    {

        return view('livewire.reportes.component')
		->extends('layouts.theme.app')
        ->section('content');
    }


}
