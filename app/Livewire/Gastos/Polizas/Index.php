<?php

namespace App\Livewire\Gastos\Polizas;

use App\Models\Auto;
use App\Models\Poliza;
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

    public function edit(Poliza $poliza): void
    {
        $this->resetForm();
        $this->editingId = $poliza->id;
        $this->empresa = $poliza->empresa;
        $this->auto_id = (string) $poliza->auto_id;
        $this->vigencia_hasta = $poliza->vigencia_hasta->toDateString();
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'empresa' => ['required', 'string'],
            'auto_id' => ['required', 'exists:autos,id'],
            'vigencia_hasta' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $poliza = $this->editingId ? Poliza::findOrFail($this->editingId) : new Poliza;
        $poliza->fill([
            'empresa' => $this->empresa,
            'auto_id' => $this->auto_id,
            'vigencia_hasta' => $this->vigencia_hasta,
        ]);
        $poliza->save();

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Póliza actualizada.' : 'Póliza cargada.');
    }

    public function delete(Poliza $poliza): void
    {
        $poliza->delete();
        session()->flash('status', 'Póliza eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'empresa', 'auto_id', 'vigencia_hasta']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $polizas = Poliza::query()
            ->with('auto')
            ->when(
                $this->query !== '',
                fn ($q) => $q->where('empresa', 'like', "%{$this->query}%")
                    ->orWhereHas('auto', fn ($a) => $a->where('patente', 'like', "%{$this->query}%"))
            )
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.gastos.polizas.index', ['polizas' => $polizas]);
    }
}
