<?php

namespace App\Livewire\Reportes;

use App\Models\Alquiler;
use App\Models\Auto;
use App\Models\Caja;
use App\Models\Cliente;
use App\Models\Mantenimiento;
use App\Models\Poliza;
use App\Models\Proveedor;
use App\Models\Rto;
use App\Models\Seguro;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Index extends Component
{
    public string $tab = 'devolver';

    public string $desde = '';

    public string $hasta = '';

    public function mount(): void
    {
        $this->desde = now()->startOfMonth()->toDateString();
        $this->hasta = now()->endOfMonth()->toDateString();
    }

    #[Computed]
    public function autosADevolver()
    {
        return Alquiler::query()
            ->with(['auto', 'cliente'])
            ->where('estado', 'Activo')
            ->whereBetween('fecha_fin', [$this->desde, $this->hasta])
            ->orderBy('fecha_fin')
            ->get();
    }

    #[Computed]
    public function segurosPorVencer()
    {
        return Seguro::query()
            ->with('auto')
            ->whereBetween('vigencia_hasta', [$this->desde, $this->hasta])
            ->orderBy('vigencia_hasta')
            ->get();
    }

    #[Computed]
    public function polizasPorVencer()
    {
        return Poliza::query()
            ->with('auto')
            ->whereBetween('vigencia_hasta', [$this->desde, $this->hasta])
            ->orderBy('vigencia_hasta')
            ->get();
    }

    /**
     * Ganancia real por auto a partir de Caja (ya es la fuente de verdad
     * del dinero que efectivamente entró/salió) en vez de volver a sumar
     * por separado alquileres, mantenimientos, seguros, pólizas y RTO.
     */
    #[Computed]
    public function gananciasPorAuto()
    {
        $enRango = fn ($q) => $q->whereBetween('cajas.created_at', [$this->desde, $this->hasta.' 23:59:59']);

        return Auto::query()
            ->withSum(['cajas as ingresos' => fn ($q) => $enRango($q)->where('tipo', 'Ingreso')], 'monto')
            ->withSum(['cajas as egresos' => fn ($q) => $enRango($q)->where('tipo', 'Egreso')], 'monto')
            ->get()
            ->map(function (Auto $auto) {
                $auto->ingresos = (float) ($auto->ingresos ?? 0);
                $auto->egresos = (float) ($auto->egresos ?? 0);
                $auto->ganancia = $auto->ingresos - $auto->egresos;

                return $auto;
            })
            ->sortByDesc('ganancia')
            ->values();
    }

    #[Computed]
    public function autosMasAlquilados()
    {
        // HAVING sobre una columna de withCount no es válido en SQLite (no
        // es una función de agregación en el SELECT) — se filtra en PHP,
        // la tabla de autos es chica y no vale la pena la vuelta en SQL.
        $enRango = fn ($q) => $q->whereBetween('alquilers.fecha_inicio', [$this->desde, $this->hasta]);

        return Auto::query()
            ->withCount(['alquileres' => fn ($q) => $enRango($q)])
            ->withSum(['alquileres as total_facturado' => fn ($q) => $enRango($q)], 'total')
            ->get()
            ->filter(fn (Auto $auto) => $auto->alquileres_count > 0)
            ->sortByDesc('alquileres_count')
            ->values();
    }

    #[Computed]
    public function mantenimientosPorFinalizar()
    {
        return Mantenimiento::query()
            ->with(['auto', 'proveedor'])
            ->where('estado', 'En Curso')
            ->whereBetween('fecha_inicio', [$this->desde, $this->hasta])
            ->orderBy('fecha_fin')
            ->get();
    }

    #[Computed]
    public function rtoPorVencer()
    {
        return Rto::query()
            ->with('auto')
            ->whereBetween('fecha_fin', [$this->desde, $this->hasta])
            ->orderBy('fecha_fin')
            ->get();
    }

    /** Saldo total (Cargo − Pago) por cliente, igual criterio que su Cta Cte individual. */
    #[Computed]
    public function clientesConSaldo()
    {
        return Cliente::query()
            ->withSum(['ctaCte as cargos' => fn ($q) => $q->where('tipo', 'Cargo')], 'importe')
            ->withSum(['ctaCte as pagos' => fn ($q) => $q->where('tipo', 'Pago')], 'importe')
            ->get()
            ->map(function (Cliente $cliente) {
                $cliente->saldo = (float) ($cliente->cargos ?? 0) - (float) ($cliente->pagos ?? 0);

                return $cliente;
            })
            ->filter(fn (Cliente $cliente) => $cliente->saldo > 0)
            ->sortByDesc('saldo')
            ->values();
    }

    /** Saldo total (Cargo − Pago) por proveedor, igual criterio que su Cta Cte individual. */
    #[Computed]
    public function proveedoresConSaldo()
    {
        return Proveedor::query()
            ->withSum(['ctaCte as cargos' => fn ($q) => $q->where('tipo', 'Cargo')], 'importe')
            ->withSum(['ctaCte as pagos' => fn ($q) => $q->where('tipo', 'Pago')], 'importe')
            ->get()
            ->map(function (Proveedor $proveedor) {
                $proveedor->saldo = (float) ($proveedor->cargos ?? 0) - (float) ($proveedor->pagos ?? 0);

                return $proveedor;
            })
            ->filter(fn (Proveedor $proveedor) => $proveedor->saldo > 0)
            ->sortByDesc('saldo')
            ->values();
    }

    /** A 1000km o menos del cambio de aceite programado (o ya pasado). */
    #[Computed]
    public function autosProximosService()
    {
        return Auto::query()
            ->whereNotNull('cambio_aceite')
            ->get()
            ->map(function (Auto $auto) {
                $auto->km_restantes = $auto->cambio_aceite - $auto->kilometros;

                return $auto;
            })
            ->filter(fn (Auto $auto) => $auto->km_restantes <= 1000)
            ->sortBy('km_restantes')
            ->values();
    }

    public function render()
    {
        return view('livewire.reportes.index');
    }
}
