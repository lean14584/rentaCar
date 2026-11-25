<div>
    <x-page-header title="Gastos" subtitle="Seguros, pólizas, mantenimientos y RTO de la flota" icon="wrench">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo gasto
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-gastos-tabs />

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por tipo o patente..."
            aria-label="Buscar por tipo o patente"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Auto</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Proveedor</th>
                        <th class="px-4 py-3">Desde</th>
                        <th class="px-4 py-3">Hasta</th>
                        <th class="px-4 py-3">Valor</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($mantenimientos as $mantenimiento)
                        <tr wire:key="mantenimiento-{{ $mantenimiento->id }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $mantenimiento->auto->patente }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $mantenimiento->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->proveedor->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $mantenimiento->fecha_fin?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $mantenimiento->valor, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $mantenimiento->estado === 'En Curso',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $mantenimiento->estado === 'Finalizada',
                                ])>{{ $mantenimiento->estado }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($mantenimiento->estado === 'En Curso')
                                        <button
                                            type="button" wire:click="finalizar({{ $mantenimiento->id }})"
                                            wire:confirm="¿Marcar este gasto como finalizado? El auto vuelve a quedar Disponible."
                                            aria-label="Finalizar gasto"
                                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-400 dark:hover:bg-emerald-500/10"
                                        >
                                            <x-heroicon-o-check-circle class="h-4 w-4" />
                                        </button>
                                        <button type="button" wire:click="edit({{ $mantenimiento->id }})" aria-label="Editar gasto" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                            <x-heroicon-o-pencil class="h-4 w-4" />
                                        </button>
                                    @endif
                                    <button
                                        type="button" wire:click="delete({{ $mantenimiento->id }})"
                                        wire:confirm="¿Borrar este gasto? Esta acción no se puede deshacer."
                                        aria-label="Eliminar gasto"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-gray-400">No hay gastos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $mantenimientos->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar gasto' : 'Nuevo gasto'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Auto *</label>
                <x-autocomplete wireModel="auto_id" :value="$auto_id" :options="$this->autos->map(fn ($auto) => ['value' => $auto->id, 'label' => $auto->patente])" placeholder="Buscar por patente..." />
                @error('auto_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Tipo de gasto *</label>
                <select wire:model="nombre" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach (\App\Livewire\Gastos\Mantenimientos\Index::TIPOS as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Proveedor *</label>
                <x-autocomplete wireModel="proveedor_id" :value="$proveedor_id" :options="$this->proveedores->map(fn ($proveedor) => ['value' => $proveedor->id, 'label' => $proveedor->nombre])" placeholder="Buscar por nombre..." />
                @error('proveedor_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Método de pago *</label>
                <select wire:model="metodo_pago" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach (\App\Livewire\Gastos\Mantenimientos\Index::METODOS_PAGO as $metodo)
                        <option value="{{ $metodo }}">{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('metodo_pago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Km actuales *</label>
                <input type="number" wire:model="km_actual" class="{{ $inputClass }}">
                @error('km_actual') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Km próximo *</label>
                <input type="number" wire:model="km_proximo" class="{{ $inputClass }}">
                @error('km_proximo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Fecha de inicio *</label>
                <input type="date" wire:model="fecha_inicio" class="{{ $inputClass }}">
                @error('fecha_inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Fecha de fin</label>
                <input type="date" wire:model="fecha_fin" class="{{ $inputClass }}">
                @error('fecha_fin') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Valor *</label>
                <input type="number" step="0.01" wire:model="valor" class="{{ $inputClass }}">
                @error('valor') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
