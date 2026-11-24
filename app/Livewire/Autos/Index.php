<?php

namespace App\Livewire\Autos;

use App\Models\Auto;
use App\Models\Marca;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
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

    public string $patente = '';

    public string $titular = 'Dueño';

    public string $kilometros = '';

    public string $cambio_aceite = '';

    public string $combustible = '';

    public string $modelo = '';

    public string $marca_id = '';

    public string $tipo = '';

    public string $color = '';

    public string $estado = 'Disponible';

    public string $precio_alquiler = '';

    public ?UploadedFile $image = null;

    public function updatedQuery(): void
    {
        $this->resetPage();
    }

    #[Computed]
    public function marcas()
    {
        return Marca::orderBy('nombre')->get();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(Auto $auto): void
    {
        $this->resetForm();
        $this->editingId = $auto->id;
        $this->patente = $auto->patente;
        $this->titular = $auto->titular;
        $this->kilometros = (string) $auto->kilometros;
        $this->cambio_aceite = (string) $auto->cambio_aceite;
        $this->combustible = $auto->combustible;
        $this->modelo = (string) $auto->modelo;
        $this->marca_id = (string) $auto->marca_id;
        $this->tipo = (string) $auto->tipo;
        $this->color = $auto->color;
        $this->estado = $auto->estado;
        $this->precio_alquiler = (string) $auto->precio_alquiler;
        $this->showModal = true;
    }

    protected function rules(): array
    {
        return [
            'patente' => ['required', 'string', 'min:3', Rule::unique('autos', 'patente')->ignore($this->editingId)],
            'titular' => ['required', Rule::in(['Dueño', 'Tercero'])],
            'kilometros' => ['required', 'integer', 'min:0'],
            'cambio_aceite' => ['required', 'integer', 'min:0'],
            'combustible' => ['required', Rule::in(['Nafta', 'Diesel', 'GNC'])],
            'modelo' => ['required', 'integer', 'digits:4'],
            'marca_id' => ['required', 'exists:marcas,id'],
            'tipo' => ['required', 'string'],
            'color' => ['required', 'string'],
            'estado' => ['required', Rule::in(['Disponible', 'Alquilado', 'No Disponible'])],
            'precio_alquiler' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $auto = $this->editingId ? Auto::findOrFail($this->editingId) : new Auto;
        $auto->fill([
            'patente' => $this->patente,
            'titular' => $this->titular,
            'kilometros' => $this->kilometros,
            'cambio_aceite' => $this->cambio_aceite,
            'combustible' => $this->combustible,
            'modelo' => $this->modelo,
            'marca_id' => $this->marca_id,
            'tipo' => $this->tipo,
            'color' => $this->color,
            'estado' => $this->estado,
            'precio_alquiler' => $this->precio_alquiler,
        ]);
        $auto->save();

        if ($this->image) {
            $anterior = $auto->image;
            $auto->update(['image' => $this->image->store('autos', 'public')]);

            if ($anterior) {
                Storage::disk('public')->delete($anterior);
            }
        }

        $this->showModal = false;
        $this->resetForm();
        session()->flash('status', $this->editingId ? 'Auto actualizado.' : 'Auto creado.');
    }

    public function delete(Auto $auto): void
    {
        abort_unless(Auth::user()->can('autos'), 403);

        if ($auto->alquileres()->exists()) {
            session()->flash('error', 'No se puede borrar: tiene alquileres cargados.');

            return;
        }

        if ($auto->image) {
            Storage::disk('public')->delete($auto->image);
        }

        $auto->delete();
        session()->flash('status', 'Auto eliminado.');
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'patente', 'kilometros', 'cambio_aceite', 'combustible',
            'modelo', 'marca_id', 'tipo', 'color', 'precio_alquiler', 'image',
        ]);
        $this->titular = 'Dueño';
        $this->estado = 'Disponible';
        $this->resetErrorBag();
    }

    public function render()
    {
        $autos = Auto::query()
            ->with('marca')
            ->when($this->query !== '', fn ($q) => $q->where('patente', 'like', "%{$this->query}%"))
            ->orderByDesc('id')
            ->paginate(15);

        return view('livewire.autos.index', ['autos' => $autos]);
    }
}
