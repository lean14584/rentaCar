<div>
    <x-page-header title="Caja" subtitle="Movimientos de ingresos y egresos de dinero" icon="banknotes">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo movimiento
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Ingresos</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">$ {{ number_format($this->ingresos, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Egresos</p>
            <p class="mt-1 text-2xl font-bold text-rose-600 dark:text-rose-400">$ {{ number_format($this->egresos, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Saldo</p>
            <p class="mt-1 text-2xl font-bold {{ $this->saldo >= 0 ? 'text-gray-900 dark:text-gray-100' : 'text-rose-600 dark:text-rose-400' }}">
                $ {{ number_format($this->saldo, 2, ',', '.') }}
            </p>
        </div>
    </div>

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por descripción o patente..."
            aria-label="Buscar por descripción o patente"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Auto</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Monto</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($movimientos as $movimiento)
                        <tr wire:key="caja-{{ $movimiento->id }}">
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $movimiento->tipo === 'Ingreso',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $movimiento->tipo === 'Egreso',
                                ])>{{ $movimiento->tipo }}</span>
                            </td>
                            <td class="px-4 py-3 text-left text-gray-600 dark:text-gray-300">{{ $movimiento->descripcion }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $movimiento->auto?->patente ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $movimiento->metodo_pago }}</td>
                            <td class="px-4 py-3 font-semibold {{ $movimiento->tipo === 'Ingreso' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $movimiento->tipo === 'Ingreso' ? '+' : '-' }} $ {{ number_format((float) $movimiento->monto, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($movimiento->esManual())
                                    <div class="flex items-center justify-center gap-1">
                                        <button type="button" wire:click="edit({{ $movimiento->id }})" aria-label="Editar movimiento" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                            <x-heroicon-o-pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            type="button" wire:click="delete({{ $movimiento->id }})"
                                            wire:confirm="¿Borrar este movimiento?"
                                            aria-label="Eliminar movimiento"
                                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                        >
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                        </button>
                                    </div>
                                @else
                                    <span class="text-xs text-gray-400" title="Generado automáticamente, se edita desde su origen">Automático</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-gray-400">No hay movimientos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $movimientos->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar movimiento' : 'Nuevo movimiento'" maxWidth="lg">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Tipo *</label>
                <select wire:model="tipo" class="{{ $inputClass }}">
                    <option value="Ingreso">Ingreso</option>
                    <option value="Egreso">Egreso</option>
                </select>
                @error('tipo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Auto</label>
                <x-autocomplete wireModel="auto_id" :value="$auto_id" :options="$this->autos->map(fn ($auto) => ['value' => $auto->id, 'label' => $auto->patente])" placeholder="Sin auto asignado (buscar por patente)" />
                @error('auto_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Descripción *</label>
                <input type="text" wire:model="descripcion" autofocus placeholder="Ej: Retiro de dinero para gastos varios" class="{{ $inputClass }}">
                @error('descripcion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Método de pago *</label>
                <select wire:model="metodo_pago" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach (\App\Livewire\Caja\Index::METODOS_PAGO as $metodo)
                        <option value="{{ $metodo }}">{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('metodo_pago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Monto *</label>
                <input type="number" step="0.01" wire:model="monto" class="{{ $inputClass }}">
                @error('monto') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
