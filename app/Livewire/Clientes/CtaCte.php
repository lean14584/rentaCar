<?php

namespace App\Livewire\Clientes;

use App\Models\Caja;
use App\Models\CajaSesion;
use App\Models\Cliente;
use App\Models\ClienteCtaCte;
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

    public Cliente $cliente;

    public bool $showModal = false;

    public string $metodo_pago = '';

    public string $descripcion = '';

    public string $importe = '';

    public function mount(Cliente $cliente): void
    {
        $this->cliente = $cliente;
    }

    #[Computed]
    public function cargos(): float
    {
        return (float) $this->cliente->ctaCte()->where('tipo', 'Cargo')->sum('importe');
    }

    #[Computed]
    public function pagos(): float
    {
        return (float) $this->cliente->ctaCte()->where('tipo', 'Pago')->sum('importe');
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
            $pago = ClienteCtaCte::create([
                'cliente_id' => $this->cliente->id,
                'tipo' => 'Pago',
                'importe' => $this->importe,
                'descripcion' => $this->descripcion,
                'metodo_pago' => $this->metodo_pago,
            ]);

            Caja::create([
                'usuario_id' => Auth::id(),
                'caja_sesion_id' => CajaSesion::abierta()?->id,
                'cliente_cta_cte_id' => $pago->id,
                'tipo' => 'Ingreso',
                'monto' => $this->importe,
                'metodo_pago' => $this->metodo_pago,
                'descripcion' => 'Pago de cliente '.$this->cliente->nombre.': '.$this->descripcion,
            ]);
        });

        $this->showModal = false;
        session()->flash('status', 'Pago registrado.');
    }

    public function delete(ClienteCtaCte $movimiento): void
    {
        abort_unless($movimiento->cliente_id === $this->cliente->id, 403);
        abort_unless($movimiento->esManual(), 403);

        $movimiento->delete();
        session()->flash('status', 'Movimiento eliminado.');
    }

    public function render()
    {
        $movimientos = $this->cliente->ctaCte()->orderByDesc('id')->paginate(15);

        return view('livewire.clientes.cta-cte', ['movimientos' => $movimientos]);
    }
}
