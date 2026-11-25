<div>
    <x-page-header title="Autos" subtitle="Gestioná la flota" icon="truck">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo auto
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por patente..."
            aria-label="Buscar por patente"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Patente</th>
                        <th class="px-4 py-3">Marca / Modelo</th>
                        <th class="px-4 py-3">Titular</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Precio/día</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($autos as $auto)
                        <tr wire:key="auto-{{ $auto->id }}">
                            <td class="px-4 py-3">
                                <div class="mx-auto h-10 w-10">
                                    @if ($auto->image)
                                        <img src="{{ asset('storage/'.$auto->image) }}" class="h-10 w-10 rounded-md object-cover">
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-gray-100 text-gray-300 dark:bg-gray-800">
                                            <x-heroicon-o-photo class="h-5 w-5" />
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $auto->patente }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $auto->marca->nombre }} · {{ $auto->modelo }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $auto->titular }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $auto->estado === 'Disponible',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $auto->estado === 'Alquilado',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $auto->estado === 'En Mantenimiento',
                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' => $auto->estado === 'No Disponible',
                                ])>{{ $auto->estado }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">$ {{ number_format((float) $auto->precio_alquiler, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $auto->id }})" aria-label="Editar auto" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $auto->id }})"
                                        wire:confirm="¿Borrar el auto {{ $auto->patente }}?"
                                        aria-label="Eliminar auto"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-gray-400">No hay autos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $autos->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar auto' : 'Nuevo auto'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Patente *</label>
                <input type="text" wire:model="patente" class="{{ $inputClass }} uppercase" maxlength="10">
                @error('patente') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Marca *</label>
                <select wire:model="marca_id" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach ($this->marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>
                @error('marca_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Modelo (año) *</label>
                <input type="number" wire:model="modelo" class="{{ $inputClass }}">
                @error('modelo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Tipo *</label>
                <input type="text" wire:model="tipo" placeholder="Sedan, SUV, Pickup..." class="{{ $inputClass }}">
                @error('tipo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Color *</label>
                <input type="text" wire:model="color" class="{{ $inputClass }}">
                @error('color') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Titular *</label>
                <select wire:model="titular" class="{{ $inputClass }}">
                    <option value="Dueño">Dueño</option>
                    <option value="Tercero">Tercero</option>
                </select>
                @error('titular') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Combustible *</label>
                <select wire:model="combustible" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    <option value="Nafta">Nafta</option>
                    <option value="Diesel">Diesel</option>
                    <option value="GNC">GNC</option>
                </select>
                @error('combustible') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Estado *</label>
                <select wire:model="estado" class="{{ $inputClass }}">
                    <option value="Disponible">Disponible</option>
                    <option value="Alquilado">Alquilado</option>
                    <option value="En Mantenimiento">En Mantenimiento</option>
                    <option value="No Disponible">No Disponible</option>
                </select>
                @error('estado') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Kilómetros *</label>
                <input type="number" wire:model="kilometros" class="{{ $inputClass }}">
                @error('kilometros') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Km próx. cambio de aceite *</label>
                <input type="number" wire:model="cambio_aceite" class="{{ $inputClass }}">
                @error('cambio_aceite') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Precio de alquiler / día *</label>
                <input type="number" step="0.01" wire:model="precio_alquiler" class="{{ $inputClass }}">
                @error('precio_alquiler') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Foto</label>
                <div class="flex items-center gap-4">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-14 w-14 rounded-lg border border-gray-200 object-cover dark:border-gray-700">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-300 text-gray-300 dark:border-gray-700 dark:text-gray-600">
                            <x-heroicon-o-photo class="h-6 w-6" />
                        </div>
                    @endif
                    <input type="file" wire:model="image" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 dark:text-gray-400 dark:file:bg-amber-500/10 dark:file:text-amber-300">
                </div>
                @error('image') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-full mt-2 flex justify-end gap-2">
                <button type="button" wire:click="$set('showModal', false)" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="save" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 disabled:opacity-50">
                    Guardar
                </button>
            </div>
        </form>
    </x-modal>
</div>
