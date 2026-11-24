<div>
    <x-page-header title="Marcas" subtitle="Gestioná las marcas de tu flota" icon="tag">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nueva marca
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
                        <th class="px-4 py-3">Logo</th>
                        <th class="px-4 py-3">Nombre</th>
                        <th class="px-4 py-3">Autos</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($marcas as $marca)
                        <tr wire:key="marca-{{ $marca->id }}">
                            <td class="px-4 py-3">
                                <div class="mx-auto h-8 w-8">
                                    @if ($marca->image)
                                        <img src="{{ asset('storage/'.$marca->image) }}" class="h-8 w-8 rounded-md object-contain">
                                    @else
                                        <div class="flex h-8 w-8 items-center justify-center rounded-md bg-gray-100 text-gray-300 dark:bg-gray-800">
                                            <x-heroicon-o-photo class="h-4 w-4" />
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $marca->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $marca->autos_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $marca->id }})" aria-label="Editar marca" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $marca->id }})"
                                        wire:confirm="¿Borrar la marca {{ $marca->nombre }}?"
                                        aria-label="Eliminar marca"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-gray-400">No hay marcas cargadas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $marcas->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar marca' : 'Nueva marca'">
        <form wire:submit="save">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre *</label>
                <input
                    type="text" wire:model="nombre" autofocus
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mt-4">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Logo</label>
                <div class="flex items-center gap-4">
                    @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="h-14 w-14 rounded-lg border border-gray-200 object-contain dark:border-gray-700">
                    @else
                        <div class="flex h-14 w-14 items-center justify-center rounded-lg border border-dashed border-gray-300 text-gray-300 dark:border-gray-700 dark:text-gray-600">
                            <x-heroicon-o-photo class="h-6 w-6" />
                        </div>
                    @endif
                    <input type="file" wire:model="image" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-amber-50 file:px-3 file:py-1.5 file:text-sm file:font-medium file:text-amber-700 hover:file:bg-amber-100 dark:text-gray-400 dark:file:bg-amber-500/10 dark:file:text-amber-300">
                </div>
                @error('image') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
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
