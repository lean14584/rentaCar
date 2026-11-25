<?php

namespace App\Livewire\Proveedores;

use App\Models\Caja;
use App\Models\CajaSesion;
use App\Models\Proveedor;
use App\Models\ProveedorCtaCte;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CtaCte extends Component
{
    use WithPagination;

    public const METODOS_PAGO = ['Efectivo', 'Transferencia', 'Tarjeta', 'Debito', 'Credito', 'Banco'];

    public Proveedor $proveedor;

    public bool $showModal = false;

    public string $metodo_pago = '';

    public string $descripcion = '';

    public string $importe = '';

    public function mount(Proveedor $proveedor): void
    {
        $this->proveedor = $proveedor;
    }

    #[Computed]
    public function cargos(): float
    {
        return (float) $this->proveedor->ctaCte()->where('tipo', 'Cargo')->sum('importe');
    }

    #[Computed]
    public function pagos(): float
    {
        return (float) $this->proveedor->ctaCte()->where('tipo', 'Pago')->sum('importe');
    }

    #[Computed]
    public function saldo(): float
    {
        return $this->cargos - $this->pagos;
    }

    public function create(): void
    {
        $this->reset(['metodo_pago', 'descripcion', 'importe']);
        $this->descripcion = 'Pago a cuenta';
        $this->resetErrorBag();
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'metodo_pago' => ['required', 'string'],
            'descripcion' => ['required', 'string'],
            'importe' => ['required', 'numeric', 'min:0.01'],
        ];
    }

    public function registrarPago(): void
    {
        $this->validate();

        DB::transaction(function () {
            $pago = ProveedorCtaCte::create([
                'proveedor_id' => $this->proveedor->id,
                'tipo' => 'Pago',
                'importe' => $this->importe,
                'descripcion' => $this->descripcion,
                'metodo_pago' => $this->metodo_pago,
            ]);

            Caja::create([
                'usuario_id' => Auth::id(),
                'caja_sesion_id' => CajaSesion::abierta()?->id,
                'proveedor_cta_cte_id' => $pago->id,
                'tipo' => 'Egreso',
                'monto' => $this->importe,
                'metodo_pago' => $this->metodo_pago,
                'descripcion' => 'Pago a proveedor '.$this->proveedor->nombre.': '.$this->descripcion,
            ]);
        });

        $this->showModal = false;
        session()->flash('status', 'Pago registrado.');
    }

    public function delete(ProveedorCtaCte $movimiento): void
    {
        abort_unless($movimiento->proveedor_id === $this->proveedor->id, 403);
        abort_unless($movimiento->esManual(), 403);

        $movimiento->delete();
        session()->flash('status', 'Movimiento eliminado.');
    }

    public function render()
    {
        $movimientos = $this->proveedor->ctaCte()->orderByDesc('id')->paginate(15);

        return view('livewire.proveedores.cta-cte', ['movimientos' => $movimientos]);
    }
}
