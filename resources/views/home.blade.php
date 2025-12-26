<x-app-layout>
    @section('title', 'Beranda')
    @section('meta_description', 'Sukabaca - Platform peminjaman buku modern dengan ribuan koleksi berkualitas. Baca lebih banyak, bayar lebih hemat.')

    {{-- Hero Section --}}
    <header class="relative pt-32 pb-20 md:pt-40 md:pb-28 overflow-hidden bg-primary dark:bg-background-dark">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -right-20 w-[600px] h-[600px] bg-secondary-accent/10 rounded-full blur-[100px] mix-blend-overlay"></div>
            <div class="absolute bottom-0 left-0 w-full h-full bg-[radial-gradient(circle_at_0%_100%,_rgba(45,138,130,0.2),transparent_50%)]"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTU LMC4wNSkiLz48L3N2Zz4=')] [mask-image:linear-gradient(to_bottom,white,transparent)]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-white/90 text-sm font-medium mb-8 border border-white/10 backdrop-blur-sm animate-fade-in-up">
                <span class="material-symbols-rounded text-secondary-accent">stars</span>
                Vibrant Book Haven
            </div>
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-8 leading-tight tracking-tight max-w-5xl mx-auto">
                Pinjam Buku Terbaik & <br/>
                <span class="relative inline-block text-secondary-accent">
                    Jelajahi Dunia Baru.
                    <svg class="absolute w-full h-4 -bottom-1 left-0 text-white/20" preserveAspectRatio="none" viewBox="0 0 100 10">
                        <path d="M0 5 Q 50 12 100 5" fill="none" stroke="currentColor" stroke-width="3"></path>
                    </svg>
                </span>
            </h1>
            <p class="text-lg md:text-xl text-white/70 mb-10 max-w-2xl mx-auto font-light leading-relaxed">
                Akses ribuan buku fisik dan digital tanpa harus membeli. Hemat biaya, hemat ruang, dan mulai petualangan membaca Anda hari ini.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a class="bg-secondary-accent text-primary-light font-bold text-lg px-8 py-4 rounded-xl shadow-xl shadow-secondary-accent/20 hover:scale-105 transition-transform flex items-center gap-2" href="{{ route('catalog.index') }}">
                    Jelajahi Katalog
                    <span class="material-symbols-rounded">explore</span>
                </a>
                <a class="bg-white/10 backdrop-blur-md border border-white/20 text-white text-lg px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all flex items-center gap-2" href="#cara-sewa">
                    <span class="material-symbols-rounded">play_circle</span>
                    Cara Kerja
                </a>
            </div>
            <div class="hidden lg:block absolute top-1/2 left-10 w-24 h-32 bg-white/5 border border-white/10 rounded-lg -rotate-12 backdrop-blur-sm animate-pulse"></div>
            <div class="hidden lg:block absolute bottom-10 right-20 w-32 h-40 bg-secondary/30 border border-white/10 rounded-lg rotate-6 backdrop-blur-sm"></div>
        </div>
    </header>

    {{-- Categories Section --}}
    <section class="py-12 bg-white dark:bg-surface-dark border-b border-gray-100 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-center gap-4 md:gap-8">
                @foreach($categories as $category)
                <a class="group flex items-center gap-3 pl-2 pr-6 py-2 rounded-full hover:bg-background-light dark:hover:bg-white/5 border border-transparent hover:border-gray-200 dark:hover:border-white/10 transition-all" href="{{ route('catalog.index', ['category' => $category->id]) }}">
                    {{-- Dynamic colors could be added here if available in DB, defaulting to blue for now as example or using loop index --}}
                    @php
                        $colors = ['blue', 'purple', 'orange', 'teal', 'pink'];
                        $color = $colors[$loop->index % count($colors)];
                    @endphp
                    <div class="w-10 h-10 rounded-full bg-{{ $color }}-50 dark:bg-{{ $color }}-900/20 text-{{ $color }}-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <span class="material-symbols-rounded">{{ $category->icon ?? 'auto_stories' }}</span>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-sm text-gray-900 dark:text-white">{{ $category->name }}</p>
                        <p class="text-xs text-gray-500">{{ $category->books_count }} Buku</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Latest Books Section --}}
    <section class="py-20 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-primary dark:text-white">Buku Terbaru</h2>
                    <p class="text-gray-500 mt-2">Koleksi yang baru saja mendarat di rak kami.</p>
                </div>
                <a class="text-primary dark:text-white font-semibold hover:underline flex items-center gap-1" href="{{ route('catalog.index') }}">
                    Lihat Semua <span class="material-symbols-rounded text-lg">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                @foreach($latestBooks as $book)
                <a href="{{ route('catalog.show', $book) }}" class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 aspect-[2/3] mb-4 shadow-sm group-hover:shadow-md transition-all">
                        <span class="absolute top-2 left-2 bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">BARU</span>
                        @if($book->image)
                            <img alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $book->image) }}"/>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center">
                                <span class="material-symbols-rounded text-6xl text-primary/50">menu_book</span>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $book->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Features/Bento Section --}}
    <section class="py-24 relative overflow-hidden bg-background-light dark:bg-background-dark">
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-primary/5 dark:to-primary/10 pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-extrabold text-primary dark:text-white mb-4">Pengalaman Sewa Terbaik</h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">Kenapa memilih Sukabaca? Temukan kemudahan meminjam buku favorit Anda dalam sekejap.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Main Feature Card --}}
                <div class="bento-card group relative col-span-1 md:col-span-2 lg:col-span-2 lg:row-span-2 bg-primary dark:bg-primary rounded-[2.5rem] p-8 lg:p-12 overflow-hidden flex flex-col justify-between shadow-soft">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 group-hover:bg-white/15 transition-colors"></div>
                    <div class="absolute bottom-0 left-0 w-64 h-64 bg-secondary-accent/20 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <div>
                            <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-secondary-accent mb-8 border border-white/10">
                                <span class="material-symbols-rounded text-4xl">auto_stories</span>
                            </div>
                            <h3 class="text-3xl lg:text-4xl font-bold text-white mb-6 leading-tight">Koleksi Lengkap & <br/>Berkualitas</h3>
                            <p class="text-white/80 text-lg leading-relaxed max-w-sm">
                                Ribuan judul dari berbagai genre tersedia. Mulai dari fiksi populer, bisnis, hingga pengembangan diri yang langka.
                            </p>
                        </div>
                        <div class="mt-8">
                            <a class="inline-flex items-center gap-2 text-white font-bold text-lg hover:gap-4 transition-all" href="{{ route('catalog.index') }}">
                                Cek Katalog <span class="material-symbols-rounded">arrow_right_alt</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Small Feature Cards --}}
                <div class="bento-card col-span-1 bg-white dark:bg-surface-dark border border-orange-100 dark:border-white/5 rounded-[2.5rem] p-8 flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-orange-50 dark:bg-orange-900/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-orange-100 dark:bg-orange-900/30 text-orange-600 flex items-center justify-center mb-6">
                            <span class="material-symbols-rounded text-3xl">savings</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sangat Terjangkau</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Sewa mulai Rp5.000 /minggu. Hemat jutaan rupiah setiap tahun.</p>
                    </div>
                </div>
                
                <div class="bento-card col-span-1 bg-white dark:bg-surface-dark border border-green-100 dark:border-white/5 rounded-[2.5rem] p-8 flex flex-col justify-center relative overflow-hidden group">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 dark:bg-green-900/10 rounded-full blur-xl group-hover:scale-150 transition-transform"></div>
                    <div class="relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-green-100 dark:bg-green-900/30 text-green-600 flex items-center justify-center mb-6">
                            <span class="material-symbols-rounded text-3xl">touch_app</span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Proses Kilat</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Pesan lewat web, ambil atau antar. Tanpa ribet, tanpa drama.</p>
                    </div>
                </div>
                
                <div class="bento-card col-span-1 md:col-span-2 lg:col-span-2 bg-white dark:bg-surface-dark border border-gray-100 dark:border-white/5 rounded-[2.5rem] p-8 flex items-center relative overflow-hidden shadow-soft group">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-purple-50 dark:from-purple-900/10 to-transparent"></div>
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center gap-6 w-full">
                        <div class="w-16 h-16 rounded-2xl bg-purple-100 dark:bg-purple-900/30 text-purple-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-3xl">update</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Perpanjangan Otomatis</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 max-w-md">
                                Belum selesai baca? Perpanjang masa sewa dengan satu klik dari dashboard Anda. Tanpa denda tersembunyi.
                            </p>
                        </div>
                        <div class="ml-auto hidden sm:block">
                            <span class="material-symbols-rounded text-6xl text-purple-100 dark:text-purple-900/20 rotate-12 group-hover:rotate-0 transition-transform">history_toggle_off</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- How It Works Section --}}
    <section id="cara-sewa" class="py-24 bg-surface-light dark:bg-surface-dark border-t border-gray-100 dark:border-white/5 relative">
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary/5 rounded-full blur-[120px]"></div>
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <span class="text-secondary-accent font-bold tracking-wider uppercase text-sm mb-2 block">Panduan Singkat</span>
                <h2 class="text-4xl font-extrabold text-primary dark:text-white mb-4">Cara Sewa Buku</h2>
                <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">4 Langkah mudah untuk memulai petualangan membacamu.</p>
            </div>
            <div class="relative">
                <div class="hidden lg:block absolute top-12 left-[10%] w-[80%] h-1 bg-gray-100 dark:bg-white/10 -z-10 rounded-full">
                    <div class="h-full w-full bg-gradient-to-r from-transparent via-primary/20 to-transparent"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-6 text-center">
                    @php
                    $steps = [
                        ['icon' => 'how_to_reg', 'title' => 'Daftar Akun', 'desc' => 'Buat akun gratis dalam hitungan detik. Cukup email & data diri.'],
                        ['icon' => 'menu_book', 'title' => 'Pilih Buku', 'desc' => 'Jelajahi katalog dan masukkan buku favorit ke keranjang.'],
                        ['icon' => 'payments', 'title' => 'Bayar Sewa', 'desc' => 'Checkout aman dengan QRIS atau E-Wallet. Mulai Rp5.000.'],
                        ['icon' => 'local_library', 'title' => 'Ambil Buku', 'desc' => 'Ambil di lokasi kami atau pilih opsi pengiriman ke rumah.'],
                    ];
                    @endphp
                    @foreach($steps as $index => $step)
                    <div class="group relative flex flex-col items-center">
                        <div class="w-24 h-24 bg-white dark:bg-surface-dark border-4 border-primary text-primary rounded-full flex items-center justify-center text-4xl shadow-lg mb-6 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all duration-300 z-10 relative">
                            <span class="material-symbols-rounded">{{ $step['icon'] }}</span>
                            <div class="absolute -top-3 -right-3 w-8 h-8 bg-secondary-accent text-primary-dark font-bold rounded-full flex items-center justify-center text-sm shadow-md border-2 border-white dark:border-surface-dark">{{ $index + 1 }}</div>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $step['title'] }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 px-4">{{ $step['desc'] }}</p>
                        
                        @if(!$loop->last)
                            <div class="hidden lg:block absolute top-8 -right-1/2 translate-x-1/2 w-full text-gray-300 dark:text-gray-600 z-0 animate-float-arrow">
                                <span class="material-symbols-rounded text-3xl">chevron_right</span>
                            </div>
                            <div class="lg:hidden mt-6 text-gray-300 dark:text-gray-600 animate-bounce">
                                <span class="material-symbols-rounded text-2xl">arrow_downward</span>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-16 text-center">
                <a class="group bg-secondary-accent hover:bg-yellow-500 text-primary-dark text-lg px-10 py-4 rounded-xl font-bold transition-all shadow-glow hover:shadow-lg inline-flex items-center gap-2" href="{{ route('register') }}">
                    Mulai Sewa Sekarang
                    <span class="material-symbols-rounded group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>

    {{-- Popular Books Section --}}
    <section class="py-20 bg-white dark:bg-surface-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-10">
                <div>
                    <h2 class="text-3xl font-bold text-primary dark:text-white">Buku Populer</h2>
                    <p class="text-gray-500 mt-2">Paling banyak dipinjam oleh pembaca kami.</p>
                </div>
                <a class="text-primary dark:text-white font-semibold hover:underline flex items-center gap-1" href="{{ route('catalog.index') }}">
                    Lihat Semua <span class="material-symbols-rounded text-lg">arrow_forward</span>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
                @foreach($popularBooks as $book)
                <a href="{{ route('catalog.show', $book) }}" class="group cursor-pointer">
                    <div class="relative overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-800 aspect-[2/3] mb-4 shadow-sm group-hover:shadow-md transition-all">
                        @if($book->times_borrowed > 10)
                        <span class="absolute top-2 left-2 bg-orange-500 text-white text-[10px] font-bold px-2 py-1 rounded shadow-sm z-10">POPULER</span>
                        @endif
                        @if($book->image)
                            <img alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $book->image) }}"/>
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/40 flex items-center justify-center">
                                <span class="material-symbols-rounded text-6xl text-primary/50">menu_book</span>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white truncate">{{ $book->title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Contact Section --}}
    <section class="py-24 bg-white dark:bg-surface-dark border-t border-gray-100 dark:border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/10 text-primary dark:text-white text-sm font-semibold mb-6">
                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                        Buka Sekarang
                    </div>
                    <h2 class="text-4xl font-extrabold text-primary dark:text-white mb-6">Kunjungi Markas Sukabaca</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-10 text-lg leading-relaxed">
                        Ingin melihat koleksi fisik atau berdiskusi dengan pustakawan kami? Datang langsung ke lokasi kami yang nyaman.
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start gap-5 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer border border-transparent hover:border-gray-100 dark:hover:border-white/10">
                            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-2xl">location_on</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">Lokasi Kami</h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ $settings['library_address'] ?? 'Jl. Pustaka No. 88, Jakarta' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-5 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer border border-transparent hover:border-gray-100 dark:hover:border-white/10">
                            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-2xl">schedule</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">Jam Operasional</h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">Senin - Jumat: 09:00 - 20:00<br/>Sabtu - Minggu: 10:00 - 18:00</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-5 p-4 rounded-2xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group cursor-pointer border border-transparent hover:border-gray-100 dark:hover:border-white/10">
                            <div class="w-14 h-14 rounded-2xl bg-primary text-white flex items-center justify-center shrink-0 shadow-lg shadow-primary/20 group-hover:scale-110 transition-transform">
                                <span class="material-symbols-rounded text-2xl">contact_support</span>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 dark:text-white text-lg mb-1">Layanan Pelanggan</h4>
                                <p class="text-gray-500 dark:text-gray-400 leading-relaxed">{{ $settings['library_email'] ?? 'halo@sukabaca.id' }}<br/>{{ $settings['library_phone'] ?? '+62 812 3456 7890' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative h-[500px] w-full bg-gray-200 dark:bg-gray-800 rounded-[2.5rem] overflow-hidden shadow-2xl border-8 border-white dark:border-white/5">
                    <div class="absolute inset-0 bg-primary opacity-10" style="background-image: radial-gradient(#2F483A 1px, transparent 1px); background-size: 20px 20px;"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
                        <div class="w-20 h-20 rounded-full bg-secondary-accent/20 flex items-center justify-center animate-ping absolute"></div>
                        <div class="relative w-16 h-16 bg-secondary-accent text-white rounded-full flex items-center justify-center shadow-xl border-4 border-white dark:border-gray-800 z-10">
                            <span class="material-symbols-rounded text-3xl">menu_book</span>
                        </div>
                        <div class="mt-4 bg-white dark:bg-surface-dark px-4 py-2 rounded-xl shadow-lg font-bold text-primary dark:text-white text-sm whitespace-nowrap z-10">Sukabaca HQ</div>
                    </div>
                    <div class="absolute top-6 right-6 bg-white dark:bg-surface-dark p-2 rounded-lg shadow-md">
                        <span class="material-symbols-rounded text-gray-400">layers</span>
                    </div>
                    <div class="absolute bottom-6 right-6 flex flex-col gap-2">
                        <div class="bg-white dark:bg-surface-dark w-10 h-10 rounded-lg shadow-md flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer">
                            <span class="material-symbols-rounded">add</span>
                        </div>
                        <div class="bg-white dark:bg-surface-dark w-10 h-10 rounded-lg shadow-md flex items-center justify-center text-gray-600 hover:bg-gray-50 cursor-pointer">
                            <span class="material-symbols-rounded">remove</span>
                        </div>
                    </div>
                    <div class="absolute bottom-6 left-6 bg-white/90 dark:bg-surface-dark/90 backdrop-blur-sm p-4 rounded-xl shadow-lg max-w-[200px]">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            <p class="text-xs font-bold text-gray-500">Rute Tercepat</p>
                        </div>
                        <p class="text-sm font-bold text-primary dark:text-white">15 min dari Stasiun Tebet</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
