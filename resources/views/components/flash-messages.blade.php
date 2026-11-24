@if (session('status'))
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">
        <x-heroicon-o-check-circle class="h-5 w-5 shrink-0" />
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-4 flex items-center gap-2 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
        <x-heroicon-o-exclamation-circle class="h-5 w-5 shrink-0" />
        {{ session('error') }}
    </div>
@endif
