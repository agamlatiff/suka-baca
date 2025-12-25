<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sukabaca - Lupa Password</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>body { font-family: 'Poppins', sans-serif; }</style>
    <script>if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');</script>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-color-light dark:text-text-color-dark transition-colors duration-300 font-sans min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg">
                    <span class="material-symbols-rounded text-3xl">menu_book</span>
                </div>
                <span class="font-bold text-3xl text-primary dark:text-white">Sukabaca.</span>
            </a>
        </div>
        
        {{-- Card --}}
        <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl p-8 border border-gray-100 dark:border-white/5">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-primary/10 dark:bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-rounded text-3xl text-primary">lock_reset</span>
                </div>
                <h2 class="text-2xl font-bold text-primary dark:text-white mb-2">Lupa Password?</h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    Tidak masalah! Masukkan alamat email Anda dan kami akan mengirimkan link untuk reset password.
                </p>
            </div>
            
            {{-- Session Status --}}
            @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-start gap-3 mb-6">
                <span class="material-symbols-rounded text-green-600 dark:text-green-400 mt-0.5 shrink-0">check_circle</span>
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
            </div>
            @endif
            
            {{-- Error --}}
            @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-start gap-3 mb-6">
                <span class="material-symbols-rounded text-red-600 dark:text-red-400 mt-0.5 shrink-0">error</span>
                <p class="text-sm text-red-600 dark:text-red-400">{{ $errors->first() }}</p>
            </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5" for="email">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <span class="material-symbols-rounded text-gray-400 group-focus-within:text-primary transition-colors">mail</span>
                        </div>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" 
                            class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm" 
                            placeholder="nama@email.com" required autofocus autocomplete="email"/>
                    </div>
                </div>
                
                <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-primary-dark bg-secondary-accent hover:bg-[#eeb64f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-accent shadow-glow transition-all duration-300 hover:-translate-y-1">
                    <span class="absolute right-4 inset-y-0 flex items-center pl-3">
                        <span class="material-symbols-rounded group-hover:translate-x-1 transition-transform">send</span>
                    </span>
                    Kirim Link Reset
                </button>
            </form>
        </div>
        
        <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
            Ingat password Anda?
            <a class="font-bold text-primary dark:text-white hover:text-secondary-accent transition-colors" href="{{ route('login') }}">
                Kembali ke login
            </a>
        </p>
    </div>
</body>
</html>
