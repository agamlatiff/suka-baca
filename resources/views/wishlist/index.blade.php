<x-app-layout>
    @section('title', 'Wishlist Saya')
    @section('meta_description', 'Lihat dan kelola daftar buku impian Anda di Sukabaca.')

    <main class="flex-grow pt-32 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden min-h-screen">
        {{-- Background Elements --}}
        <div class="absolute top-20 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[100px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary-accent/5 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto">
            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 border border-green-200 dark:border-green-800 flex items-center gap-3">
                    <span class="material-symbols-rounded">check_circle</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 flex items-center gap-3">
                    <span class="material-symbols-rounded">info</span>
                    {{ session('info') }}
                </div>
            @endif

            {{-- Header --}}
            <header class="mb-10 animate-fade-in-up">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-4 mb-2">
                            <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white">Wishlist Saya</h1>
                            <span class="bg-secondary-accent text-primary-dark font-bold px-3 py-1 rounded-full text-xs shadow-glow">
                                {{ $wishlists->total() }} Item
                            </span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-400 max-w-xl">
                            Simpan buku impian Anda di sini dan pinjam saat Anda siap memulai petualangan baru.
                        </p>
                    </div>
                </div>
            </header>

            {{-- Empty State or Grid --}}
            @if($wishlists->isEmpty())
                <div class="border-t-2 border-dashed border-gray-200 dark:border-white/10 pt-16 mt-16">
                    <div class="flex flex-col items-center justify-center text-center py-10 max-w-lg mx-auto">
                        <div class="relative w-48 h-48 mb-6 group cursor-pointer">
                            <div class="absolute inset-0 bg-secondary-accent/20 rounded-full blur-2xl group-hover:blur-3xl transition-all duration-500"></div>
                            <div class="relative bg-white dark:bg-surface-dark w-full h-full rounded-full flex items-center justify-center shadow-lg border-4 border-white dark:border-white/5 group-hover:scale-105 transition-transform duration-300">
                                <span class="material-symbols-rounded text-7xl text-gray-200 dark:text-white/10 group-hover:text-secondary-accent transition-colors duration-300">auto_stories</span>
                                <div class="absolute top-2 right-6 bg-white dark:bg-surface-dark p-2 rounded-full shadow-md animate-bounce">
                                    <span class="material-symbols-rounded text-2xl text-secondary-accent">stars</span>
                                </div>
                                <div class="absolute bottom-4 left-6 bg-white dark:bg-surface-dark p-2 rounded-full shadow-md animate-bounce" style="animation-delay: 0.5s;">
                                    <span class="material-symbols-rounded text-xl text-primary">bookmark_add</span>
                                </div>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-primary dark:text-white mb-3">Wishlist Masih Kosong</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-8 leading-relaxed">
                            Wah, rak bukumu masih sepi nih! Yuk, mulai petualanganmu dengan menambahkan buku seru ke daftar keinginan.
                        </p>
                        <a href="{{ route('catalog.index') }}" class="bg-secondary-accent hover:bg-yellow-500 text-primary-dark font-bold text-lg px-8 py-3 rounded-xl shadow-glow hover:shadow-lg transition-all hover:-translate-y-1 inline-flex items-center gap-2">
                            Jelajahi Katalog
                            <span class="material-symbols-rounded">explore</span>
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8 mb-24">
                    @foreach($wishlists as $wishlist)
                        @php $book = $wishlist->book; @endphp
                        <div class="group bg-white dark:bg-surface-dark rounded-2xl p-4 shadow-sm hover:shadow-soft hover:-translate-y-1 transition-all duration-300 border border-gray-100 dark:border-white/5 flex flex-col h-full relative {{ $book->available_copies < 1 ? 'grayscale opacity-75 hover:grayscale-0 hover:opacity-100' : '' }}">
                            
                            {{-- Remove Button --}}
                            <form action="{{ route('wishlist.destroy', $wishlist->id) }}" method="POST" class="absolute top-4 right-4 z-20">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-full bg-white/80 dark:bg-black/40 backdrop-blur-sm flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-all opacity-0 group-hover:opacity-100" title="Hapus dari wishlist">
                                    <span class="material-symbols-rounded text-lg">close</span>
                                </button>
                            </form>

                            {{-- Image --}}
                            <div class="relative aspect-[2/3] rounded-xl overflow-hidden mb-4 bg-gray-100 dark:bg-gray-800 shadow-inner">
                                @if($book->available_copies < 1)
                                    <div class="absolute inset-0 bg-black/40 z-10 flex items-center justify-center backdrop-blur-[1px]">
                                        <span class="bg-black/70 text-white text-xs font-bold px-3 py-1.5 rounded-full backdrop-blur-md border border-white/20">Stok Habis</span>
                                    </div>
                                @endif
                                
                                <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('placeholder-book.jpg') }}" 
                                    alt="{{ $book->title }}" 
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                                
                                @if($book->available_copies > 0)
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                @endif
                            </div>

                            {{-- Content --}}
                            <div class="flex flex-col flex-grow">
                                <div class="flex justify-between items-start mb-2">
                                    @if($book->available_copies > 0)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 tracking-wide uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5 animate-pulse"></span>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 dark:bg-gray-800 dark:text-gray-400 tracking-wide uppercase">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-400 mr-1.5"></span>
                                            Habis
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="font-bold text-lg text-primary dark:text-white leading-tight mb-1 line-clamp-2">
                                    <a href="{{ route('catalog.show', $book) }}" class="hover:underline">{{ $book->title }}</a>
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ $book->author }}</p>

                                <div class="mt-auto pt-4 border-t border-gray-50 dark:border-white/5">
                                    @if($book->available_copies > 0)
                                        <form action="{{ route('borrowings.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                                            <button type="submit" class="w-full py-2.5 bg-primary hover:bg-primary-light text-white rounded-lg font-medium text-sm transition-all shadow-lg shadow-primary/20 hover:shadow-primary/30 flex items-center justify-center gap-2 group/btn">
                                                <span class="material-symbols-rounded text-lg">shopping_bag</span>
                                                <span>Pinjam Sekarang</span>
                                            </button>
                                        </form>
                                    @else
                                        <button class="w-full py-2.5 bg-gray-100 text-gray-400 dark:bg-white/5 dark:text-gray-500 rounded-lg font-medium text-sm flex items-center justify-center gap-2 cursor-not-allowed" disabled>
                                            <span class="material-symbols-rounded text-lg">notifications</span>
                                            <span>Ingatkan Saya</span>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                
                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $wishlists->links() }}
                </div>
            @endif
        </div>
    </main>
</x-app-layout>
