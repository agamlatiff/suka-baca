<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center gap-x-3 p-4 bg-danger-50 dark:bg-danger-950 rounded-xl border border-danger-200 dark:border-danger-800"
             style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 0.75rem;">
            <div class="flex-shrink-0" style="flex-shrink: 0;">
                <x-heroicon-o-exclamation-triangle class="text-danger-600 dark:text-danger-400"
                    style="width: 1.5rem; height: 1.5rem; color: #dc2626;" />
            </div>
            <div class="flex-1" style="flex: 1;">
                <h3 class="text-lg font-semibold text-danger-800 dark:text-danger-200"
                    style="font-size: 1.125rem; font-weight: 600; color: #991b1b; margin: 0;">
                    ⚠️ {{ $this->overdueCount }} Peminjaman Terlambat!
                </h3>
                <p class="text-sm text-danger-600 dark:text-danger-400 mt-1"
                   style="font-size: 0.875rem; color: #dc2626; margin-top: 0.25rem; margin-bottom: 0;">
                    Potensi denda: Rp {{ number_format($this->totalOverdueFees, 0, ',', '.') }}
                </p>
            </div>
            <div class="flex-shrink-0" style="flex-shrink: 0;">
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
