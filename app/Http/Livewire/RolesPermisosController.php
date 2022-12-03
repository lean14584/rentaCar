<?php

namespace App\Http\Livewire;

use Livewire\Component;

class RolesPermisosController extends Component
{

	public $componentName;
    public function mount()
    {
		$this->componentName = 'Gestion Roles Y Permisos';
    }

    public function render()
    {

        return view('livewire.permisosRoles.component')
		->extends('layouts.theme.app')
        ->section('content');
    }


}
