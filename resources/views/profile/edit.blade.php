@section('title', 'Pengaturan Profil')

<x-app-layout>
    <div class="pt-28 pb-20 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Header --}}
            <div class="mb-10">
                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                    <a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Dashboard</a>
                    <span class="material-symbols-rounded text-base">chevron_right</span>
                    <span class="text-primary font-medium">Pengaturan Profil</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary dark:text-white leading-tight">
                    Pengaturan Profil <span class="material-symbols-rounded text-secondary-accent text-3xl align-middle ml-2">manage_accounts</span>
                </h1>
                <p class="text-gray-500 mt-2">Kelola informasi pribadi dan keamanan akun Anda di sini.</p>
            </div>

            <div class="grid lg:grid-cols-12 gap-8">
                {{-- Sidebar Navigation --}}
                <div class="lg:col-span-3">
                    <div class="bg-white dark:bg-surface-dark rounded-2xl p-4 shadow-soft border border-gray-100 dark:border-white/5 sticky top-28">
                        <div class="flex flex-col gap-1">
                            <a href="#profile-info" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-primary/5 text-primary dark:text-white font-semibold transition-all">
                                <span class="material-symbols-rounded">person</span>
                                Edit Profil
                            </a>
                            <a href="#update-password" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white transition-all">
                                <span class="material-symbols-rounded">lock</span>
                                Ganti Password
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white transition-all">
                                <span class="material-symbols-rounded">notifications</span>
                                Notifikasi
                            </a>
                            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-white/5 hover:text-primary dark:hover:text-white transition-all">
                                <span class="material-symbols-rounded">credit_card</span>
                                Metode Pembayaran
                            </a>
                        </div>
                        <div class="border-t border-gray-100 dark:border-white/10 my-4"></div>
                        
                        {{-- Logout Button Form --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 dark:hover:bg-red-900/10 transition-all font-medium">
                                <span class="material-symbols-rounded">logout</span>
                                Keluar
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Main Content --}}
                <div class="lg:col-span-9 space-y-8">
                    
                    {{-- Profile Information Section --}}
                    <div id="profile-info" class="bg-white dark:bg-surface-dark rounded-3xl p-6 md:p-10 shadow-soft border border-gray-100 dark:border-white/5 relative">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 rounded-bl-full pointer-events-none rounded-tr-3xl"></div>
                        <div class="flex flex-col md:flex-row gap-8 items-start relative z-10">
                            
                            {{-- Avatar --}}
                            <div class="flex flex-col items-center gap-4 shrink-0 w-full md:w-auto">
                                <div class="w-32 h-32 rounded-full bg-secondary-accent text-primary font-bold flex items-center justify-center text-4xl border-4 border-white dark:border-surface-dark shadow-lg relative group cursor-pointer">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                    <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                                        <span class="material-symbols-rounded">photo_camera</span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500 mb-1">Maks 2MB</p>
                                    <button class="text-sm font-semibold text-primary dark:text-white hover:underline">Ganti Foto</button>
                                </div>
                            </div>

                            {{-- Update Profile Form --}}
                            <div class="flex-1 w-full min-w-0">
                                @include('profile.partials.update-profile-information-form')
                            </div>
                        </div>
                    </div>

                    {{-- Update Password Section --}}
                    <div id="update-password" class="bg-white dark:bg-surface-dark rounded-3xl p-6 md:p-10 shadow-soft border border-gray-100 dark:border-white/5">
                        <div class="max-w-3xl">
                            <h2 class="text-xl font-bold text-primary dark:text-white mb-6 flex items-center gap-2">
                                <span class="material-symbols-rounded text-secondary-accent">lock_reset</span>
                                Ganti Password
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-8">Pastikan password Anda aman. Gunakan minimal 8 karakter dengan kombinasi huruf dan angka.</p>
                            
                            @include('profile.partials.update-password-form')
                        </div>
                    </div>

                    {{-- Delete Account Section --}}
                    <div class="bg-white dark:bg-surface-dark rounded-3xl p-6 md:p-10 shadow-soft border border-red-100 dark:border-red-900/20">
                         <div class="max-w-3xl">
                            <h2 class="text-xl font-bold text-red-600 mb-6 flex items-center gap-2">
                                <span class="material-symbols-rounded">warning</span>
                                Hapus Akun
                            </h2>
                            <div class="prose prose-sm text-gray-500 dark:text-gray-400 mb-6">
                                <p>Setelah akun Anda dihapus, semua data dan sumber dayanya akan dihapus secara permanen.</p>
                            </div>
                             @include('profile.partials.delete-user-form')
                         </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
