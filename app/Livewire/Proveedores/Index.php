<?php

namespace App\Livewire\Proveedores;

use App\Models\Proveedor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public string $inicio = '';

    public string $cuit = '';

    public string $brutos = '';

    public string $localidad = '';

    public string $telefono = '';

    public string $direccion = '';

    public string $correo = '';

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

    public function edit(Proveedor $proveedor): void
    {
        $this->resetForm();
        $this->editingId = $proveedor->id;
        $this->nombre = $proveedor->nombre;
        $this->inicio = $proveedor->inicio?->toDateString() ?? '';
        $this->cuit = (string) $proveedor->cuit;
        $this->brutos = (string) $proveedor->brutos;
        $this->localidad = (string) $proveedor->localidad;
        $this->telefono = (string) $proveedor->telefono;
        $this->direccion = (string) $proveedor->direccion;
        $this->correo = (string) $proveedor->correo;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'min:3'],
            'inicio' => ['required', 'date'],
            'cuit' => ['required', 'string'],
            'brutos' => ['required', 'string'],
            'localidad' => ['required', 'string'],
            'telefono' => ['required', 'string'],
            'direccion' => ['required', 'string'],
            'correo' => ['required', 'email'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $proveedor = $this->editingId ? Proveedor::findOrFail($this->editingId) : new Proveedor;
        $proveedor->fill([
            'nombre' => $this->nombre,
            'inicio' => $this->inicio,
            'cuit' => $this->cuit,
            'brutos' => $this->brutos,
            'localidad' => $this->localidad,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
            'correo' => $this->correo,
        ]);
        $proveedor->save();

        if ($this->image) {
            $anterior = $proveedor->image;
            $proveedor->update(['image' => $this->image->store('proveedores', 'public')]);

            if ($anterior) {
                Storage::disk('public')->delete($anterior);
            }
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Proveedor actualizado.' : 'Proveedor creado.');
    }

    public function delete(Proveedor $proveedor): void
    {
        abort_unless(Auth::user()->can('proveedores'), 403);

        if ($proveedor->mantenimientos()->exists()) {
            session()->flash('error', 'No se puede borrar: tiene gastos cargados.');

            return;
        }

        if ($proveedor->image) {
            Storage::disk('public')->delete($proveedor->image);
        }

        $proveedor->delete();
        session()->flash('status', 'Proveedor eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'nombre', 'inicio', 'cuit', 'brutos',
            'localidad', 'telefono', 'direccion', 'correo', 'image',
        ]);
        $this->resetErrorBag();
    }

    public function render()
    {
        $proveedores = Proveedor::query()
            ->when($this->query !== '', fn ($q) => $q->where('nombre', 'like', "%{$this->query}%"))
            ->orderBy('nombre')
            ->paginate(15);

        return view('livewire.proveedores.index', ['proveedores' => $proveedores]);
    }
}
