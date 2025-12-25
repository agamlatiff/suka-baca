{{-- Footer Component - Sukabaca Design --}}
<footer class="bg-background-light dark:bg-background-dark border-t border-gray-200 dark:border-white/5 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 mb-12">
            {{-- Brand --}}
            <div class="lg:col-span-2">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-8 h-8 bg-primary rounded-lg flex items-center justify-center text-white">
                        <span class="material-symbols-rounded text-lg">menu_book</span>
                    </div>
                    <span class="font-bold text-xl text-primary dark:text-white">Sukabaca.</span>
                </div>
                <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed mb-8 max-w-sm">
                    Sukabaca adalah platform peminjaman buku modern yang menghubungkan pembaca dengan ribuan koleksi berkualitas. Baca lebih banyak, bayar lebih hemat.
                </p>
                <div class="flex gap-4">
                    <a class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 hover:border-primary text-gray-500 hover:text-primary transition-all" href="#">
                        <span class="material-symbols-rounded">share</span>
                    </a>
                    <a class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 hover:border-primary text-gray-500 hover:text-primary transition-all" href="#">
                        <span class="material-symbols-rounded">photo_camera</span>
                    </a>
                    <a class="w-10 h-10 flex items-center justify-center rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 hover:border-primary text-gray-500 hover:text-primary transition-all" href="#">
                        <span class="material-symbols-rounded">alternate_email</span>
                    </a>
                </div>
            </div>

            {{-- Navigation --}}
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-6">Navigasi</h4>
                <ul class="space-y-3 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="{{ route('home') }}">Beranda</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="{{ route('catalog.index') }}">Katalog Buku</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Wishlist</a></li>
                </ul>
            </div>

            {{-- Support --}}
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-6">Dukungan</h4>
                <ul class="space-y-3 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Pusat Bantuan</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Syarat & Ketentuan</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Kebijakan Privasi</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Cara Pengembalian</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="font-bold text-gray-900 dark:text-white mb-6">Kontak</h4>
                <ul class="space-y-3 text-sm text-gray-500 dark:text-gray-400">
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Hubungi Kami</a></li>
                    <li><a class="hover:text-primary dark:hover:text-white transition-colors" href="#">Partnership</a></li>
                </ul>
            </div>
        </div>

        {{-- Copyright --}}
        <div class="border-t border-gray-200 dark:border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-400">
            <p>© {{ date('Y') }} Sukabaca Indonesia. Hak cipta dilindungi.</p>
            <p>Dibuat dengan ❤️ untuk pembaca Indonesia.</p>
        </div>
    </div>
</footer>
