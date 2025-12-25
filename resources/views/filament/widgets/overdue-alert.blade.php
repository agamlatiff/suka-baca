<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3 p-4 bg-danger-50 dark:bg-danger-950 rounded-xl border border-danger-200 dark:border-danger-800">
            <div class="flex-shrink-0">
                <x-heroicon-o-exclamation-triangle class="w-8 h-8 text-danger-600 dark:text-danger-400" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-semibold text-danger-800 dark:text-danger-200">
                    ⚠️ {{ $this->overdueCount }} Peminjaman Terlambat!
                </h3>
                <p class="text-sm text-danger-600 dark:text-danger-400 mt-1">
                    Potensi denda: Rp {{ number_format($this->totalOverdueFees, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <x-filament::button
                    tag="a"
                    href="{{ route('filament.admin.resources.borrowings.index', ['tableFilters[status][value]' => 'overdue']) }}"
                    color="danger"
                    size="sm"
                >
                    Lihat Detail
                </x-filament::button>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
