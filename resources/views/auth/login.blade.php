<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sukabaca - Masuk</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@48,400,1,0" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .bg-grid-pattern {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(47, 72, 58, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(47, 72, 58, 0.03) 1px, transparent 1px);
        }
        .dark .bg-grid-pattern {
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        }
    </style>
    
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.classList.add('dark');
        }
    </script>
</head>
<body class="bg-background-light dark:bg-background-dark text-text-color-light dark:text-text-color-dark transition-colors duration-300 font-sans min-h-screen flex">
    {{-- Left Panel - Branding --}}
    <div class="hidden lg:flex w-1/2 bg-primary relative overflow-hidden flex-col justify-between p-12 xl:p-20">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] bg-cover bg-center mix-blend-overlay opacity-20"></div>
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-secondary-accent/20 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-secondary/30 rounded-full blur-[100px] translate-y-1/3 -translate-x-1/3"></div>
        
        <div class="relative z-10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 mb-10">
                <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center border border-white/20 text-secondary-accent shadow-lg">
                    <span class="material-symbols-rounded text-3xl">menu_book</span>
                </div>
                <span class="font-bold text-3xl text-white tracking-tight">Sukabaca.</span>
            </a>
            <h1 class="text-5xl xl:text-6xl font-bold text-white mb-8 leading-tight">
                Temukan Dunia Baru di Setiap Halaman.
            </h1>
            <p class="text-lg text-white/80 max-w-lg leading-relaxed font-light">
                Bergabunglah dengan komunitas pembaca Sukabaca. Akses ribuan koleksi buku berkualitas tanpa batas.
            </p>
        </div>
        
        <div class="relative z-10 mt-12">
            <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-6 max-w-md shadow-2xl">
                <div class="flex items-center gap-1 text-secondary-accent mb-3">
                    @for($i = 0; $i < 5; $i++)
                    <span class="material-symbols-rounded text-sm">star</span>
                    @endfor
                </div>
                <p class="text-white text-sm italic mb-4">"Sukabaca mengubah cara saya membaca. Peminjamannya mudah dan bukunya selalu update!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-secondary-accent to-orange-400 flex items-center justify-center text-primary font-bold text-sm">DA</div>
                    <div>
                        <p class="text-white text-sm font-bold">Dina Amalia</p>
                        <p class="text-white/60 text-xs">Book Enthusiast</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Right Panel - Login Form --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center p-6 sm:p-12 lg:p-24 relative overflow-y-auto bg-grid-pattern">
        {{-- Mobile Logo --}}
        <a href="{{ route('home') }}" class="lg:hidden absolute top-8 left-8 flex items-center gap-2">
            <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-md">
                <span class="material-symbols-rounded text-2xl">menu_book</span>
            </div>
            <span class="font-bold text-2xl text-primary dark:text-white">Sukabaca.</span>
        </a>
        
        {{-- Dark Mode Toggle --}}
        <button class="absolute top-8 right-8 p-2 rounded-full hover:bg-gray-100 dark:hover:bg-white/5 transition-colors text-gray-500 dark:text-gray-400" 
            onclick="document.documentElement.classList.toggle('dark'); localStorage.setItem('darkMode', document.documentElement.classList.contains('dark'))">
            <span class="material-symbols-rounded block dark:hidden">dark_mode</span>
            <span class="material-symbols-rounded hidden dark:block">light_mode</span>
        </button>
        
        <div class="w-full max-w-md space-y-8 mt-12 lg:mt-0">
            <div class="text-center lg:text-left">
                <h2 class="text-3xl md:text-4xl font-extrabold text-primary dark:text-white mb-3">Selamat Datang! 👋</h2>
                <p class="text-gray-500 dark:text-gray-400">Masuk untuk melanjutkan petualangan membaca Anda.</p>
            </div>
            
            {{-- Session Status --}}
            @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/10 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-start gap-3">
                <span class="material-symbols-rounded text-green-600 dark:text-green-400 mt-0.5 shrink-0">check_circle</span>
                <p class="text-sm text-green-600 dark:text-green-400">{{ session('status') }}</p>
            </div>
            @endif
            
            {{-- Error Alert --}}
            @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-start gap-3">
                <span class="material-symbols-rounded text-red-600 dark:text-red-400 mt-0.5 shrink-0">error</span>
                <div>
                    <h3 class="text-sm font-bold text-red-800 dark:text-red-300">Login Gagal</h3>
                    <p class="text-xs text-red-600 dark:text-red-400 mt-1 leading-relaxed">{{ $errors->first() }}</p>
                </div>
            </div>
            @endif
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div class="space-y-5">
                    {{-- Email Input --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5" for="email">
                            Alamat Email
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-primary transition-colors">mail</span>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" 
                                class="block w-full pl-11 pr-4 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm" 
                                placeholder="nama@email.com" required autofocus autocomplete="email"/>
                        </div>
                    </div>
                    
                    {{-- Password Input --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5" for="password">
                            Password
                        </label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <span class="material-symbols-rounded text-gray-400 group-focus-within:text-primary transition-colors">lock</span>
                            </div>
                            <input type="password" id="password" name="password" 
                                class="block w-full pl-11 pr-12 py-3.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-surface-dark text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-sm" 
                                placeholder="••••••••" required autocomplete="current-password"/>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" 
                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded cursor-pointer"/>
                        <label class="ml-2 block text-sm text-gray-600 dark:text-gray-400 cursor-pointer select-none" for="remember">
                            Ingat Saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                    <div class="text-sm">
                        <a class="font-semibold text-primary dark:text-white hover:text-primary-light dark:hover:text-primary-light transition-colors" href="{{ route('password.request') }}">
                            Lupa Password?
                        </a>
                    </div>
                    @endif
                </div>
                
                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-4 px-4 border border-transparent text-sm font-bold rounded-xl text-primary-dark bg-secondary-accent hover:bg-[#eeb64f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary-accent shadow-glow transition-all duration-300 hover:-translate-y-1">
                        <span class="absolute right-4 inset-y-0 flex items-center pl-3">
                            <span class="material-symbols-rounded group-hover:translate-x-1 transition-transform">arrow_forward</span>
                        </span>
                        Masuk Sekarang
                    </button>
                </div>
            </form>
            
            <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                Belum punya akun?
                <a class="font-bold text-primary dark:text-white hover:text-secondary-accent transition-colors" href="{{ route('register') }}">
                    Daftar gratis di sini
                </a>
            </p>
        </div>
        
        <div class="absolute bottom-6 w-full text-center lg:text-left lg:px-24">
            <p class="text-xs text-gray-400 dark:text-gray-600">© {{ date('Y') }} Sukabaca. Hak cipta dilindungi.</p>
        </div>
    </div>
</body>
</html>
