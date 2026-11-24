<?php

namespace App\Livewire\Marcas;

use App\Models\Marca;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public string $query = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public string $nombre = '';

    public ?UploadedFile $image = null;

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Marca $marca): void
    {
        $this->resetForm();
        $this->editingId = $marca->id;
        $this->nombre = $marca->nombre;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3', Rule::unique('marcas', 'nombre')->ignore($this->editingId)],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $marca = $this->editingId ? Marca::findOrFail($this->editingId) : new Marca;
        $marca->nombre = $this->nombre;
        $marca->save();

        if ($this->image) {
            $anterior = $marca->image;
            $marca->update(['image' => $this->image->store('marcas', 'public')]);

            if ($anterior) {
                Storage::disk('public')->delete($anterior);
            }
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Marca actualizada.' : 'Marca creada.');
    }

    public function delete(Marca $marca): void
    {
        abort_unless(Auth::user()->can('marcas'), 403);

        if ($marca->autos()->exists()) {
            session()->flash('error', 'No se puede borrar: tiene autos cargados con esta marca.');

            return;
        }

        if ($marca->image) {
            Storage::disk('public')->delete($marca->image);
        }

        $marca->delete();
        session()->flash('status', 'Marca eliminada.');
    }

    private function resetForm(): void
    {
        $this->reset(['editingId', 'nombre', 'image']);
        $this->resetErrorBag();
    }

    public function render()
    {
        $marcas = Marca::query()
            ->when($this->query !== '', fn ($q) => $q->where('nombre', 'like', "%{$this->query}%"))
            ->withCount('autos')
            ->orderBy('nombre')
            ->paginate(15);

        return view('livewire.marcas.index', ['marcas' => $marcas]);
    }
}
