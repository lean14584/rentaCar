<div>
    <x-page-header :title="'Cta. Cte. — '.$proveedor->nombre" subtitle="Cargos y pagos de este proveedor" icon="building-storefront">
        <x-slot:actions>
            <a href="{{ route('proveedores.index') }}" wire:navigate class="flex items-center gap-1.5 whitespace-nowrap rounded-xl border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                <x-heroicon-o-arrow-left class="h-4 w-4" /> Volver a Proveedores
            </a>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Registrar pago
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Cargos</p>
            <p class="mt-1 text-2xl font-bold text-rose-600 dark:text-rose-400">$ {{ number_format($this->cargos, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Pagos</p>
            <p class="mt-1 text-2xl font-bold text-emerald-600 dark:text-emerald-400">$ {{ number_format($this->pagos, 2, ',', '.') }}</p>
        </div>
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <p class="text-sm text-gray-500 dark:text-gray-400">Saldo (le debemos)</p>
            <p class="mt-1 text-2xl font-bold {{ $this->saldo > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-gray-900 dark:text-gray-100' }}">
                $ {{ number_format($this->saldo, 2, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Fecha</th>
                        <th class="px-4 py-3">Tipo</th>
                        <th class="px-4 py-3">Descripción</th>
                        <th class="px-4 py-3">Método</th>
                        <th class="px-4 py-3">Importe</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($movimientos as $movimiento)
                        <tr wire:key="ctacte-{{ $movimiento->id }}">
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $movimiento->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $movimiento->tipo === 'Cargo',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $movimiento->tipo === 'Pago',
                                ])>{{ $movimiento->tipo }}</span>
                            </td>
                            <td class="px-4 py-3 text-left text-gray-600 dark:text-gray-300">{{ $movimiento->descripcion }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $movimiento->metodo_pago ?? '—' }}</td>
                            <td class="px-4 py-3 font-semibold {{ $movimiento->tipo === 'Cargo' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                {{ $movimiento->tipo === 'Cargo' ? '+' : '-' }} $ {{ number_format((float) $movimiento->importe, 2, ',', '.') }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($movimiento->esManual())
                                    <button
                                        type="button" wire:click="delete({{ $movimiento->id }})"
                                        wire:confirm="¿Borrar este pago?"
                                        aria-label="Eliminar pago"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400" title="Generado automáticamente desde el gasto de origen">Automático</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-gray-400">No hay movimientos cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($movimientos as $movimiento)
            <div wire:key="ctacte-card-{{ $movimiento->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <span @class([
                            'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $movimiento->tipo === 'Cargo',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $movimiento->tipo === 'Pago',
                        ])>{{ $movimiento->tipo }}</span>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $movimiento->descripcion }}</p>
                    </div>
                    <p class="shrink-0 font-semibold {{ $movimiento->tipo === 'Cargo' ? 'text-rose-600 dark:text-rose-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                        {{ $movimiento->tipo === 'Cargo' ? '+' : '-' }} $ {{ number_format((float) $movimiento->importe, 2, ',', '.') }}
                    </p>
                </div>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ $movimiento->created_at->format('d/m/Y H:i') }}</span>
                    <span>{{ $movimiento->metodo_pago ?? '—' }}</span>
                </div>
                @if ($movimiento->esManual())
                    <div class="mt-3 flex justify-end border-t border-gray-100 pt-3 dark:border-gray-800">
                        <button
                            type="button" wire:click="delete({{ $movimiento->id }})"
                            wire:confirm="¿Borrar este pago?"
                            aria-label="Eliminar pago"
                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                        >
                            <x-heroicon-o-trash class="h-4 w-4" />
                        </button>
                    </div>
                @else
                    <p class="mt-3 border-t border-gray-100 pt-3 text-right text-xs text-gray-400 dark:border-gray-800" title="Generado automáticamente desde el gasto de origen">Automático</p>
                @endif
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay movimientos cargados.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $movimientos->links() }}
    </div>

    <x-modal wireModel="showModal" title="Registrar pago" maxWidth="md">
        <form wire:submit="registrarPago" class="grid grid-cols-1 gap-4">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Método de pago *</label>
                <select wire:model="metodo_pago" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach (\App\Livewire\Proveedores\CtaCte::METODOS_PAGO as $metodo)
                        <option value="{{ $metodo }}">{{ $metodo }}</option>
                    @endforeach
                </select>
                @error('metodo_pago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Descripción *</label>
                <input type="text" wire:model="descripcion" class="{{ $inputClass }}">
                @error('descripcion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Importe *</label>
                <input type="number" step="0.01" wire:model="importe" autofocus class="{{ $inputClass }}">
                @error('importe') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="col-span-full mt-2 flex justify-end gap-2">
                <button type="button" wire:click="$set('showModal', false)" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="registrarPago" class="rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-600 disabled:opacity-50">
                    Guardar
                </button>
            </div>
        </form>
    </x-modal>
</div>
