<div>
    <x-page-header title="Proveedores" subtitle="Gestioná los datos de tus proveedores" icon="building-storefront">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo proveedor
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

    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Foto</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">CUIT</th>
                        <th class="px-4 py-3">Localidad</th>
                        <th class="px-4 py-3">Teléfono</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($proveedores as $proveedor)
                        <tr wire:key="proveedor-{{ $proveedor->id }}">
                            <td class="px-4 py-3">
                                <div class="mx-auto h-9 w-9">
                                    @if ($proveedor->image)
                                        <img src="{{ asset('storage/'.$proveedor->image) }}" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-400 dark:bg-gray-800">
                                            {{ Str::upper(Str::substr($proveedor->nombre, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $proveedor->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $proveedor->cuit }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $proveedor->localidad }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $proveedor->telefono }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $proveedor->id }})" aria-label="Editar proveedor" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $proveedor->id }})"
                                        wire:confirm="¿Borrar a {{ $proveedor->nombre }}?"
                                        aria-label="Eliminar proveedor"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-gray-400">No hay proveedores cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $proveedores->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar proveedor' : 'Nuevo proveedor'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Nombre *</label>
                <input type="text" wire:model="nombre" autofocus placeholder="Ej: Distribuidora Norte" class="{{ $inputClass }}">
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Inicio de actividad *</label>
                <input type="date" wire:model="inicio" class="{{ $inputClass }}">
                @error('inicio') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">CUIT *</label>
                <input type="text" wire:model="cuit" placeholder="20-12345678-9" class="{{ $inputClass }}">
                @error('cuit') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Ingresos brutos *</label>
                <input type="text" wire:model="brutos" class="{{ $inputClass }}">
                @error('brutos') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Localidad *</label>
                <input type="text" wire:model="localidad" placeholder="Ej: San Rafael" class="{{ $inputClass }}">
                @error('localidad') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Teléfono *</label>
                <input type="text" wire:model="telefono" placeholder="Ej: 2604565656" class="{{ $inputClass }}">
                @error('telefono') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Correo *</label>
                <input type="email" wire:model="correo" class="{{ $inputClass }}">
                @error('correo') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="sm:col-span-2">
                <label class="{{ $label }}">Dirección *</label>
                <input type="text" wire:model="direccion" placeholder="Ej: El Libertador 123" class="{{ $inputClass }}">
                @error('direccion') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
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
