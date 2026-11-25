<?php

namespace App\Livewire\Caja;

use App\Models\Auto;
use App\Models\Caja;
use App\Models\CajaSesion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public const METODOS_PAGO = ['Efectivo', 'Transferencia', 'Tarjeta', 'Debito', 'Credito', 'Banco'];

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $tipo = 'Ingreso';

    public string $auto_id = '';

    public string $metodo_pago = '';

    public string $descripcion = '';

    public string $monto = '';

    public bool $showAperturaModal = false;

    public bool $showCierreModal = false;

    public string $monto_apertura = '';

    public string $monto_cierre = '';

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function autos()
    {
        return Auto::orderBy('patente')->get();
    }

    #[Computed]
    public function ingresos(): float
    {
        return (float) Caja::where('tipo', 'Ingreso')->sum('monto');
    }

    #[Computed]
    public function egresos(): float
    {
        return (float) Caja::where('tipo', 'Egreso')->sum('monto');
    }

    #[Computed]
    public function saldo(): float
    {
        return $this->ingresos - $this->egresos;
    }

    #[Computed]
    public function sesionAbierta(): ?CajaSesion
    {
        return CajaSesion::abierta()?->load('usuarioApertura');
    }

    #[Computed]
    public function sesionesRecientes()
    {
        return CajaSesion::with(['usuarioApertura', 'usuarioCierre'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();
    }

    /** Vista previa en vivo de lo que daría el cierre con el monto contado hasta ahora. */
    #[Computed]
    public function montoEsperadoCierre(): float
    {
        $sesion = $this->sesionAbierta;
        if (! $sesion) {
            return 0;
        }

        return (float) $sesion->monto_apertura + $this->movimientosEfectivoSesion($sesion->id);
    }

    #[Computed]
    public function diferenciaCierre(): float
    {
        return (float) ($this->monto_cierre ?: 0) - $this->montoEsperadoCierre;
    }

    private function movimientosEfectivoSesion(int $sesionId): float
    {
        $ingresos = (float) Caja::where('caja_sesion_id', $sesionId)->where('tipo', 'Ingreso')->where('metodo_pago', 'Efectivo')->sum('monto');
        $egresos = (float) Caja::where('caja_sesion_id', $sesionId)->where('tipo', 'Egreso')->where('metodo_pago', 'Efectivo')->sum('monto');

        return $ingresos - $egresos;
    }

    public function abrirApertura(): void
    {
        $this->monto_apertura = '';
        $this->resetErrorBag();
        $this->showAperturaModal = true;
    }

    public function abrirCaja(): void
    {
        $this->validate(['monto_apertura' => ['required', 'numeric', 'min:0']]);

        DB::transaction(function () {
            $sesion = CajaSesion::create([
                'fecha' => now()->toDateString(),
                'usuario_apertura_id' => Auth::id(),
                'monto_apertura' => $this->monto_apertura,
                'hora_apertura' => now(),
                'estado' => 'Abierta',
            ]);

            // Movimientos generados mientras la caja estaba cerrada (por
            // Alquileres o Gastos) quedan "huérfanos" (caja_sesion_id null)
            // hasta la próxima apertura DEL MISMO DÍA — filtrar por fecha de
            // hoy evita arrastrar movimientos de días anteriores a una
            // sesión nueva.
            Caja::whereNull('caja_sesion_id')
                ->whereDate('created_at', now()->toDateString())
                ->update(['caja_sesion_id' => $sesion->id]);
        });

        $this->showAperturaModal = false;
        unset($this->sesionAbierta, $this->sesionesRecientes);
        session()->flash('status', 'Caja abierta.');
    }

    public function abrirCierre(): void
    {
        $this->monto_cierre = '';
        $this->resetErrorBag();
        $this->showCierreModal = true;
    }

    public function cerrarCaja(): void
    {
        $sesion = $this->sesionAbierta;
        abort_unless($sesion, 404);

        $this->validate(['monto_cierre' => ['required', 'numeric', 'min:0']]);

        $montoEsperado = (float) $sesion->monto_apertura + $this->movimientosEfectivoSesion($sesion->id);

        $sesion->update([
            'usuario_cierre_id' => Auth::id(),
            'monto_cierre' => $this->monto_cierre,
            'monto_esperado' => $montoEsperado,
            'diferencia' => (float) $this->monto_cierre - $montoEsperado,
            'hora_cierre' => now(),
            'estado' => 'Cerrada',
        ]);

        $this->showCierreModal = false;
        unset($this->sesionAbierta, $this->sesionesRecientes);
        session()->flash('status', 'Caja cerrada.');
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Caja $caja): void
    {
        abort_unless($caja->esManual(), 403);

        $this->resetForm();
        $this->editingId = $caja->id;
        $this->tipo = $caja->tipo;
        $this->auto_id = (string) $caja->auto_id;
        $this->metodo_pago = $caja->metodo_pago;
        $this->descripcion = $caja->descripcion;
        $this->monto = (string) $caja->monto;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'tipo' => ['required', 'in:Ingreso,Egreso'],
            'auto_id' => ['nullable', 'exists:autos,id'],
            'metodo_pago' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'monto' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $caja = $this->editingId ? Caja::findOrFail($this->editingId) : new Caja([
            'usuario_id' => Auth::id(),
            'caja_sesion_id' => CajaSesion::abierta()?->id,
        ]);
        $caja->fill([
            'auto_id' => $this->auto_id ?: null,
            'tipo' => $this->tipo,
            'metodo_pago' => $this->metodo_pago,
            'descripcion' => $this->descripcion,
            'monto' => $this->monto,
        ]);
        $caja->save();

        $this->showModal = false;
        $this->resetForm();
        unset($this->sesionAbierta);
        session()->flash('status', $this->editingId ? 'Movimiento actualizado.' : 'Movimiento cargado.');
    }

    public function delete(Caja $caja): void
    {
        abort_unless($caja->esManual(), 403);

        $caja->delete();
        session()->flash('status', 'Movimiento eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'auto_id', 'metodo_pago', 'descripcion', 'monto']);
        $this->tipo = 'Ingreso';
        $this->resetErrorBag();
    }

    public function render()
    {
        $movimientos = Caja::query()
            ->with(['auto', 'usuario'])
            ->when(
                $this->query !== '',
                fn ($q) => $q->where('descripcion', 'like', "%{$this->query}%")
                    ->orWhereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.caja.index', ['movimientos' => $movimientos]);
    }
}
