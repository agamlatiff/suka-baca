<x-filament-panels::page>
    {{-- Period Filter --}}
    <div class="mb-6 flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dari Tanggal</label>
            <input type="date" wire:model.live="startDate" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sampai Tanggal</label>
            <input type="date" wire:model.live="endDate" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm">
        </div>
        <div wire:loading class="flex items-center gap-2 text-sm text-primary-600">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Memuat...
        </div>
    </div>

    {{-- Tabs --}}
    <div x-data="{ activeTab: @entangle('activeTab') }" class="space-y-6">
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button @click="activeTab = 'popular-books'" :class="activeTab === 'popular-books' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📚 Buku Terpopuler
                </button>
                <button @click="activeTab = 'active-borrowers'" :class="activeTab === 'active-borrowers' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    👥 Peminjam Teraktif
                </button>
                <button @click="activeTab = 'revenue'" :class="activeTab === 'revenue' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    💰 Revenue
                </button>
                <button @click="activeTab = 'borrowings'" :class="activeTab === 'borrowings' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    📋 Laporan Peminjaman
                </button>
            </nav>
        </div>

        {{-- Tab: Popular Books --}}
        <div x-show="activeTab === 'popular-books'" x-cloak wire:loading.class="opacity-50">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 10 Buku Terpopuler</h3>
                        <p class="text-sm text-gray-500">Berdasarkan jumlah peminjaman</p>
                    </div>
                    <button wire:click="exportPopularBooks" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export
                    </button>
                </div>
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Buku</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($this->getPopularBooks() as $index => $book)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $book['title'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $book['author'] }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold text-primary-600">{{ $book['count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab: Active Borrowers --}}
        <div x-show="activeTab === 'active-borrowers'" x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Top 10 Peminjam Teraktif</h3>
                        <p class="text-sm text-gray-500">Berdasarkan jumlah peminjaman dalam periode</p>
                    </div>
                    <button wire:click="exportActiveBorrowers" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export
                    </button>
                </div>
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pinjam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($this->getActiveBorrowers() as $index => $user)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user['name'] }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400">{{ $user['email'] }}</td>
                                    <td class="px-4 py-3 text-sm text-right font-semibold text-primary-600">{{ $user['count'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-500">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Tab: Revenue --}}
        <div x-show="activeTab === 'revenue'" x-cloak>
            {{-- Stats Cards --}}
            @php $revenueStats = $this->getRevenueStats(); @endphp
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <p class="text-sm text-gray-500">Revenue Hari Ini</p>
                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($revenueStats['today'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <p class="text-sm text-gray-500">Revenue Minggu Ini</p>
                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($revenueStats['week'], 0, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <p class="text-sm text-gray-500">Revenue Bulan Ini</p>
                    <p class="text-2xl font-bold text-purple-600">Rp {{ number_format($revenueStats['month'], 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Revenue Chart --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Revenue 12 Bulan Terakhir</h3>
                    <button wire:click="exportRevenue" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Export
                    </button>
                </div>
                <div class="p-6">
                    @php $revenueData = $this->getRevenueData(); @endphp
                    <div class="h-64 flex items-end justify-between gap-2">
                        @foreach($revenueData as $data)
                            @php
                                $maxRevenue = max(array_column($revenueData, 'revenue')) ?: 1;
                                $height = ($data['revenue'] / $maxRevenue) * 100;
                            @endphp
                            <div class="flex-1 flex flex-col items-center">
                                <div class="w-full bg-primary-500 rounded-t transition-all duration-300" style="height: {{ max($height, 2) }}%"></div>
                                <span class="text-xs text-gray-500 mt-2 text-center">{{ Str::limit($data['month'], 3, '') }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Borrowings Report --}}
        <div x-show="activeTab === 'borrowings'" x-cloak>
            {{-- Stats --}}
            @php $stats = $this->getBorrowingStats(); @endphp
            <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total'] }}</p>
                    <p class="text-xs text-gray-500">Total</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-2xl font-bold text-blue-600">{{ $stats['active'] }}</p>
                    <p class="text-xs text-gray-500">Aktif</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-2xl font-bold text-green-600">{{ $stats['returned'] }}</p>
                    <p class="text-xs text-gray-500">Dikembalikan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
                    <p class="text-xs text-gray-500">Terlambat</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-lg font-bold text-gray-900 dark:text-white">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Total Pendapatan</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 text-center">
                    <p class="text-lg font-bold text-orange-600">Rp {{ number_format($stats['total_late_fee'], 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500">Total Denda</p>
                </div>
            </div>
            {{-- Export Button --}}
            <div class="flex justify-end mb-4">
                <button wire:click="exportBorrowings" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export Excel
                </button>
            </div>

            {{-- Borrowings Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                {{ $this->table }}
            </div>
        </div>
    </div>
</x-filament-panels::page>
