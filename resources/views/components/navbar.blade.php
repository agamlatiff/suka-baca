{{-- Navbar Component - Sukabaca Design --}}
<nav class="fixed w-full z-50 bg-background-light/90 dark:bg-background-dark/90 backdrop-blur-md border-b border-primary/10 dark:border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex-shrink-0 flex items-center gap-2 cursor-pointer">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white">
                    <span class="material-symbols-rounded text-2xl">menu_book</span>
                </div>
                <span class="font-bold text-2xl tracking-tight text-primary dark:text-white">Sukabaca.</span>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden md:flex space-x-10 items-center">
                <a class="{{ request()->routeIs('home') ? 'text-primary dark:text-secondary-accent' : 'text-gray-600 dark:text-gray-300' }} hover:text-primary dark:hover:text-primary-light transition-colors font-medium relative group" href="{{ route('home') }}">
                    Beranda
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-primary transition-all {{ request()->routeIs('home') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a class="{{ request()->routeIs('catalog.*') ? 'text-primary dark:text-secondary-accent' : 'text-gray-600 dark:text-gray-300' }} hover:text-primary dark:hover:text-primary-light transition-colors font-medium relative group" href="{{ route('catalog.index') }}">
                    Katalog
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-primary transition-all {{ request()->routeIs('catalog.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
                <a class="{{ request()->routeIs('wishlist.*') ? 'text-primary dark:text-secondary-accent' : 'text-gray-600 dark:text-gray-300' }} hover:text-primary dark:hover:text-primary-light transition-colors font-medium relative group flex items-center gap-1" href="{{ route('wishlist.index') }}">
                    Wishlist
                    @auth
                        @php
                            $wishlistCount = Auth::user()->wishlists()->count();
                        @endphp
                        @if($wishlistCount > 0)
                            <span class="bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $wishlistCount > 9 ? '9+' : $wishlistCount }}
                            </span>
                        @endif
                    @endauth
                    <span class="absolute -bottom-1 left-0 h-0.5 bg-primary transition-all {{ request()->routeIs('wishlist.*') ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                </a>
            </div>

            {{-- Right Side Actions --}}
            <div class="flex items-center space-x-3">
                {{-- Dark Mode Toggle --}}
                <button 
                    onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'))"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-gray-600 dark:text-gray-300 mr-2"
                >
                    <span class="material-symbols-rounded block dark:hidden">dark_mode</span>
                    <span class="material-symbols-rounded hidden dark:block">light_mode</span>
                </button>

                @guest
                    <a class="hidden md:block px-5 py-2.5 text-primary dark:text-white font-semibold hover:bg-primary/5 rounded-lg transition-colors" href="{{ route('login') }}">
                        Masuk
                    </a>
                    <a class="bg-primary hover:bg-primary-light text-white px-5 py-2.5 rounded-lg font-medium transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40" href="{{ route('register') }}">
                        Daftar
                    </a>
                @else
                    {{-- User Dropdown --}}
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center gap-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 transition-colors">
                            <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center text-white text-sm font-bold">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="hidden md:block text-gray-700 dark:text-gray-200 font-medium">{{ Auth::user()->name }}</span>
                            <span class="material-symbols-rounded text-gray-400">expand_more</span>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-surface-dark rounded-xl shadow-xl border border-gray-100 dark:border-white/10 py-2 z-50">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5">
                                <span class="material-symbols-rounded text-xl">dashboard</span>
                                Dashboard
                            </a>
                            <a href="{{ route('borrowings.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5">
                                <span class="material-symbols-rounded text-xl">library_books</span>
                                Pinjaman Saya
                            </a>
                            <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-white/5">
                                <span class="material-symbols-rounded text-xl">person</span>
                                Profil
                            </a>
                            <hr class="my-2 border-gray-100 dark:border-white/10">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-3 px-4 py-2.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20">
                                    <span class="material-symbols-rounded text-xl">logout</span>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

                {{-- Mobile Menu Button --}}
                <button class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-white/5 text-gray-600 dark:text-gray-300">
                    <span class="material-symbols-rounded">menu</span>
                </button>
            </div>
        </div>
    </div>
</nav>
