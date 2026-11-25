<?php

namespace App\Livewire\Caja;

use App\Models\Auto;
use App\Models\Caja;
use Illuminate\Support\Facades\Auth;
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

        $caja = $this->editingId ? Caja::findOrFail($this->editingId) : new Caja(['usuario_id' => Auth::id()]);
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
