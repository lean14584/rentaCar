<div>
    <x-page-header title="Caja" subtitle="Movimientos de ingresos y egresos de dinero" icon="banknotes">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo movimiento
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-6 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        @if ($this->sesionAbierta)
            <div class="flex flex-wrap items-center justify-between gap-3">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    </span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        Caja abierta desde las <strong>{{ $this->sesionAbierta->hora_apertura->format('H:i') }}</strong>
                        por <strong>{{ $this->sesionAbierta->usuarioApertura->name }}</strong>
                        con $ {{ number_format((float) $this->sesionAbierta->monto_apertura, 2, ',', '.') }} de apertura.
                    </p>
                </div>
                <button type="button" wire:click="abrirCierre" class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500">
                    Cerrar caja
                </button>
            </div>
        @else
            <div class="flex flex-wrap items-center justify-between gap-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">La caja está cerrada. Abrila para empezar a registrar el turno.</p>
                <button type="button" wire:click="abrirApertura" class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500">
                    Abrir caja
                </button>
            </div>
        @endif
    </div>

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

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
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

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($movimientos as $movimiento)
            <div wire:key="caja-card-{{ $movimiento->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <span @class([
                            'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $movimiento->tipo === 'Ingreso',
                            'bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-400' => $movimiento->tipo === 'Egreso',
                        ])>{{ $movimiento->tipo }}</span>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $movimiento->descripcion }}</p>
                    </div>
                    <p class="shrink-0 font-semibold {{ $movimiento->tipo === 'Ingreso' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $movimiento->tipo === 'Ingreso' ? '+' : '-' }} $ {{ number_format((float) $movimiento->monto, 2, ',', '.') }}
                    </p>
                </div>
                <div class="mt-2 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>{{ $movimiento->created_at->format('d/m/Y H:i') }}</span>
                    <span>{{ $movimiento->auto?->patente ?? '—' }} · {{ $movimiento->metodo_pago }}</span>
                </div>
                @if ($movimiento->esManual())
                    <div class="mt-3 flex justify-end gap-1 border-t border-gray-100 pt-3 dark:border-gray-800">
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
                    <p class="mt-3 border-t border-gray-100 pt-3 text-right text-xs text-gray-400 dark:border-gray-800" title="Generado automáticamente, se edita desde su origen">Automático</p>
                @endif
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay movimientos cargados.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $movimientos->links() }}
    </div>

    <div class="mt-8">
        <h2 class="mb-3 text-sm font-semibold text-gray-700 dark:text-gray-300">Historial de cajas</h2>

        {{-- Desktop: tabla --}}
        <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
            <div class="overflow-x-auto">
                <table class="w-full text-center text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                        <tr>
                            <th class="px-4 py-3">Fecha</th>
                            <th class="px-4 py-3">Apertura</th>
                            <th class="px-4 py-3">Cierre</th>
                            <th class="px-4 py-3">Monto apertura</th>
                            <th class="px-4 py-3">Monto cierre</th>
                            <th class="px-4 py-3">Diferencia</th>
                            <th class="px-4 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse ($this->sesionesRecientes as $sesion)
                            <tr wire:key="sesion-{{ $sesion->id }}">
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $sesion->fecha->format('d/m/Y') }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $sesion->hora_apertura->format('H:i') }} · {{ $sesion->usuarioApertura->name }}</td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ $sesion->hora_cierre ? $sesion->hora_cierre->format('H:i').' · '.$sesion->usuarioCierre?->name : '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">$ {{ number_format((float) $sesion->monto_apertura, 2, ',', '.') }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $sesion->monto_cierre !== null ? '$ '.number_format((float) $sesion->monto_cierre, 2, ',', '.') : '—' }}</td>
                                <td class="px-4 py-3 font-semibold {{ (float) $sesion->diferencia === 0.0 ? 'text-gray-400' : ((float) $sesion->diferencia > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400') }}">
                                    {{ $sesion->diferencia !== null ? '$ '.number_format((float) $sesion->diferencia, 2, ',', '.') : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span @class([
                                        'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $sesion->estado === 'Abierta',
                                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $sesion->estado === 'Cerrada',
                                    ])>{{ $sesion->estado }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-gray-400">Todavía no se abrió ninguna caja.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Mobile: cards --}}
        <div class="space-y-3 sm:hidden">
            @forelse ($this->sesionesRecientes as $sesion)
                <div wire:key="sesion-card-{{ $sesion->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                    <div class="flex items-start justify-between gap-2">
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $sesion->fecha->format('d/m/Y') }}</p>
                        <span @class([
                            'inline-flex shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold',
                            'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $sesion->estado === 'Abierta',
                            'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $sesion->estado === 'Cerrada',
                        ])>{{ $sesion->estado }}</span>
                    </div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Apertura {{ $sesion->hora_apertura->format('H:i') }} · {{ $sesion->usuarioApertura->name }}
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Cierre {{ $sesion->hora_cierre ? $sesion->hora_cierre->format('H:i').' · '.$sesion->usuarioCierre?->name : '—' }}
                    </p>
                    <div class="mt-2 flex items-center justify-between text-sm">
                        <span class="text-gray-500 dark:text-gray-400">
                            $ {{ number_format((float) $sesion->monto_apertura, 2, ',', '.') }}
                            →
                            {{ $sesion->monto_cierre !== null ? '$ '.number_format((float) $sesion->monto_cierre, 2, ',', '.') : '—' }}
                        </span>
                        <span class="font-semibold {{ (float) $sesion->diferencia === 0.0 ? 'text-gray-400' : ((float) $sesion->diferencia > 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400') }}">
                            {{ $sesion->diferencia !== null ? '$ '.number_format((float) $sesion->diferencia, 2, ',', '.') : '—' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">Todavía no se abrió ninguna caja.</div>
            @endforelse
        </div>
    </div>

    <x-modal wireModel="showAperturaModal" title="Abrir caja" maxWidth="sm">
        <form wire:submit="abrirCaja" class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Monto de apertura *</label>
                <input
                    type="number" step="0.01" wire:model="monto_apertura" autofocus
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                <p class="mt-1 text-xs text-gray-400">El efectivo que hay físicamente en la caja al arrancar el turno.</p>
                @error('monto_apertura') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" wire:click="$set('showAperturaModal', false)" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="abrirCaja" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 disabled:opacity-50">
                    Abrir
                </button>
            </div>
        </form>
    </x-modal>

    <x-modal wireModel="showCierreModal" title="Cerrar caja" maxWidth="sm">
        <form wire:submit="cerrarCaja" class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Monto contado *</label>
                <input
                    type="number" step="0.01" wire:model.live="monto_cierre" autofocus
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                <p class="mt-1 text-xs text-gray-400">El efectivo que contaste físicamente en la caja.</p>
                @error('monto_cierre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-800 dark:bg-gray-800/40">
                <div class="flex justify-between py-1">
                    <span class="text-gray-500 dark:text-gray-400">Esperado (apertura + Efectivo del turno)</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">$ {{ number_format($this->montoEsperadoCierre, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 py-1 pt-2 dark:border-gray-700">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">Diferencia</span>
                    <span @class([
                        'font-bold',
                        'text-gray-400' => $this->diferenciaCierre === 0.0,
                        'text-emerald-600 dark:text-emerald-400' => $this->diferenciaCierre > 0,
                        'text-rose-600 dark:text-rose-400' => $this->diferenciaCierre < 0,
                    ])>$ {{ number_format($this->diferenciaCierre, 2, ',', '.') }}</span>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" wire:click="$set('showCierreModal', false)" class="rounded-xl border border-gray-300 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800">
                    Cancelar
                </button>
                <button type="submit" wire:loading.attr="disabled" wire:target="cerrarCaja" class="rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 disabled:opacity-50">
                    Cerrar
                </button>
            </div>
        </form>
    </x-modal>

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
