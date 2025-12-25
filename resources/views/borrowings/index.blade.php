<x-app-layout>
    @section('title', 'Riwayat Peminjaman')
    @section('meta_description', 'Pantau status dan jejak literasi Anda di Sukabaca.')

    <main class="pt-28 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header & Search --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4 animate-fade-in-up">
                <div>
                    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                        <a class="hover:text-primary" href="{{ route('dashboard') }}">Dashboard</a>
                        <span class="material-symbols-rounded text-xs">chevron_right</span>
                        <span class="text-primary font-semibold">Riwayat</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white leading-tight">
                        Riwayat Peminjaman
                    </h1>
                    <p class="text-gray-500 mt-2">
                        Pantau status dan jejak literasi Anda di sini.
                    </p>
                </div>
                <div class="w-full md:w-auto">
                    <form action="{{ route('borrowings.index') }}" method="GET" class="relative group">
                        @if(request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400">search</span>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="w-full md:w-64 pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-white/10 bg-white dark:bg-surface-dark focus:ring-2 focus:ring-secondary-accent/50 focus:border-secondary-accent text-sm transition-all" 
                            placeholder="Cari kode atau judul...">
                    </form>
                </div>
            </div>

            {{-- Filter Tabs --}}
            <div class="mb-8 overflow-x-auto hide-scrollbar pb-2">
                <div class="flex space-x-2 min-w-max">
                    <a href="{{ route('borrowings.index', ['status' => 'all']) }}" 
                        class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $currentStatus == 'all' ? 'bg-primary text-white shadow-lg shadow-primary/20 hover:scale-105' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:border-primary hover:text-primary hover:-translate-y-0.5' }}">
                        Semua <span class="ml-1 opacity-70">({{ $counts['all'] }})</span>
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'pending']) }}" 
                       class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $currentStatus == 'pending' ? 'bg-yellow-500 text-white shadow-lg shadow-yellow-500/20 hover:scale-105' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:border-yellow-400 hover:text-yellow-600 hover:-translate-y-0.5' }}">
                        <span class="flex items-center gap-2">
                            Pending
                            <span class="w-2 h-2 rounded-full {{ $currentStatus == 'pending' ? 'bg-white' : 'bg-yellow-400' }}"></span>
                            <span class="opacity-70">({{ $counts['pending'] }})</span>
                        </span>
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'active']) }}" 
                       class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $currentStatus == 'active' ? 'bg-green-600 text-white shadow-lg shadow-green-600/20 hover:scale-105' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:border-green-500 hover:text-green-600 hover:-translate-y-0.5' }}">
                        <span class="flex items-center gap-2">
                            Aktif
                            <span class="w-2 h-2 rounded-full {{ $currentStatus == 'active' ? 'bg-white' : 'bg-green-500' }}"></span>
                            <span class="opacity-70">({{ $counts['active'] }})</span>
                        </span>
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'returned']) }}" 
                       class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $currentStatus == 'returned' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20 hover:scale-105' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:border-blue-500 hover:text-blue-600 hover:-translate-y-0.5' }}">
                        Dikembalikan <span class="ml-1 opacity-70">({{ $counts['returned'] }})</span>
                    </a>
                    <a href="{{ route('borrowings.index', ['status' => 'overdue']) }}" 
                       class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all {{ $currentStatus == 'overdue' ? 'bg-red-600 text-white shadow-lg shadow-red-600/20 hover:scale-105' : 'bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 text-gray-600 dark:text-gray-300 hover:border-red-500 hover:text-red-600 hover:-translate-y-0.5' }}">
                        <span class="flex items-center gap-2">
                            Terlambat
                            <span class="w-2 h-2 rounded-full {{ $currentStatus == 'overdue' ? 'bg-white' : 'bg-red-500 animate-pulse' }}"></span>
                             <span class="opacity-70">({{ $counts['overdue'] }})</span>
                        </span>
                    </a>
                </div>
            </div>

            {{-- Borrowing List --}}
            <div class="space-y-6">
                @forelse($borrowings as $borrowing)
                    @php
                        $isOverdue = $borrowing->status == 'overdue' || ($borrowing->status == 'active' && $borrowing->due_date < now());
                        $book = $borrowing->bookCopy->book;
                    @endphp
                    
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-4 sm:p-5 border {{ $isOverdue ? 'border-red-100 dark:border-red-900/30' : 'border-gray-100 dark:border-white/5' }} hover:shadow-soft transition-all duration-300 group relative overflow-hidden">
                        @if($isOverdue)
                            <div class="absolute left-0 top-0 w-1 h-full bg-red-500"></div>
                        @elseif($borrowing->status == 'pending')
                            <div class="absolute left-0 top-0 w-1 h-full bg-yellow-400"></div>
                        @endif

                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- Book Image --}}
                            <div class="w-full md:w-32 h-48 md:h-40 shrink-0 rounded-xl overflow-hidden relative shadow-md">
                                <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('placeholder-book.jpg') }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 {{ $borrowing->status == 'returned' ? 'grayscale group-hover:grayscale-0' : '' }}">
                                @if($isOverdue)
                                    <div class="absolute inset-0 bg-red-500/10 mix-blend-multiply"></div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="flex flex-wrap justify-between items-start gap-2 mb-2">
                                        <span class="text-xs font-mono text-gray-400 bg-gray-50 dark:bg-white/5 px-2 py-1 rounded">{{ $borrowing->borrowing_code }}</span>
                                        
                                        @if($isOverdue)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                <span class="material-symbols-rounded text-sm mr-1">warning</span> Terlambat
                                            </span>
                                        @elseif($borrowing->status == 'pending')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($borrowing->status == 'active')
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                Aktif
                                            </span>
                                        @elseif($borrowing->status == 'returned')
                                             <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                                Dikembalikan
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <h3 class="font-bold text-lg md:text-xl text-primary dark:text-white mb-1 group-hover:text-secondary-accent transition-colors">
                                        {{ $book->title }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $book->author }}</p>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm mb-4">
                                        <div>
                                            <p class="text-gray-400 text-xs mb-0.5">Tanggal Pinjam</p>
                                            <p class="font-medium text-gray-700 dark:text-gray-200">{{ $borrowing->created_at->translatedFormat('d M Y') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-xs mb-0.5">
                                                {{ $borrowing->status == 'returned' ? 'Dikembalikan' : 'Jatuh Tempo' }}
                                            </p>
                                            <p class="font-bold {{ $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-primary dark:text-white' }}">
                                                {{ $borrowing->status == 'returned' && $borrowing->returned_at ? $borrowing->returned_at->translatedFormat('d M Y') : $borrowing->due_date->translatedFormat('d M Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-xs mb-0.5">Biaya Total</p>
                                            <p class="font-medium text-gray-700 dark:text-gray-200">Rp {{ number_format($borrowing->total_fee, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400 text-xs mb-0.5">Status Bayar</p>
                                            @if($borrowing->is_paid)
                                                <span class="text-xs font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-0.5 rounded">Lunas</span>
                                            @else
                                                <span class="text-xs font-bold text-red-500 bg-red-50 dark:bg-red-900/20 px-2 py-0.5 rounded">Belum Lunas</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Actions --}}
                                @if($isOverdue && !$borrowing->returned_at)
                                    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100 dark:border-white/5">
                                        <div class="flex items-center gap-2 text-red-600 text-sm font-medium">
                                            <span class="material-symbols-rounded">payments</span>
                                            <span>Denda aktif: Rp {{ number_format($borrowing->late_fee, 0, ',', '.') }}</span>
                                        </div>
                                        {{-- Pay & Return Button (Future) --}}
                                        <button class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-red-200 dark:shadow-none flex items-center gap-2">
                                            <span>Bayar & Kembalikan</span>
                                            <span class="material-symbols-rounded text-lg">arrow_forward</span>
                                        </button>
                                    </div>
                                @elseif($borrowing->status == 'active')
                                    <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100 dark:border-white/5">
                                        <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 text-sm font-medium bg-blue-50 dark:bg-blue-900/10 px-3 py-1 rounded-lg">
                                            <span class="material-symbols-rounded text-lg">timer</span>
                                            <span>Sisa waktu: {{ $borrowing->due_date->diffInDays(now()) }} Hari</span>
                                        </div>
                                        {{-- Extend Button (Future) --}}
                                        <button class="px-5 py-2 bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-secondary-accent text-primary dark:text-white text-sm font-bold rounded-xl transition-all shadow-sm hover:shadow flex items-center gap-2 group/btn">
                                            <span class="material-symbols-rounded text-secondary-accent group-hover/btn:rotate-180 transition-transform duration-500">update</span>
                                            Perpanjang Sewa
                                        </button>
                                    </div>
                                @elseif($borrowing->status == 'returned')
                                     <div class="flex flex-wrap items-center justify-end gap-4 pt-4 border-t border-gray-200 dark:border-white/5">
                                        <a href="{{ route('catalog.show', $book) }}" class="px-4 py-2 bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 hover:bg-gray-50 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300 text-xs font-bold rounded-lg transition-all">
                                            Pinjam Lagi
                                        </a>
                                    </div>
                                @elseif(!$borrowing->is_paid && $borrowing->total_fee > 0)
                                     <div class="flex flex-wrap items-center justify-between gap-4 pt-4 border-t border-gray-100 dark:border-white/5">
                                        <p class="text-xs text-gray-500 italic">Menunggu pembayaran untuk diproses.</p>
                                        <button class="px-5 py-2 bg-secondary-accent hover:bg-yellow-400 text-primary-dark font-bold text-sm rounded-xl transition-all shadow-glow flex items-center gap-2">
                                            <span>Bayar Sekarang</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-8 text-center border border-dashed border-gray-200 dark:border-white/10">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-rounded text-4xl text-gray-400">history_edu</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Belum ada riwayat peminjaman</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-6">Mulai petualangan membacamu dengan meminjam buku dari katalog kami.</p>
                        <a href="{{ route('catalog.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-light text-white font-medium rounded-xl transition-colors">
                            <span class="material-symbols-rounded">menu_book</span>
                            Jelajahi Katalog
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8">
                {{ $borrowings->links() }}
            </div>
        </div>
    </main>
</x-app-layout>
