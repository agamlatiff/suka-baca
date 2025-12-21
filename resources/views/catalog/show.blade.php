<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('catalog.index') }}" class="text-gray-500 hover:text-gray-700 mr-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Buku
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    <!-- Category Badge -->
                    <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full bg-indigo-100 text-indigo-800 mb-4">
                        {{ $book->category->name }}
                    </span>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ $book->title }}
                    </h1>

                    <!-- Author -->
                    <p class="text-lg text-gray-600 mb-6">
                        oleh <span class="font-medium">{{ $book->author }}</span>
                    </p>

                    <!-- Stats Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                        <!-- Rental Fee -->
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Biaya Sewa</p>
                            <p class="text-xl font-bold text-indigo-600">
                                Rp {{ number_format($book->rental_fee, 0, ',', '.') }}
                            </p>
                        </div>

                        <!-- Available Copies -->
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Tersedia</p>
                            <p class="text-xl font-bold {{ $book->available_copies > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $book->available_copies }} / {{ $book->total_copies }}
                            </p>
                        </div>

                        <!-- Times Borrowed -->
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Total Dipinjam</p>
                            <p class="text-xl font-bold text-gray-900">
                                {{ $book->times_borrowed }}x
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="bg-gray-50 rounded-lg p-4 text-center">
                            <p class="text-sm text-gray-500 mb-1">Status</p>
                            @if($book->available_copies > 0)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Description -->
                    @if($book->description)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Deskripsi</h3>
                            <p class="text-gray-600 leading-relaxed">
                                {{ $book->description }}
                            </p>
                        </div>
                    @endif

                    <!-- Borrow Action -->
                    <div class="border-t border-gray-200 pt-6">
                        @auth
                            @if($book->available_copies > 0)
                                <form action="{{ route('borrowings.store') }}" method="POST" class="flex flex-col sm:flex-row gap-4">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    
                                    <div class="flex-1">
                                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-1">
                                            Durasi Pinjam
                                        </label>
                                        <select name="duration" 
                                                id="duration"
                                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="7">7 hari</option>
                                            <option value="14">14 hari</option>
                                        </select>
                                    </div>

                                    <div class="flex items-end">
                                        <button type="submit" 
                                                class="w-full sm:w-auto px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                            Pinjam Buku Ini
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-yellow-800">Stok Habis</h3>
                                            <p class="mt-1 text-sm text-yellow-700">
                                                Semua eksemplar buku ini sedang dipinjam. Silakan cek kembali nanti.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <div class="flex">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Login untuk Meminjam</h3>
                                        <p class="mt-1 text-sm text-blue-700">
                                            <a href="{{ route('login') }}" class="font-medium underline hover:text-blue-600">Masuk</a> 
                                            atau 
                                            <a href="{{ route('register') }}" class="font-medium underline hover:text-blue-600">daftar</a> 
                                            untuk meminjam buku ini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Back to Catalog -->
            <div class="mt-6">
                <a href="{{ route('catalog.index') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali ke Katalog
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
