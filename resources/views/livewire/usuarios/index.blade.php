<div>
    <x-page-header title="Usuarios" subtitle="Gestioná el equipo que accede al sistema" icon="user-group">
        <x-slot:actions>
            <button type="button" wire:click="create" class="flex items-center gap-1.5 whitespace-nowrap rounded-xl bg-amber-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-600">
                <x-heroicon-o-plus class="h-4 w-4" /> Nuevo usuario
            </button>
        </x-slot:actions>
    </x-page-header>

    <x-flash-messages />

    <div class="mb-4">
        <input
            type="text" wire:model.live.debounce.300ms="query"
            placeholder="Buscar por nombre o usuario..."
            aria-label="Buscar por nombre o usuario"
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
                        <th class="px-4 py-3">Usuario</th>
                        <th class="px-4 py-3">Email</th>
                        <th class="px-4 py-3">Rol</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse ($usuarios as $usuario)
                        <tr wire:key="usuario-{{ $usuario->id }}">
                            <td class="px-4 py-3">
                                <div class="mx-auto h-9 w-9">
                                    @if ($usuario->image)
                                        <img src="{{ asset('storage/'.$usuario->image) }}" class="h-9 w-9 rounded-full object-cover">
                                    @else
                                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-gray-100 text-xs font-semibold text-gray-400 dark:bg-gray-800">
                                            {{ Str::upper(Str::substr($usuario->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $usuario->name }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $usuario->username }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $usuario->email }}</td>
                            <td class="px-4 py-3 text-gray-500 dark:text-gray-400">{{ $usuario->getRoleNames()->first() ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span @class([
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold',
                                    'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-400' => $usuario->activo,
                                    'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' => ! $usuario->activo,
                                ])>{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" wire:click="edit({{ $usuario->id }})" aria-label="Editar usuario" class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-gray-800">
                                        <x-heroicon-o-pencil class="h-4 w-4" />
                                    </button>
                                    @if ($usuario->id !== auth()->id())
                                        <button
                                            type="button" wire:click="delete({{ $usuario->id }})"
                                            wire:confirm="¿Borrar a {{ $usuario->name }}?"
                                            aria-label="Eliminar usuario"
                                            class="inline-flex rounded-md p-1.5 text-gray-500 hover:bg-red-50 hover:text-red-600 dark:text-gray-400 dark:hover:bg-red-500/10"
                                        >
                                            <x-heroicon-o-trash class="h-4 w-4" />
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-10 text-gray-400">No hay usuarios cargados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $usuarios->links() }}
    </div>

    <x-modal wireModel="showModal" :title="$editingId ? 'Editar usuario' : 'Nuevo usuario'" maxWidth="2xl">
        <form wire:submit="save" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            @php $inputClass = 'w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100'; @endphp
            @php $label = 'mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300'; @endphp

            <div>
                <label class="{{ $label }}">Nombre *</label>
                <input type="text" wire:model="name" autofocus class="{{ $inputClass }}">
                @error('name') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Usuario *</label>
                <input type="text" wire:model="username" class="{{ $inputClass }}">
                @error('username') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Email *</label>
                <input type="email" wire:model="email" class="{{ $inputClass }}">
                @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Teléfono</label>
                <input type="text" wire:model="telefono" class="{{ $inputClass }}">
                @error('telefono') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">Rol *</label>
                <select wire:model="role" class="{{ $inputClass }}">
                    <option value="">Elegir...</option>
                    @foreach ($this->roles as $rol)
                        <option value="{{ $rol->name }}">{{ $rol->name }}</option>
                    @endforeach
                </select>
                @error('role') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="{{ $label }}">{{ $editingId ? 'Nueva contraseña' : 'Contraseña *' }}</label>
                <input type="password" wire:model="password" placeholder="{{ $editingId ? 'Dejar en blanco para no cambiar' : '' }}" class="{{ $inputClass }}">
                @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-2 pt-6">
                <input type="checkbox" wire:model="activo" id="activo" class="h-4 w-4 rounded border-gray-300 text-amber-500 focus:ring-amber-500">
                <label for="activo" class="text-sm font-medium text-gray-700 dark:text-gray-300">Usuario activo (puede iniciar sesión)</label>
            </div>

            <div class="sm:col-span-2">
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
