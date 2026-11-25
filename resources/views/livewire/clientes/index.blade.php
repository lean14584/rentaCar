<div>
    <x-page-header title="Clientes" subtitle="Gestioná los datos de tus clientes" icon="users">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo cliente
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por nombre..."
            aria-label="Buscar por nombre"
            class="w-full max-w-sm rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
        >
    </div>

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Documento</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Licencia vence</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($clientes as $cliente)
                        <tr wire:key="cliente-{{ $cliente->id }}">
                            <td class="px-4 py-3">
                                <div class="mx-auto h-9 w-9">
                                    @if ($cliente->image)
                                        <img src="{{ asset('storage/'.$cliente->image) }}" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-400 dark:bg-gray-800">
                                            {{ Str::upper(Str::substr($cliente->nombre, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $cliente->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $cliente->documento }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $cliente->telefono }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $cliente->vencimiento_licencia?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('clientes.cta-cte', $cliente) }}" wire:navigate aria-label="Ver cuenta corriente" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-banknotes class="h-4 w-4" />
                                    </a>
                                    <button type="button" wire:click="edit({{ $cliente->id }})" aria-label="Editar cliente" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $cliente->id }})"
                                        wire:confirm="¿Borrar a {{ $cliente->nombre }}?"
                                        aria-label="Eliminar cliente"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-gray-400">No hay clientes cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($clientes as $cliente)
            <div wire:key="cliente-card-{{ $cliente->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 shrink-0">
                        @if ($cliente->image)
                            <img src="{{ asset('storage/'.$cliente->image) }}" class="h-10 w-10 rounded-full object-cover">
                        @else
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-400 dark:bg-gray-800">
                                {{ Str::upper(Str::substr($cliente->nombre, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate font-medium text-gray-900 dark:text-gray-100">{{ $cliente->nombre }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $cliente->documento }} · {{ $cliente->telefono }}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">Licencia vence {{ $cliente->vencimiento_licencia?->format('d/m/Y') ?? '—' }}</p>
                    </div>
                </div>
                <div class="mt-3 flex justify-end gap-1 border-t border-gray-100 pt-3 dark:border-gray-800">
                    <a href="{{ route('clientes.cta-cte', $cliente) }}" wire:navigate aria-label="Ver cuenta corriente" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        <x-heroicon-o-banknotes class="h-4 w-4" />
                    </a>
                    <button type="button" wire:click="edit({{ $cliente->id }})" aria-label="Editar cliente" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                    </button>
                    <button
                        type="button" wire:click="delete({{ $cliente->id }})"
                        wire:confirm="¿Borrar a {{ $cliente->nombre }}?"
                        aria-label="Eliminar cliente"
                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                    >
                        <x-heroicon-o-trash class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay clientes cargados.</div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $clientes->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar cliente' : 'Nuevo cliente'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Nombre *</label>
                <input type="text" wire:model="nombre" autofocus placeholder="Ej: Juan Pérez" class="{{ $inputClass }}">
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Teléfono *</label>
                <input type="text" wire:model="telefono" placeholder="Ej: 2604565656" class="{{ $inputClass }}">
                @error('telefono') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Tipo de documento *</label>
                <select wire:model="tipo_documento" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    <option value="96">DNI</option>
                    <option value="80">CUIT</option>
                    <option value="86">CUIL</option>
                    <option value="94">Pasaporte</option>
                    <option value="91">CI extranjera</option>
                    <option value="99">Otros</option>
                </select>
                @error('tipo_documento') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Documento *</label>
                <input type="text" wire:model="documento" placeholder="xxxxxxxx" class="{{ $inputClass }}">
                @error('documento') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Correo *</label>
                <input type="email" wire:model="correo" class="{{ $inputClass }}">
                @error('correo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Provincia *</label>
                <input type="text" wire:model="provincia" placeholder="Ej: Mendoza" class="{{ $inputClass }}">
                @error('provincia') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Ciudad *</label>
                <input type="text" wire:model="ciudad" placeholder="Ej: San Rafael" class="{{ $inputClass }}">
                @error('ciudad') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Dirección *</label>
                <input type="text" wire:model="direccion" placeholder="Ej: El Libertador 123" class="{{ $inputClass }}">
                @error('direccion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Licencia *</label>
                <input type="text" wire:model="licencia" class="{{ $inputClass }}">
                @error('licencia') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Vencimiento de licencia *</label>
                <input type="date" wire:model="vencimiento_licencia" class="{{ $inputClass }}">
                @error('vencimiento_licencia') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-3">
                <label class="{{ $label }}">Foto</label>
                <div class="flex items-center gap-4">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-14 w-14 rounded-full border border-gray-200 object-cover dark:border-gray-700">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-full border border-dashed border-gray-300 text-gray-300 dark:border-gray-700 dark:text-gray-600">
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
