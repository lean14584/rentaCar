<div>
    <x-page-header title="Alquileres" subtitle="Reservá un auto y gestioná los alquileres activos" icon="key">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo alquiler
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por patente o cliente..."
            aria-label="Buscar por patente o cliente"
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
                        <th class="px-4 py-3">Cliente</th>
                        <th class="px-4 py-3">Desde</th>
                        <th class="px-4 py-3">Hasta</th>
                        <th class="px-4 py-3">Total</th>
                        <th class="px-4 py-3">Saldo</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($alquileres as $alquiler)
                        <tr wire:key="alquiler-{{ $alquiler->id }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $alquiler->auto->patente }}</td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $alquiler->cliente->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $alquiler->fecha_inicio->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $alquiler->fecha_fin->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-900 dark:text-gray-100">$ {{ number_format((float) $alquiler->total, 2, ',', '.') }}</td>
                            <td class="px-4 py-3 {{ (float) $alquiler->saldo > 0 ? 'text-amber-600 dark:text-amber-400 font-semibold' : 'text-gray-400' }}">$ {{ number_format((float) $alquiler->saldo, 2, ',', '.') }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $alquiler->estado === 'Activo',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $alquiler->estado === 'Finalizado',
                                ])>{{ $alquiler->estado }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    @if ($alquiler->estado === 'Activo')
                                        <button
                                            type="button" wire:click="finalizar({{ $alquiler->id }})"
                                            wire:confirm="¿Marcar este alquiler como finalizado? El auto vuelve a quedar Disponible."
                                            aria-label="Finalizar alquiler"
                                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-400 dark:hover:bg-emerald-500/10"
                                        >
                                            <x-heroicon-o-check-circle class="h-4 w-4" />
                                        </button>
                                        <button type="button" wire:click="edit({{ $alquiler->id }})" aria-label="Editar alquiler" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                            <x-heroicon-o-pencil class="h-4 w-4" />
                                        </button>
                                    @endif
                                    <button
                                        type="button" wire:click="delete({{ $alquiler->id }})"
                                        wire:confirm="¿Borrar este alquiler? Esta acción no se puede deshacer."
                                        aria-label="Eliminar alquiler"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-10 text-gray-400">No hay alquileres cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($alquileres as $alquiler)
            <div wire:key="alquiler-card-{{ $alquiler->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <p class="truncate font-semibold text-gray-900 dark:text-gray-100">{{ $alquiler->auto->patente }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $alquiler->cliente->nombre }}</p>
                    </div>
                    <span @class([
                        'inline-flex shrink-0 rounded-full px-2.5 py-0.5 text-xs font-semibold',
                        'bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-400' => $alquiler->estado === 'Activo',
                        'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $alquiler->estado === 'Finalizado',
                    ])>{{ $alquiler->estado }}</span>
                </div>
                <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ $alquiler->fecha_inicio->format('d/m/Y') }} — {{ $alquiler->fecha_fin->format('d/m/Y') }}
                </div>
                <div class="mt-2 flex items-center justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Total: <span class="font-medium text-gray-900 dark:text-gray-100">$ {{ number_format((float) $alquiler->total, 2, ',', '.') }}</span></span>
                    <span class="{{ (float) $alquiler->saldo > 0 ? 'font-semibold text-amber-600 dark:text-amber-400' : 'text-gray-400' }}">Saldo: $ {{ number_format((float) $alquiler->saldo, 2, ',', '.') }}</span>
                </div>
                <div class="mt-3 flex justify-end gap-1 border-t border-gray-100 pt-3 dark:border-gray-800">
                    @if ($alquiler->estado === 'Activo')
                        <button
                            type="button" wire:click="finalizar({{ $alquiler->id }})"
                            wire:confirm="¿Marcar este alquiler como finalizado? El auto vuelve a quedar Disponible."
                            aria-label="Finalizar alquiler"
                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 dark:text-gray-400 dark:hover:bg-emerald-500/10"
                        >
                            <x-heroicon-o-check-circle class="h-4 w-4" />
                        </button>
                        <button type="button" wire:click="edit({{ $alquiler->id }})" aria-label="Editar alquiler" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                            <x-heroicon-o-pencil class="h-4 w-4" />
                        </button>
                    @endif
                    <button
                        type="button" wire:click="delete({{ $alquiler->id }})"
                        wire:confirm="¿Borrar este alquiler? Esta acción no se puede deshacer."
                        aria-label="Eliminar alquiler"
                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                    >
                        <x-heroicon-o-trash class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay alquileres cargados.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $alquileres->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar alquiler' : 'Nuevo alquiler'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Auto *</label>
                <x-autocomplete
                    wireModel="auto_id"
                    :value="$auto_id"
                    :options="$this->autosDisponibles->map(fn ($auto) => ['value' => $auto->id, 'label' => $auto->patente.' · '.$auto->marca->nombre.' ('.$auto->modelo.')'])"
                    placeholder="Buscar por patente o marca..."
                />
                @error('auto_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                @error('fecha_inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Cliente *</label>
                <x-autocomplete
                    wireModel="cliente_id"
                    :value="$cliente_id"
                    :options="$this->clientes->map(fn ($cliente) => ['value' => $cliente->id, 'label' => $cliente->nombre])"
                    placeholder="Buscar por nombre..."
                />
                @error('cliente_id') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Fecha de inicio *</label>
                <input type="date" wire:model.live="fecha_inicio" class="{{ $inputClass }}">
                @error('fecha_inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Fecha de fin *</label>
                <input type="date" wire:model.live="fecha_fin" class="{{ $inputClass }}">
                @error('fecha_fin') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            @if ($auto_id !== '')
                <div class="sm:col-span-2 grid grid-cols-1 gap-4 rounded-xl border border-gray-100 bg-gray-50 p-4 dark:border-gray-800 dark:bg-gray-800/40 sm:grid-cols-3">
                    <div>
                        <label class="{{ $label }}">Cadena</label>
                        <select wire:model.live="cadena_id" class="{{ $inputClass }}">
                            <option value="">Sin cadena</option>
                            @foreach ($this->cadenasDisponibles as $cadena)
                                <option value="{{ $cadena->id }}">{{ $cadena->nombre }} (${{ number_format((float) $cadena->valor, 0, ',', '.') }}/día)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="{{ $label }}">Matafuego</label>
                        <select wire:model.live="matafuego_id" class="{{ $inputClass }}">
                            <option value="">Sin matafuego</option>
                            @foreach ($this->matafuegosDisponibles as $matafuego)
                                <option value="{{ $matafuego->id }}">{{ $matafuego->nombre }} (${{ number_format((float) $matafuego->valor, 0, ',', '.') }}/día)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="{{ $label }}">Silla de bebé</label>
                        <select wire:model.live="silla_id" class="{{ $inputClass }}">
                            <option value="">Sin silla</option>
                            @foreach ($this->sillasDisponibles as $silla)
                                <option value="{{ $silla->id }}">{{ $silla->nombre }} (${{ number_format((float) $silla->valor, 0, ',', '.') }}/día)</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div>
                <label class="{{ $label }}">Lugar de retiro *</label>
                <input type="text" wire:model="lugar_retiro" class="{{ $inputClass }}">
                @error('lugar_retiro') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Lugar de devolución *</label>
                <input type="text" wire:model="lugar_devolucion" class="{{ $inputClass }}">
                @error('lugar_devolucion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Método de pago *</label>
                <select wire:model="metodo_pago" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Transferencia">Transferencia</option>
                    <option value="Tarjeta">Tarjeta</option>
                </select>
                @error('metodo_pago') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Entrega (seña) *</label>
                <input type="number" step="0.01" wire:model.live="entrega" class="{{ $inputClass }}">
                @error('entrega') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2 rounded-xl border border-gray-100 bg-gray-50 p-4 text-sm dark:border-gray-800 dark:bg-gray-800/40">
                <div class="flex justify-between py-1">
                    <span class="text-gray-500 dark:text-gray-400">Días de alquiler</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $this->dias }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-gray-500 dark:text-gray-400">Importe por día</span>
                    <span class="font-medium text-gray-900 dark:text-gray-100">$ {{ number_format($this->importePorDia, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-200 py-1 pt-2 dark:border-gray-700">
                    <span class="font-semibold text-gray-700 dark:text-gray-300">Total</span>
                    <span class="font-bold text-gray-900 dark:text-gray-100">$ {{ number_format($this->total, 2, ',', '.') }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="font-semibold text-amber-700 dark:text-amber-400">Saldo pendiente</span>
                    <span class="font-bold text-amber-700 dark:text-amber-400">$ {{ number_format($this->saldo, 2, ',', '.') }}</span>
                </div>
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
