<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Katalog Buku
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search & Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('catalog.index') }}" class="flex flex-col md:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Buku</label>
                            <input type="text" 
                                   name="search" 
                                   id="search"
                                   value="{{ $filters['search'] ?? '' }}"
                                   placeholder="Judul atau penulis..."
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>

                        <!-- Category Filter -->
                        <div class="md:w-48">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select name="category" 
                                    id="category"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($filters['category'] ?? '') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Show All Toggle -->
                        <div class="flex items-end gap-2">
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="show_all" 
                                       value="1"
                                       {{ ($filters['show_all'] ?? '') == '1' ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-600">Tampilkan semua</span>
                            </label>
                        </div>

                        <!-- Submit -->
                        <div class="flex items-end">
                            <button type="submit" 
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Cari
                            </button>
                            @if($filters['search'] || $filters['category'])
                                <a href="{{ route('catalog.index') }}" 
                                   class="ml-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Info -->
            <div class="mb-4 text-sm text-gray-600">
                Menampilkan {{ $books->count() }} dari {{ $books->total() }} buku
            </div>

            <!-- Book Grid -->
            @if($books->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($books as $book)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <!-- Category Badge -->
                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full bg-indigo-100 text-indigo-800 mb-2">
                                    {{ $book->category->name }}
                                </span>

                                <!-- Title -->
                                <h3 class="text-lg font-semibold text-gray-900 mb-1 line-clamp-2">
                                    <a href="{{ route('catalog.show', $book) }}" class="hover:text-indigo-600">
                                        {{ $book->title }}
                                    </a>
                                </h3>

                                <!-- Author -->
                                <p class="text-sm text-gray-600 mb-3">
                                    oleh {{ $book->author }}
                                </p>

                                <!-- Price & Availability -->
                                <div class="flex justify-between items-center mt-4 pt-4 border-t border-gray-100">
                                    <span class="text-lg font-bold text-indigo-600">
                                        Rp {{ number_format($book->rental_fee, 0, ',', '.') }}
                                    </span>
                                    
                                    @if($book->available_copies > 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ $book->available_copies }} tersedia
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Habis
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                <div class="mt-4">
                                    <a href="{{ route('catalog.show', $book) }}" 
                                       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $books->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada buku ditemukan</h3>
                        <p class="mt-2 text-sm text-gray-500">Coba ubah filter pencarian Anda.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
