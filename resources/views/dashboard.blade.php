<x-app-layout>
    @section('title', 'Dashboard')
    @section('meta_description', 'Dashboard user Sukabaca. Lihat status peminjaman, tagihan, dan lainnya.')

    <main class="pt-28 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 flex items-center gap-3">
                    <span class="material-symbols-rounded">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Welcome Section --}}
            <div class="flex flex-col md:flex-row justify-between items-end mb-8 gap-4 animate-fade-in-up">
                <div>
                    <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white leading-tight">
                        Selamat datang, {{ explode(' ', $user->name)[0] }}! <span class="inline-block animate-bounce">👋</span>
                    </h1>
                    <p class="text-gray-500 mt-2 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg text-secondary-accent">calendar_month</span>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </p>
                </div>
                <div class="hidden md:block">
                    <a href="{{ route('profile.edit') }}" class="text-sm text-primary dark:text-white font-medium hover:underline flex items-center gap-1">
                        <span class="material-symbols-rounded">settings</span> Pengaturan Akun
                    </a>
                </div>
            </div>

            {{-- Alerts Section --}}
            <div class="space-y-4 mb-10">
                @if($overdueBorrowings->isNotEmpty())
                    @foreach($overdueBorrowings as $borrowing)
                        <div class="bg-red-50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
                            <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-xl text-red-600 shrink-0">
                                <span class="material-symbols-rounded">warning</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-red-800 dark:text-red-300">Keterlambatan Pengembalian</h3>
                                <p class="text-sm text-red-600 dark:text-red-400 mt-1">
                                    Buku "{{ $borrowing->bookCopy->book->title }}" terlambat {{ $borrowing->days_late }} hari. 
                                    Denda berjalan saat ini: <span class="font-bold">Rp {{ number_format($borrowing->late_fee, 0, ',', '.') }}</span>. 
                                    Mohon segera dikembalikan atau diperpanjang.
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if($unpaidBorrowings->isNotEmpty())
                    <div class="bg-yellow-50 dark:bg-yellow-900/10 border border-yellow-100 dark:border-yellow-900/30 rounded-2xl p-4 flex items-start gap-4 shadow-sm">
                        <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl text-yellow-700 shrink-0">
                            <span class="material-symbols-rounded">receipt_long</span>
                        </div>
                        <div class="flex-1 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                            <div>
                                <h3 class="font-bold text-yellow-800 dark:text-yellow-300">Tagihan Pending</h3>
                                <p class="text-sm text-yellow-700 dark:text-yellow-400 mt-1">
                                    Anda memiliki tagihan sewa yang belum dibayar sebesar Rp {{ number_format($stats['total_outstanding_fees'], 0, ',', '.') }}.
                                </p>
                            </div>
                            {{-- Placeholder for Pay Now Button or Link --}}
                            <a href="#" class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold rounded-lg transition-colors whitespace-nowrap">
                                Bayar Sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                {{-- Active Borrowings --}}
                <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:translate-y-[-2px] transition-transform duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-green-50 dark:bg-green-900/20 text-green-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-2xl">menu_book</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Sedang Dipinjam</p>
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $stats['active_count'] }} <span class="text-sm font-normal text-gray-400">Buku</span></p>
                    </div>
                </div>

                {{-- Total Borrowed --}}
                <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:translate-y-[-2px] transition-transform duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-900/20 text-blue-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-2xl">history_edu</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Dipinjam</p>
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $stats['total_borrowed'] }}</p>
                    </div>
                </div>

                {{-- Wishlist --}}
                <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-white/5 flex items-center gap-4 hover:translate-y-[-2px] transition-transform duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-pink-50 dark:bg-pink-900/20 text-pink-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-2xl">favorite</span>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Wishlist</p>
                        <p class="text-2xl font-bold text-primary dark:text-white">{{ $stats['wishlist_count'] }}</p>
                    </div>
                </div>

                {{-- Outstanding Bill --}}
                <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 shadow-soft border border-gray-100 dark:border-white/5 flex items-center gap-4 relative overflow-hidden hover:translate-y-[-2px] transition-transform duration-300">
                    <div class="absolute right-0 top-0 w-20 h-20 bg-orange-500/5 rounded-bl-[3rem]"></div>
                    <div class="w-14 h-14 rounded-2xl bg-orange-50 dark:bg-orange-900/20 text-orange-600 flex items-center justify-center shrink-0 relative z-10">
                        <span class="material-symbols-rounded text-2xl">account_balance_wallet</span>
                    </div>
                    <div class="relative z-10">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tagihan</p>
                        <p class="text-2xl font-bold {{ $stats['total_outstanding_fees'] > 0 ? 'text-red-600' : 'text-primary dark:text-white' }}">
                            Rp {{ number_format($stats['total_outstanding_fees'], 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Main Content Grid --}}
            <div class="grid lg:grid-cols-3 gap-8">
                
                {{-- Active Loans Column --}}
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white dark:bg-surface-dark rounded-3xl p-6 md:p-8 shadow-soft border border-gray-100 dark:border-white/5">
                        <div class="flex flex-wrap justify-between items-center mb-6 gap-4">
                            <h2 class="text-xl font-bold text-primary dark:text-white flex items-center gap-2">
                                <span class="material-symbols-rounded text-secondary-accent">auto_stories</span>
                                Pinjaman Aktif
                            </h2>
                            <a href="{{ route('borrowings.index') }}" class="text-sm font-semibold text-primary dark:text-white hover:text-secondary-accent transition-colors flex items-center gap-1">
                                Lihat Semua <span class="material-symbols-rounded text-lg">arrow_forward</span>
                            </a>
                        </div>

                        <div class="space-y-4">
                            @forelse($activeBorrowings as $borrowing)
                                <div class="flex flex-col sm:flex-row gap-5 p-4 rounded-2xl border 
                                    {{ $borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()) 
                                        ? 'border-red-100 dark:border-red-900/30 bg-red-50/40 dark:bg-red-900/10 hover:bg-red-50/80 dark:hover:bg-red-900/20' 
                                        : 'border-gray-100 dark:border-white/5 bg-gray-50/50 dark:bg-white/5 hover:bg-gray-50 dark:hover:bg-white/10' 
                                    }} transition-colors group">
                                    
                                    <div class="w-full sm:w-24 h-36 shrink-0 rounded-xl overflow-hidden shadow-md relative">
                                        <img src="{{ $borrowing->bookCopy->book->image ? asset('storage/' . $borrowing->bookCopy->book->image) : asset('placeholder-book.jpg') }}" 
                                            alt="{{ $borrowing->bookCopy->book->title }}" 
                                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 {{ $borrowing->is_overdue ? 'grayscale group-hover:grayscale-0' : '' }}">
                                        @if($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()))
                                            <div class="absolute inset-0 bg-red-500/10 mix-blend-multiply"></div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0 flex flex-col justify-between py-1">
                                        <div>
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="font-bold text-lg text-gray-900 dark:text-white truncate pr-2">{{ $borrowing->bookCopy->book->title }}</h3>
                                                @if($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()))
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400 border border-red-200 dark:border-red-800">
                                                        Terlambat
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-200 dark:border-green-800">
                                                        Aktif
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $borrowing->bookCopy->book->author }}</p>
                                            
                                            <div class="flex flex-wrap gap-x-6 gap-y-2 text-sm text-gray-600 dark:text-gray-300 mb-4">
                                                <div class="flex items-center gap-1.5">
                                                    @if($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()))
                                                        <span class="material-symbols-rounded text-lg text-red-600">event_busy</span>
                                                        <span>Jatuh Tempo: <span class="font-semibold text-red-600">{{ $borrowing->due_date->translatedFormat('d M Y') }}</span></span>
                                                    @else
                                                        <span class="material-symbols-rounded text-lg text-gray-400">event</span>
                                                        <span>Jatuh Tempo: <span class="font-semibold text-primary dark:text-white">{{ $borrowing->due_date->translatedFormat('d M Y') }}</span></span>
                                                    @endif
                                                </div>
                                                @if(!($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now())) && $borrowing->due_date->diffInDays(now()) <= 3)
                                                    <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400 font-medium">
                                                        <span class="material-symbols-rounded text-lg">timer</span>
                                                        <span>Sisa {{ $borrowing->due_date->diffInDays(now()) }} Hari</span>
                                                    </div>
                                                @endif
                                                @if($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()))
                                                     <div class="flex items-center gap-1.5 text-red-600 dark:text-red-400 font-semibold">
                                                        <span class="material-symbols-rounded text-lg">warning</span>
                                                        <span>Telat {{ $borrowing->days_late ?? $borrowing->due_date->diffInDays(now()) }} Hari</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between border-t border-gray-100 dark:border-white/5 pt-3 mt-1">
                                            @if($borrowing->is_overdue || ($borrowing->status == 'active' && $borrowing->due_date < now()))
                                                <span class="text-xs font-bold text-red-600 dark:text-red-400">Denda: Rp {{ number_format($borrowing->late_fee, 0, ',', '.') }}</span>
                                                <button class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all shadow-sm shadow-red-200 dark:shadow-none flex items-center gap-1">
                                                    Bayar & Kembalikan
                                                </button>
                                            @else
                                                <span class="text-xs text-gray-400">Biaya: Rp {{ number_format($borrowing->rental_price, 0, ',', '.') }}</span>
                                                {{-- Extend Button (could be form) --}}
                                                <button class="bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 hover:border-secondary-accent text-primary dark:text-white text-xs font-bold px-4 py-2 rounded-lg transition-all shadow-sm hover:shadow flex items-center gap-1">
                                                    <span class="material-symbols-rounded text-sm text-secondary-accent">update</span>
                                                    Perpanjang
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-gray-50/50 dark:bg-white/5 rounded-2xl border border-dashed border-gray-200 dark:border-white/10">
                                    <div class="w-16 h-16 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <span class="material-symbols-rounded text-3xl text-gray-400">menu_book</span>
                                    </div>
                                    <h3 class="font-bold text-gray-900 dark:text-white">Tidak ada pinjaman aktif</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Mulai meminjam buku favorit Anda sekarang</p>
                                    <a href="{{ route('catalog.index') }}" class="text-sm font-bold text-secondary-accent hover:underline">Jelajahi Katalog</a>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Sidebar Column --}}
                <div class="lg:col-span-1 space-y-8">
                    {{-- Total Bill Card --}}
                    @if($stats['total_outstanding_fees'] > 0)
                        <div class="bg-primary dark:bg-[#1a2e24] rounded-3xl p-6 text-white relative overflow-hidden shadow-xl shadow-primary/20 group">
                            <div class="absolute -top-10 -right-10 w-40 h-40 bg-secondary-accent/20 rounded-full blur-3xl group-hover:bg-secondary-accent/30 transition-colors"></div>
                            <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black/20 to-transparent"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-2 mb-6 opacity-90">
                                    <span class="material-symbols-rounded bg-white/20 p-1.5 rounded-lg">account_balance_wallet</span>
                                    <span class="text-sm font-bold tracking-wide">TOTAL TAGIHAN</span>
                                </div>
                                <div class="mb-8">
                                    <h3 class="text-4xl font-extrabold mb-1 tracking-tight">Rp {{ number_format($stats['total_outstanding_fees'], 0, ',', '.') }}</h3>
                                    <div class="flex items-center gap-2 text-xs text-white/70">
                                        <span class="w-2 h-2 rounded-full bg-red-400 animate-pulse"></span>
                                        Belum dibayar
                                    </div>
                                </div>
                                <button class="w-full bg-secondary-accent hover:bg-yellow-400 text-primary-dark font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-black/20 flex items-center justify-center gap-2 group-hover:scale-[1.02]">
                                    Bayar Sekarang
                                    <span class="material-symbols-rounded text-lg">arrow_forward</span>
                                </button>
                                <div class="mt-4 text-center">
                                    <a href="#" class="text-xs text-white/60 hover:text-white underline decoration-white/30 transition-colors">Lihat rincian tagihan</a>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Quick Actions --}}
                    <div class="bg-white dark:bg-surface-dark rounded-3xl p-6 shadow-soft border border-gray-100 dark:border-white/5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Aksi Cepat</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <a href="{{ route('catalog.index') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-green-50 dark:hover:bg-green-900/10 border border-transparent hover:border-green-200 dark:hover:border-green-900/30 transition-all group h-32">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-surface-dark text-green-600 shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded">search</span>
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-green-700 dark:group-hover:text-green-400">Katalog</span>
                            </a>
                            <a href="{{ route('wishlist.index') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-pink-50 dark:hover:bg-pink-900/10 border border-transparent hover:border-pink-200 dark:hover:border-pink-900/30 transition-all group h-32">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-surface-dark text-pink-600 shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded">favorite</span>
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-pink-700 dark:group-hover:text-pink-400">Wishlist</span>
                            </a>
                            <a href="{{ route('borrowings.index') }}" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-blue-50 dark:hover:bg-blue-900/10 border border-transparent hover:border-blue-200 dark:hover:border-blue-900/30 transition-all group h-32">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-surface-dark text-blue-600 shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded">history</span>
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-blue-700 dark:group-hover:text-blue-400">Riwayat</span>
                            </a>
                            <a href="#" class="flex flex-col items-center justify-center p-4 rounded-2xl bg-gray-50 dark:bg-white/5 hover:bg-orange-50 dark:hover:bg-orange-900/10 border border-transparent hover:border-orange-200 dark:hover:border-orange-900/30 transition-all group h-32">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-surface-dark text-orange-600 shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-rounded">support_agent</span>
                                </div>
                                <span class="text-xs font-bold text-gray-700 dark:text-gray-300 group-hover:text-orange-700 dark:group-hover:text-orange-400">Bantuan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
