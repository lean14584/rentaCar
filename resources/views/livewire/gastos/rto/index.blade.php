<div>
    <x-page-header title="Gastos" subtitle="Seguros, pólizas, mantenimientos y RTO de la flota" icon="wrench">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nueva RTO
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-gastos-tabs />

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por empresa o patente..."
            aria-label="Buscar por empresa o patente"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Auto</th>
                        <th class="px-4 py-3">Empresa</th>
                        <th class="px-4 py-3">Realizada</th>
                        <th class="px-4 py-3">Vigencia hasta</th>
                        <th class="px-4 py-3">Valor</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($rtos as $rto)
                        <tr wire:key="rto-{{ $rto->id }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $rto->auto->patente }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $rto->empresa }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $rto->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 {{ $rto->fecha_fin->isPast() ? 'font-semibold text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ $rto->fecha_fin->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">$ {{ number_format((float) $rto->valor, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $rto->id }})" aria-label="Editar RTO" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $rto->id }})"
                                        wire:confirm="¿Borrar esta RTO?"
                                        aria-label="Eliminar RTO"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-gray-400">No hay RTO cargadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($rtos as $rto)
            <div wire:key="rto-card-{{ $rto->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $rto->auto->patente }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $rto->empresa }}</p>
                    </div>
                    <p class="shrink-0 text-sm font-medium text-gray-900 dark:text-gray-100">$ {{ number_format((float) $rto->valor, 2, ',', '.') }}</p>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Realizada {{ $rto->fecha_inicio->format('d/m/Y') }}</p>
                <p class="text-sm {{ $rto->fecha_fin->isPast() ? 'font-semibold text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400' }}">
                    Vigencia hasta {{ $rto->fecha_fin->format('d/m/Y') }}
                </p>
                <div class="mt-3 flex justify-end gap-1 border-t border-gray-100 pt-3 dark:border-gray-800">
                    <button type="button" wire:click="edit({{ $rto->id }})" aria-label="Editar RTO" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                    </button>
                    <button
                        type="button" wire:click="delete({{ $rto->id }})"
                        wire:confirm="¿Borrar esta RTO?"
                        aria-label="Eliminar RTO"
                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                    >
                        <x-heroicon-o-trash class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay RTO cargadas.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $rtos->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar RTO' : 'Nueva RTO'" maxWidth="lg">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Auto *</label>
                <x-autocomplete wireModel="auto_id" :value="$auto_id" :options="$this->autos->map(fn ($auto) => ['value' => $auto->id, 'label' => $auto->patente])" placeholder="Buscar por patente..." />
                @error('auto_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Empresa *</label>
                <input type="text" wire:model="empresa" autofocus class="{{ $inputClass }}">
                @error('empresa') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Fecha de realización *</label>
                <input type="date" wire:model="fecha_inicio" class="{{ $inputClass }}">
                @error('fecha_inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Vigencia hasta *</label>
                <input type="date" wire:model="fecha_fin" class="{{ $inputClass }}">
                @error('fecha_fin') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Método de pago *</label>
                <select wire:model="metodo_pago" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Debito">Débito</option>
                    <option value="Credito">Crédito</option>
                    <option value="Banco">Banco</option>
                </select>
                @error('metodo_pago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
