<?php

namespace App\Livewire;

use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Cliente;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    public function render()
    {
        return view('livewire.dashboard', [
            'autosDisponibles' => Auto::where('estado', 'Disponible')->count(),
            'autosAlquilados' => Auto::where('estado', 'Alquilado')->count(),
            'clientesTotal' => Cliente::count(),
            'alquileresActivos' => Alquiler::whereDate('fecha_fin', '>=', now())->count(),
        ]);
    }
}
