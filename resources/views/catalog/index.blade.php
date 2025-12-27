<x-app-layout>
    @section('title', 'Katalog Buku')
    @section('meta_description', 'Jelajahi koleksi buku lengkap Sukabaca. Temukan buku favorit Anda dari berbagai kategori.')

    {{-- Header Section --}}
    <header class="relative pt-32 pb-24 bg-primary dark:bg-background-dark overflow-hidden">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-[400px] h-[400px] bg-secondary-accent/10 rounded-full blur-[80px] mix-blend-overlay"></div>
            <div class="absolute bottom-0 left-0 w-full h-full bg-[radial-gradient(circle_at_0%_100%,_rgba(45,138,130,0.1),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-20 [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center md:text-left">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Katalog Buku</h1>
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
                            <span class="text-sm font-medium text-secondary-accent">Katalog Buku</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </header>

    {{-- Filter & Search Section --}}
    <section class="relative z-20 -mt-10 mb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('catalog.index') }}" method="GET" class="bg-white dark:bg-surface-dark rounded-xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-5 md:p-6 border border-gray-100 dark:border-white/5">
                <div class="flex flex-col lg:flex-row gap-5 items-center justify-between">
                    {{-- Search Input --}}
                    <div class="relative w-full lg:flex-1 group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400 group-focus-within:text-primary transition-colors">search</span>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-200 dark:border-white/10 rounded-xl leading-5 bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all sm:text-sm" 
                            placeholder="Cari judul, penulis, atau ISBN..." onchange="this.form.submit()">
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto items-center">
                        {{-- Category Filter --}}
                        <div class="relative w-full sm:w-48">
                            <select name="category" onchange="this.form.submit()" 
                                class="block w-full pl-3 pr-10 py-3 text-base border-gray-200 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm rounded-xl bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white appearance-none cursor-pointer">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <span class="material-symbols-rounded">expand_more</span>
                            </div>
                        </div>

                        {{-- Sort Filter --}}
                        <div class="relative w-full sm:w-40">
                            <select name="sort" onchange="this.form.submit()" 
                                class="block w-full pl-3 pr-10 py-3 text-base border-gray-200 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent sm:text-sm rounded-xl bg-gray-50 dark:bg-white/5 text-gray-900 dark:text-white appearance-none cursor-pointer">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Populer</option>
                                <option value="random" {{ request('sort') == 'random' ? 'selected' : '' }}>Acak</option>
                                <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>A-Z Judul</option>
                                <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Z-A Judul</option>
                                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-500">
                                <span class="material-symbols-rounded">sort</span>
                            </div>
                        </div>

                        {{-- Availability Toggle --}}
                        <label class="flex items-center cursor-pointer relative select-none w-full sm:w-auto justify-between sm:justify-start gap-3 p-3 sm:p-0 rounded-xl bg-gray-50 sm:bg-transparent dark:bg-white/5 sm:dark:bg-transparent border border-gray-200 sm:border-none dark:border-white/10 sm:dark:border-none" for="show_all">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Tampilkan Semua</span>
                            <div class="relative">
                                <input type="checkbox" id="show_all" name="show_all" value="1" class="sr-only peer" 
                                    {{ request('show_all') ? 'checked' : '' }} onchange="this.form.submit()">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
                            </div>
                        </label>
                    </div>
                </div>
            </form>
        </div>
    </section>

    {{-- Book Grid Section --}}
    <section class="pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6 text-sm text-gray-500 dark:text-gray-400">
                <p>Menampilkan <span class="font-bold text-gray-900 dark:text-white">{{ $books->firstItem() ?? 0 }}-{{ $books->lastItem() ?? 0 }}</span> dari <span class="font-bold text-gray-900 dark:text-white">{{ $books->total() }}</span> buku</p>
            </div>

            @if($books->isEmpty())
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-48 h-48 bg-gray-100 dark:bg-white/5 rounded-full flex items-center justify-center mb-6">
                        <span class="material-symbols-rounded text-6xl text-gray-300 dark:text-gray-600">search_off</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6 max-w-md">
                        Kami tidak dapat menemukan buku yang cocok dengan pencarian Anda. Coba kata kunci lain atau reset filter.
                    </p>
                    <a href="{{ route('catalog.index') }}" class="px-6 py-2.5 bg-white border border-gray-200 dark:bg-surface-dark dark:border-white/10 rounded-lg text-primary dark:text-white font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        Reset Filter
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($books as $book)
                        <div class="group bg-white dark:bg-surface-dark rounded-2xl overflow-hidden border border-gray-100 dark:border-white/5 hover:border-primary/30 hover:shadow-xl hover:shadow-primary/5 transition-all duration-300 flex flex-col h-full relative">
                            <div class="relative aspect-[2/3] overflow-hidden bg-gray-100 dark:bg-gray-800">
                                @php
                                    $coverUrl = $book->image ? asset('storage/' . $book->image) : null;
                                    if (!$coverUrl && $book->isbn) {
                                        $isbnClean = str_replace(['-', ' '], '', $book->isbn);
                                        $coverUrl = "https://covers.openlibrary.org/b/isbn/{$isbnClean}-L.jpg?default=false";
                                    }
                                    if (!$coverUrl) {
                                        $coverUrl = "https://placehold.co/400x600?text=" . urlencode($book->title);
                                    }
                                @endphp
                                <img src="{{ $coverUrl }}" onerror="this.onerror=null; this.src='https://placehold.co/400x600?text={{ urlencode($book->title) }}';" alt="{{ $book->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                                <div class="absolute top-3 left-3">
                                    <span class="bg-white/90 dark:bg-surface-dark/90 backdrop-blur-sm text-primary text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm">
                                        {{ $book->category->name }}
                                    </span>
                                </div>

                                @if($book->is_popular)
                                    <div class="absolute top-3 right-3">
                                        <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm">
                                            POPULER
                                        </span>
                                    </div>
                                @endif
                            </div>

                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-bold text-gray-900 dark:text-white text-lg leading-tight mb-1 line-clamp-1 group-hover:text-primary transition-colors">
                                    <a href="{{ route('catalog.show', $book->slug) }}">{{ $book->title }}</a>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ $book->author }}</p>
                                
                                <div class="mt-auto flex justify-between items-end border-t border-gray-100 dark:border-white/5 pt-3 mb-4">
                                    @if($book->available_copies > 0)
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-orange-600 bg-orange-50 dark:bg-orange-900/20 px-2 py-1 rounded-md">
                                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span> Dipinjam
                                        </span>
                                    @endif
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('catalog.show', $book->slug) }}" class="flex-1 bg-primary hover:bg-primary-light text-white text-sm font-medium py-2 rounded-lg transition-colors shadow-lg shadow-primary/20 hover:shadow-primary/30 flex items-center justify-center">
                                        Detail
                                    </a>
                                    @auth
                                        @php
                                            $isInWishlist = Auth::user()->wishlists()->where('book_id', $book->id)->exists();
                                        @endphp
                                        <form action="{{ $isInWishlist ? route('wishlist.destroy', $book->id) : route('wishlist.store') }}" method="POST">
                                            @csrf
                                            @if($isInWishlist)
                                                @method('DELETE')
                                            @endif
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="submit" class="p-2 border rounded-lg transition-all {{ $isInWishlist ? 'border-red-300 bg-red-50 dark:bg-red-900/20 text-red-500' : 'border-gray-200 dark:border-white/10 text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 dark:hover:bg-red-900/10' }}">
                                                <span class="material-symbols-rounded text-xl">{{ $isInWishlist ? 'favorite' : 'favorite_border' }}</span>
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="p-2 border border-gray-200 dark:border-white/10 rounded-lg text-gray-400 hover:text-red-500 hover:border-red-200 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all">
                                            <span class="material-symbols-rounded text-xl">favorite_border</span>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-16">
                    {{ $books->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
