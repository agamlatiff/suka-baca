<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2" style="display: flex; align-items: center; gap: 0.5rem;">
                <div style="width: 20px; height: 20px;">
                    <x-heroicon-o-bolt class="text-primary-500" style="width: 100%; height: 100%; color: var(--primary-500);" />
                </div>
                <span>Quick Actions</span>
            </div>
        </x-slot>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            {{-- Pending Borrowings --}}
            <a href="{{ route('filament.admin.resources.borrowings.index', ['tableFilters' => ['status' => ['value' => 'pending']]]) }}" 
               style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 0.75rem; border: 1px solid #fcd34d; background: linear-gradient(to bottom right, #fffbeb, #fef3c7); transition: all 0.3s;"
               class="group hover:shadow-lg">
                <div style="padding: 0.75rem; background-color: #f59e0b; border-radius: 0.5rem; color: white;">
                    <div style="width: 24px; height: 24px;">
                        <x-heroicon-o-clock style="width: 100%; height: 100%;" />
                    </div>
                </div>
                <div>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #d97706; margin: 0;">{{ $this->getPendingBorrowingsCount() }}</p>
                    <p style="font-size: 0.875rem; color: #b45309; margin: 0;">Peminjaman Pending</p>
                </div>
                <div style="position: absolute; right: 1rem;">
                    <div style="width: 20px; height: 20px;">
                        <x-heroicon-o-arrow-right style="width: 100%; height: 100%; color: #fbbf24;" />
                    </div>
                </div>
            </a>

            {{-- Pending Payments --}}
            <a href="{{ route('filament.admin.resources.payments.index', ['tableFilters' => ['status' => ['value' => 'pending']]]) }}"
               style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 0.75rem; border: 1px solid #93c5fd; background: linear-gradient(to bottom right, #eff6ff, #dbeafe); transition: all 0.3s;"
               class="group hover:shadow-lg">
                <div style="padding: 0.75rem; background-color: #3b82f6; border-radius: 0.5rem; color: white;">
                    <div style="width: 24px; height: 24px;">
                        <x-heroicon-o-banknotes style="width: 100%; height: 100%;" />
                    </div>
                </div>
                <div>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #2563eb; margin: 0;">{{ $this->getPendingPaymentsCount() }}</p>
                    <p style="font-size: 0.875rem; color: #1d4ed8; margin: 0;">Pembayaran Pending</p>
                </div>
                <div style="position: absolute; right: 1rem;">
                    <div style="width: 20px; height: 20px;">
                        <x-heroicon-o-arrow-right style="width: 100%; height: 100%; color: #60a5fa;" />
                    </div>
                </div>
            </a>

            {{-- Overdue --}}
            <a href="{{ route('filament.admin.resources.borrowings.index', ['tableFilters' => ['status' => ['value' => 'overdue']]]) }}"
               style="display: flex; align-items: center; gap: 1rem; padding: 1rem; border-radius: 0.75rem; border: 1px solid #fca5a5; background: linear-gradient(to bottom right, #fef2f2, #fee2e2); transition: all 0.3s;"
               class="group hover:shadow-lg">
                <div style="padding: 0.75rem; background-color: #ef4444; border-radius: 0.5rem; color: white;">
                    <div style="width: 24px; height: 24px;">
                        <x-heroicon-o-exclamation-triangle style="width: 100%; height: 100%;" />
                    </div>
                </div>
                <div>
                    <p style="font-size: 1.5rem; font-weight: 700; color: #dc2626; margin: 0;">{{ $this->getOverdueCount() }}</p>
                    <p style="font-size: 0.875rem; color: #b91c1c; margin: 0;">Terlambat</p>
                </div>
                <div style="position: absolute; right: 1rem;">
                    <div style="width: 20px; height: 20px;">
                        <x-heroicon-o-arrow-right style="width: 100%; height: 100%; color: #f87171;" />
                    </div>
                </div>
            </a>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
