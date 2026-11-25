<?php

namespace App\Livewire\Gastos\Rto;

use App\Models\Auto;
use App\Models\Caja;
use App\Models\CajaSesion;
use App\Models\Rto;
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

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $empresa = '';

    public string $auto_id = '';

    public string $fecha_inicio = '';

    public string $fecha_fin = '';

    public string $metodo_pago = '';

    public string $valor = '';

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function autos()
    {
        return Auto::orderBy('patente')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Rto $rto): void
    {
        $this->resetForm();
        $this->editingId = $rto->id;
        $this->empresa = $rto->empresa;
        $this->auto_id = (string) $rto->auto_id;
        $this->fecha_inicio = $rto->fecha_inicio->toDateString();
        $this->fecha_fin = $rto->fecha_fin->toDateString();
        $this->metodo_pago = (string) $rto->metodo_pago;
        $this->valor = (string) $rto->valor;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'empresa' => ['required', 'string'],
            'auto_id' => ['required', 'exists:autos,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
            'metodo_pago' => ['required', 'string'],
            'valor' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $rto = $this->editingId ? Rto::findOrFail($this->editingId) : new Rto;
            $rto->fill([
                'empresa' => $this->empresa,
                'auto_id' => $this->auto_id,
                'fecha_inicio' => $this->fecha_inicio,
                'fecha_fin' => $this->fecha_fin,
                'metodo_pago' => $this->metodo_pago,
                'valor' => $this->valor,
            ]);
            $rto->save();

            $cajaExistente = Caja::where('rto_id', $rto->id)->first();
            $datosCaja = [
                'usuario_id' => Auth::id(),
                'auto_id' => $rto->auto_id,
                'tipo' => 'Egreso',
                'monto' => $this->valor,
                'metodo_pago' => $this->metodo_pago,
                'descripcion' => 'RTO en '.$this->empresa.' - '.$rto->auto->patente,
            ];
            if (! $cajaExistente) {
                $datosCaja['caja_sesion_id'] = CajaSesion::abierta()?->id;
            }
            Caja::updateOrCreate(['rto_id' => $rto->id], $datosCaja);
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'RTO actualizada.' : 'RTO cargada.');
    }

    public function delete(Rto $rto): void
    {
        $rto->delete();
        session()->flash('status', 'RTO eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'empresa', 'auto_id', 'fecha_inicio', 'fecha_fin', 'metodo_pago', 'valor']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $rtos = Rto::query()
            ->with('auto')
            ->when(
                $this->query !== '',
                fn ($q) => $q->where('empresa', 'like', "%{$this->query}%")
                    ->orWhereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.gastos.rto.index', ['rtos' => $rtos]);
    }
}
