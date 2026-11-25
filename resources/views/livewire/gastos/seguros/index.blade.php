<div>
    <x-page-header title="Gastos" subtitle="Seguros, pólizas, mantenimientos y RTO de la flota" icon="wrench">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo seguro
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-gastos-tabs />

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por aseguradora o patente..."
            aria-label="Buscar por aseguradora o patente"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Auto</th>
                        <th class="px-4 py-3">Aseguradora</th>
                        <th class="px-4 py-3">Valor</th>
                        <th class="px-4 py-3">Vigencia hasta</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($seguros as $seguro)
                        <tr wire:key="seguro-{{ $seguro->id }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $seguro->auto->patente }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $seguro->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $seguro->valor, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 {{ $seguro->vigencia_hasta->isPast() ? 'font-semibold text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $seguro->vigencia_hasta->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $seguro->id }})" aria-label="Editar seguro" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $seguro->id }})"
                                        wire:confirm="¿Borrar este seguro?"
                                        aria-label="Eliminar seguro"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-10 text-gray-400">No hay seguros cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $seguros->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar seguro' : 'Nuevo seguro'" maxWidth="md">
        <form wire:submit="save" class="grid grid-cols-1 gap-4">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Auto *</label>
                <select wire:model="auto_id" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach ($this->autos as $auto)
                        <option value="{{ $auto->id }}">{{ $auto->patente }}</option>
                    @endforeach
                </select>
                @error('auto_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Aseguradora *</label>
                <input type="text" wire:model="nombre" autofocus placeholder="Ej: La Segunda" class="{{ $inputClass }}">
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Valor *</label>
                <input type="number" step="0.01" wire:model="valor" class="{{ $inputClass }}">
                @error('valor') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Vigencia hasta *</label>
                <input type="date" wire:model="vigencia_hasta" class="{{ $inputClass }}">
                @error('vigencia_hasta') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
