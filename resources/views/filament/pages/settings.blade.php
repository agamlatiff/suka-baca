<x-filament-panels::page>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Library Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/20 dark:to-primary-800/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary-500 rounded-lg">
                        <x-heroicon-o-building-library class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Perpustakaan</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Informasi dasar perpustakaan</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama Perpustakaan</label>
                    <input type="text" wire:model="librarySettings.library_name" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Alamat</label>
                    <input type="text" wire:model="librarySettings.library_address" 
                           class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="pt-2">
                    <button wire:click="saveLibrarySettings" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <x-heroicon-o-check class="w-4 h-4 mr-2" />
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        {{-- Borrowing Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-500 rounded-lg">
                        <x-heroicon-o-book-open class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Peminjaman</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Aturan peminjaman buku</p>
                    </div>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maks Hari Pinjam</label>
                        <input type="number" wire:model="borrowingSettings.max_borrow_days" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maks Buku/User</label>
                        <input type="number" wire:model="borrowingSettings.max_books_per_user" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Maks Perpanjangan</label>
                        <input type="number" wire:model="borrowingSettings.max_extensions" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hari Perpanjangan</label>
                        <input type="number" wire:model="borrowingSettings.extension_days" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <div class="pt-2">
                    <button wire:click="saveBorrowingSettings" class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <x-heroicon-o-check class="w-4 h-4 mr-2" />
                        Simpan Pengaturan
                    </button>
                </div>
            </div>
        </div>

        {{-- Fee Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-500 rounded-lg">
                        <x-heroicon-o-banknotes class="w-5 h-5 text-white" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Biaya & Denda</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pengaturan biaya sewa dan denda</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Biaya Sewa Default (Rp)</label>
                        <input type="number" wire:model="feeSettings.default_rental_fee" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Denda Per Hari (Rp)</label>
                        <input type="number" wire:model="feeSettings.late_fee_per_day" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Denda Kerusakan (%)</label>
                        <input type="number" wire:model="feeSettings.damage_fee_percentage" 
                               class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                </div>
                <div class="pt-4">
                    <button wire:click="saveFeeSettings" class="inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <x-heroicon-o-check class="w-4 h-4 mr-2" />
                        Simpan Pengaturan Biaya
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
