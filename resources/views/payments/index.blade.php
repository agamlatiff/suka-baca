<x-app-layout>
    @section('title', 'Riwayat Pembayaran')
    @section('meta_description', 'Kelola dan pantau semua transaksi peminjaman buku Anda di Sukabaca.')

    <main class="pt-28 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white leading-tight">
                        Riwayat Pembayaran
                    </h1>
                    <p class="text-gray-500 mt-2 flex items-center gap-2">
                        Kelola dan pantau semua transaksi peminjaman bukumu.
                    </p>
                </div>
                <div class="hidden md:flex items-center gap-2 text-sm text-gray-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-primary">Dashboard</a>
                    <span class="material-symbols-rounded text-base">chevron_right</span>
                    <span class="font-semibold text-primary dark:text-white">Riwayat Pembayaran</span>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2 space-y-6">
                    {{-- Filter Tabs --}}
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-2 shadow-sm border border-gray-100 dark:border-white/5 inline-flex flex-wrap gap-1 w-full md:w-auto">
                        <a href="{{ route('payments.index', ['status' => 'all']) }}" 
                           class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 {{ $currentStatus == 'all' ? 'bg-primary text-white shadow-md shadow-primary/20' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white' }}">
                            Semua
                        </a>
                        <a href="{{ route('payments.index', ['status' => 'pending']) }}" 
                           class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2 {{ $currentStatus == 'pending' ? 'bg-yellow-50 dark:bg-yellow-900/20 text-yellow-700 dark:text-yellow-400 font-bold' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white' }}">
                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span> Pending
                        </a>
                        <a href="{{ route('payments.index', ['status' => 'verified']) }}" 
                           class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2 {{ $currentStatus == 'verified' ? 'bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 font-bold' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white' }}">
                            <span class="w-2 h-2 rounded-full bg-green-400"></span> Verified
                        </a>
                        <a href="{{ route('payments.index', ['status' => 'rejected']) }}" 
                           class="flex-1 md:flex-none px-6 py-2.5 rounded-xl text-sm font-medium transition-colors flex items-center justify-center gap-2 {{ $currentStatus == 'rejected' ? 'bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 font-bold' : 'text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white' }}">
                            <span class="w-2 h-2 rounded-full bg-red-400"></span> Rejected
                        </a>
                    </div>

                    {{-- Payment List --}}
                    <div class="space-y-4">
                        @forelse($payments as $payment)
                            @php
                                $statusColor = match($payment->status) {
                                    'pending' => 'yellow',
                                    'confirmed' => 'green',
                                    'rejected' => 'red',
                                    default => 'gray',
                                };
                                $statusIcon = match($payment->status) {
                                    'pending' => 'pending',
                                    'confirmed' => 'verified',
                                    'rejected' => 'highlight_off',
                                    default => 'help',
                                };
                                $bookTitle = $payment->borrowing->bookCopy ? $payment->borrowing->bookCopy->book->title : 'Pembayaran Peminjaman';
                            @endphp

                            <div class="bg-white dark:bg-surface-dark rounded-2xl p-5 shadow-soft border border-gray-100 dark:border-white/5 hover:border-{{ $statusColor }}-200 dark:hover:border-{{ $statusColor }}-900/30 transition-all group relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-{{ $statusColor == 'yellow' ? 'yellow-400' : ($statusColor == 'green' ? 'green-500' : ($statusColor == 'red' ? 'red-500' : 'gray-400')) }}"></div>
                                
                                <div class="flex flex-col md:flex-row gap-5 items-start md:items-center justify-between">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-{{ $statusColor }}-50 dark:bg-{{ $statusColor }}-900/20 text-{{ $statusColor }}-600 flex items-center justify-center shrink-0">
                                            <span class="material-symbols-rounded text-2xl">{{ $statusIcon }}</span>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <h3 class="font-bold text-gray-900 dark:text-white">Pembayaran Sewa</h3>
                                                <span class="text-xs text-gray-400">• #TRX-{{ $payment->id }}</span>
                                            </div>
                                            <p class="text-sm text-gray-500 mb-1">{{ $bookTitle }}</p>
                                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                                <span class="material-symbols-rounded text-sm">event</span> {{ $payment->created_at->translatedFormat('d M Y, H:i') }} WIB
                                            </p>
                                            @if($payment->status == 'rejected' && $payment->notes)
                                                <p class="text-xs text-red-500 mt-1 italic">Note: {{ $payment->notes }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-2 w-full md:w-auto pl-16 md:pl-0">
                                        <span class="text-lg font-bold {{ $payment->status == 'rejected' ? 'text-gray-400 dark:text-gray-500 line-through' : 'text-primary dark:text-white' }}">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </span>
                                        <div class="flex items-center gap-3">
                                            @if($payment->proof_file)
                                                <a href="{{ asset('storage/' . $payment->proof_file) }}" target="_blank" class="text-xs text-blue-600 hover:text-blue-700 font-medium flex items-center gap-1">
                                                    <span class="material-symbols-rounded text-sm">receipt</span> Bukti
                                                </a>
                                            @endif
                                            
                                            <span class="px-3 py-1 rounded-lg bg-{{ $statusColor }}-100 dark:bg-{{ $statusColor }}-900/30 text-{{ $statusColor }}-700 dark:text-{{ $statusColor }}-400 text-xs font-bold border border-{{ $statusColor }}-200 dark:border-{{ $statusColor }}-800 uppercase">
                                                {{ $payment->status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white dark:bg-surface-dark rounded-2xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-rounded text-4xl text-gray-400">receipt_long</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada riwayat pembayaran</h3>
                                <p class="text-gray-500 dark:text-gray-400">Semua transaksi pembayaranmu akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination --}}
                    <div class="mt-8">
                        {{ $payments->links() }}
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="lg:col-span-1 space-y-8 sticky top-28">
                    {{-- Total Bill Widget --}}
                    @if($totalOutstandingFees > 0)
                        <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-3xl p-6 text-white relative overflow-hidden shadow-xl shadow-red-500/20 group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2 opacity-90">
                                        <span class="material-symbols-rounded bg-white/20 p-1.5 rounded-lg">account_balance_wallet</span>
                                        <span class="text-sm font-bold tracking-wide">TOTAL TAGIHAN</span>
                                    </div>
                                    <span class="material-symbols-rounded animate-pulse">priority_high</span>
                                </div>
                                <div class="mb-8">
                                    <h3 class="text-4xl font-extrabold mb-1 tracking-tight">Rp {{ number_format($totalOutstandingFees, 0, ',', '.') }}</h3>
                                    <p class="text-xs text-white/80 mt-2">
                                        Total semua biaya peminjaman dan denda yang belum dibayar.
                                    </p>
                                </div>
                                <button class="w-full bg-white text-red-700 font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-black/10 flex items-center justify-center gap-2 hover:bg-gray-50 hover:scale-[1.02]">
                                    Lunasi Sekarang
                                    <span class="material-symbols-rounded text-lg">arrow_forward</span>
                                </button>
                            </div>
                        </div>
                    @endif

                    {{-- Payment methods info --}}
                    <div class="bg-white dark:bg-surface-dark rounded-3xl p-6 shadow-soft border border-gray-100 dark:border-white/5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5 flex items-center gap-2">
                            <span class="material-symbols-rounded text-secondary">payments</span>
                            Metode Pembayaran
                        </h3>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-surface-dark flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-rounded text-blue-600">account_balance</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">Bank Transfer</p>
                                    <p class="text-xs text-gray-500">BCA, Mandiri, BRI</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-white/5">
                                <div class="w-10 h-10 rounded-lg bg-white dark:bg-surface-dark flex items-center justify-center shadow-sm">
                                    <span class="material-symbols-rounded text-green-600">qr_code_2</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">QRIS</p>
                                    <p class="text-xs text-gray-500">Gopay, OVO, Dana</p>
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-white/5">
                                <p class="text-xs text-gray-400 text-center leading-relaxed">
                                    Konfirmasi pembayaran otomatis dalam 10-30 menit setelah upload bukti transfer.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Help Support --}}
                    <div class="bg-secondary-accent/10 rounded-3xl p-6 border border-secondary-accent/20 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-secondary-accent/20 text-secondary-accent flex items-center justify-center mb-3">
                            <span class="material-symbols-rounded text-2xl">support_agent</span>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">Butuh Bantuan?</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-4">Jika ada kendala pembayaran, hubungi CS kami.</p>
                        <button class="text-xs font-bold text-secondary-accent hover:underline">Hubungi CS</button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
