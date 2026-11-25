<div>
    <x-page-header title="Roles y permisos" subtitle="Definí qué pantallas puede ver cada rol" icon="shield-check">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo rol
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    {{-- Desktop: tabla --}}
    <div class="hidden overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm sm:block dark:border-gray-800 dark:bg-gray-900">
        <div class="overflow-x-auto">
            <table class="w-full text-center text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-500 dark:bg-gray-800/50 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Permisos</th>
                        <th class="px-4 py-3">Usuarios</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($roles as $role)
                        <tr wire:key="role-{{ $role->id }}">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $role->permissions_count }} / {{ count($labels) }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $role->users_count }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $role->id }})" aria-label="Editar rol" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    <button
                                        type="button" wire:click="delete({{ $role->id }})"
                                        wire:confirm="¿Borrar el rol {{ $role->name }}?"
                                        aria-label="Eliminar rol"
                                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                    >
                                        <x-heroicon-o-trash class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-10 text-gray-400">No hay roles cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Mobile: cards --}}
    <div class="space-y-3 sm:hidden">
        @forelse ($roles as $role)
            <div wire:key="role-card-{{ $role->id }}" class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-start justify-between gap-2">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $role->name }}</p>
                </div>
                <div class="mt-2 flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    <span>{{ $role->permissions_count }} / {{ count($labels) }} permisos</span>
                    <span>{{ $role->users_count }} {{ Str::plural('usuario', $role->users_count) }}</span>
                </div>
                <div class="mt-3 flex justify-end gap-1 border-t border-gray-100 pt-3 dark:border-gray-800">
                    <button type="button" wire:click="edit({{ $role->id }})" aria-label="Editar rol" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                        <x-heroicon-o-pencil class="h-4 w-4" />
                    </button>
                    <button
                        type="button" wire:click="delete({{ $role->id }})"
                        wire:confirm="¿Borrar el rol {{ $role->name }}?"
                        aria-label="Eliminar rol"
                        class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                    >
                        <x-heroicon-o-trash class="h-4 w-4" />
                    </button>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-gray-200 bg-white p-6 text-center text-gray-400 dark:border-gray-800 dark:bg-gray-900">No hay roles cargados.</div>
        @endforelse
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar rol' : 'Nuevo rol'" maxWidth="lg">
        <form wire:submit="save" class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre del rol *</label>
                <input
                    type="text" wire:model="nombre" autofocus placeholder="Ej: Cobrador"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                @error('nombre') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">Pantallas permitidas</label>
                <div class="grid grid-cols-2 gap-2 rounded-xl border border-gray-200 bg-gray-50 p-4 sm:grid-cols-3 dark:border-gray-800 dark:bg-gray-800/40">
                    @foreach ($labels as $pantalla => $etiqueta)
                        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="checkbox" wire:model="permisos.{{ $pantalla }}" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                            {{ $etiqueta }}
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
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
