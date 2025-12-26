<x-app-layout>
    @section('title', $book->title)
    @section('meta_description', Str::limit($book->synopsis, 150))

    {{-- Header --}}
    <header class="relative pt-32 pb-24 bg-primary dark:bg-background-dark overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-[400px] h-[400px] bg-secondary-accent/10 rounded-full blur-[80px] mix-blend-overlay"></div>
            <div class="absolute bottom-0 left-0 w-full h-full bg-[radial-gradient(circle_at_0%_100%,_rgba(45,138,130,0.1),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-20 [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Detail Buku</h1>
            <nav aria-label="Breadcrumb" class="flex justify-center md:justify-start">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('home') }}" class="inline-flex items-center text-sm font-medium text-white/70 hover:text-white transition-colors">
                            <span class="material-symbols-rounded text-lg mr-2">home</span>
                            Beranda
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-white/50 mx-1">chevron_right</span>
                            <a href="{{ route('catalog.index') }}" class="text-sm font-medium text-white/70 hover:text-white transition-colors">Katalog</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-white/50 mx-1">chevron_right</span>
                            <span aria-current="page" class="text-sm font-medium text-secondary-accent truncate max-w-[150px] md:max-w-xs">{{ $book->title }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    <main class="relative z-20 -mt-10 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Main Content Card --}}
            <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-6 md:p-10 border border-gray-100 dark:border-white/5 mb-16">
                <div class="flex flex-col lg:flex-row gap-10 xl:gap-14">
                    {{-- Left Column: Image --}}
                    <div class="w-full lg:w-1/3 flex-shrink-0">
                        <div class="relative group mx-auto max-w-sm lg:max-w-none">
                            <div class="absolute inset-0 bg-primary/20 blur-2xl rounded-[2rem] transform rotate-3 scale-95 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            <div class="relative aspect-[2/3] rounded-2xl overflow-hidden shadow-2xl border border-gray-100 dark:border-white/10">
                                @if($book->image)
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400">
                                        <span class="material-symbols-rounded text-6xl opacity-30">menu_book</span>
                                    </div>
                                @endif
                                
                                <div class="absolute top-4 left-4">
                                    <span class="bg-secondary-accent text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-lg flex items-center gap-1">
                                        <span class="material-symbols-rounded text-sm">{{ $book->category->icon ?? 'star' }}</span>
                                        {{ $book->category->name }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Stats for Mobile (Optional) --}}
                        <div class="mt-6 flex justify-center gap-6 lg:hidden">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Tahun</p>
                                <p class="text-lg font-bold text-primary dark:text-white">{{ $book->publication_year }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-500 uppercase font-semibold">Stok</p>
                                <p class="text-lg font-bold text-primary dark:text-white">{{ $book->available_copies }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Details --}}
                    <div class="flex-1 flex flex-col">
                        <div class="mb-6">
                            <h2 class="text-3xl md:text-3xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">{{ $book->title }}</h2>
                            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-300 text-lg">
                                <span>Oleh</span>
                                <span class="font-semibold text-primary dark:text-primary-light">{{ $book->author }}</span>
                            </div>
                        </div>

                        {{-- Pricing and Status Box --}}
                        <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-5 md:p-6 mb-8 border border-gray-100 dark:border-white/5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Harga Sewa / Minggu</p>
                                <div class="flex items-baseline gap-1">
                                    <span class="text-3xl font-bold text-secondary-accent">Rp {{ number_format($book->rental_price, 0, ',', '.') }}</span>
                                    <span class="text-sm text-gray-400">/minggu</span>
                                </div>
                            </div>
                            <div class="h-px sm:h-12 w-full sm:w-px bg-gray-200 dark:bg-white/10"></div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Status Ketersediaan</p>
                                <div class="flex items-center gap-2">
                                    @if($book->available_copies > 0)
                                        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-green-600 bg-green-100 dark:text-green-300 dark:bg-green-900/30 px-3 py-1.5 rounded-lg">
                                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                            Tersedia
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $book->available_copies }} dari {{ $book->total_copies }} eksemplar)</span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-sm font-bold text-red-600 bg-red-100 dark:text-red-300 dark:bg-red-900/30 px-3 py-1.5 rounded-lg">
                                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                                            Tidak Tersedia
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Synopsis --}}
                        <div class="mb-8">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                <span class="material-symbols-rounded text-primary dark:text-primary-light">description</span>
                                Sinopsis
                            </h3>
                            <div class="prose dark:prose-invert text-gray-600 dark:text-gray-300 leading-relaxed max-w-none">
                                <p>{{ $book->synopsis }}</p>
                            </div>
                        </div>

                        {{-- Details Grid --}}
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 py-6 border-y border-gray-100 dark:border-white/5 mb-8">
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Penerbit</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $book->publisher }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Tahun Terbit</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $book->publication_year }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">ISBN</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $book->isbn }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Bahasa</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $book->language ?? 'Indonesia' }}</p>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col sm:flex-row gap-4 mt-auto">
                            @auth
                                <a href="{{ route('borrow.create', $book) }}" 
                                    class="flex-1 bg-primary hover:bg-primary-light text-white text-lg font-bold py-4 px-8 rounded-xl transition-all shadow-xl shadow-primary/20 hover:shadow-primary/40 transform hover:-translate-y-1 flex items-center justify-center gap-3 {{ $book->available_copies < 1 ? 'pointer-events-none opacity-50' : '' }}">
                                    <span class="material-symbols-rounded">bookmark_add</span>
                                    {{ $book->available_copies > 0 ? 'Pinjam Buku' : 'Stok Habis' }}
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="flex-1 bg-primary hover:bg-primary-light text-white text-lg font-bold py-4 px-8 rounded-xl transition-all shadow-xl shadow-primary/20 hover:shadow-primary/40 transform hover:-translate-y-1 flex items-center justify-center gap-3">
                                    <span class="material-symbols-rounded">login</span>
                                    Masuk untuk Meminjam
                                </a>
                            @endauth

                            <form action="{{ route('wishlist.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book_id" value="{{ $book->id }}">
                                <button type="submit" class="flex-none bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 hover:border-red-200 hover:bg-red-50 dark:hover:bg-red-900/10 text-gray-600 dark:text-gray-300 hover:text-red-500 py-4 px-6 rounded-xl transition-all font-medium flex items-center justify-center gap-2 group">
                                    <span class="material-symbols-rounded group-hover:fill-current">favorite</span>
                                    <span class="hidden sm:inline">Wishlist</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Similar Books (Placeholder) --}}
            <div class="mt-16">
                <div class="flex justify-between items-end mb-8">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Buku Serupa</h3>
                        <p class="text-gray-500 dark:text-gray-400 mt-1">Rekomendasi lain dari kategori {{ $book->category->name }}</p>
                    </div>
                    <a class="text-primary dark:text-primary-light font-medium hover:underline flex items-center gap-1" href="{{ route('catalog.index', ['category' => $book->category_id]) }}">
                        Lihat Semua <span class="material-symbols-rounded text-lg">arrow_forward</span>
                    </a>
                </div>
                {{-- Grid for similar books would go here --}}
                 <p class="text-gray-500 italic">Fitur rekomendasi buku akan segera hadir.</p>
            </div>
        </div>
    </main>
</x-app-layout>
