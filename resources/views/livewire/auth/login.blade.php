<div class="w-full max-w-sm">
    <div class="mb-8 flex items-center justify-center gap-2">
        <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-500 text-white shadow-sm">
            <x-heroicon-o-truck class="h-5 w-5" />
        </span>
        <span class="text-xl font-bold text-gray-900 dark:text-gray-100">RentaCar</span>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
        <h1 class="mb-6 text-lg font-semibold text-gray-900 dark:text-gray-100">Ingresar</h1>

        <form wire:submit="submit" class="space-y-4">
            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                <input
                    type="email"
                    wire:model="email"
                    autofocus
                    autocomplete="username"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                @error('email') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                <input
                    type="password"
                    wire:model="password"
                    autocomplete="current-password"
                    class="w-full rounded-xl border border-gray-300 bg-white px-3 py-2.5 text-sm shadow-sm focus:border-amber-500 focus:outline-none focus:ring-2 focus:ring-amber-500/30 dark:border-gray-700 dark:bg-gray-800/60 dark:text-gray-100"
                >
                @error('password') <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                <input type="checkbox" wire:model="remember" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700">
                Recordarme
            </label>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="submit"
                class="w-full rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-amber-600 disabled:opacity-50"
            >
                <span wire:loading.remove wire:target="submit">Ingresar</span>
                <span wire:loading wire:target="submit">Ingresando...</span>
            </button>
        </form>
    </div>
</div>
