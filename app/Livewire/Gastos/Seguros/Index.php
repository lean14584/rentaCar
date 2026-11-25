<?php

namespace App\Livewire\Gastos\Seguros;

use App\Models\Auto;
use App\Models\Seguro;
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

    public string $nombre = '';

    public string $auto_id = '';

    public string $valor = '';

    public string $vigencia_hasta = '';

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

    public function edit(Seguro $seguro): void
    {
        $this->resetForm();
        $this->editingId = $seguro->id;
        $this->nombre = $seguro->nombre;
        $this->auto_id = (string) $seguro->auto_id;
        $this->valor = (string) $seguro->valor;
        $this->vigencia_hasta = $seguro->vigencia_hasta->toDateString();
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string'],
            'auto_id' => ['required', 'exists:autos,id'],
            'valor' => ['required', 'numeric', 'min:0'],
            'vigencia_hasta' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $seguro = $this->editingId ? Seguro::findOrFail($this->editingId) : new Seguro;
        $seguro->fill([
            'nombre' => $this->nombre,
            'auto_id' => $this->auto_id,
            'valor' => $this->valor,
            'vigencia_hasta' => $this->vigencia_hasta,
        ]);
        $seguro->save();

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Seguro actualizado.' : 'Seguro cargado.');
    }

    public function delete(Seguro $seguro): void
    {
        $seguro->delete();
        session()->flash('status', 'Seguro eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'nombre', 'auto_id', 'valor', 'vigencia_hasta']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $seguros = Seguro::query()
            ->with('auto')
            ->when(
                $this->query !== '',
                fn ($q) => $q->where('nombre', 'like', "%{$this->query}%")
                    ->orWhereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.gastos.seguros.index', ['seguros' => $seguros]);
    }
}
