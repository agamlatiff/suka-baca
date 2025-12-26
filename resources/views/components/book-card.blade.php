@props([
    'book',
    'showActions' => true
])

@php
    $imageUrl = $book->image ? asset('storage/' . $book->image) : asset('placeholder-book.jpg');
@endphp

<div {{ $attributes->merge(['class' => 'group bg-white dark:bg-surface-dark rounded-2xl border border-gray-100 dark:border-white/5 overflow-hidden hover:shadow-xl hover:shadow-gray-200/50 dark:hover:shadow-black/30 transition-all duration-500 hover:-translate-y-1']) }}>
    {{-- Book Cover --}}
    <a href="{{ route('catalog.show', $book) }}" class="block relative aspect-[2/3] overflow-hidden">
        <img src="{{ $imageUrl }}" 
            alt="{{ $book->title }}" 
            class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
        
        {{-- Category Badge --}}
        <div class="absolute top-3 left-3">
            <span class="bg-secondary-accent text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-lg flex items-center gap-1">
                <span class="material-symbols-rounded text-xs">{{ $book->category->icon ?? 'category' }}</span>
                {{ $book->category->name ?? 'Umum' }}
            </span>
        </div>

        {{-- Availability Badge --}}
        @if($book->available_copies < 1)
            <div class="absolute top-3 right-3">
                <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-1 rounded-full">
                    Stok Habis
                </span>
            </div>
        @endif

        {{-- Hover Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
    </a>

    {{-- Book Info --}}
    <div class="p-4">
        <a href="{{ route('catalog.show', $book) }}" class="block">
            <h3 class="font-bold text-gray-900 dark:text-white line-clamp-2 leading-tight mb-1 group-hover:text-primary dark:group-hover:text-secondary-accent transition-colors">
                {{ $book->title }}
            </h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-3">{{ $book->author }}</p>
        </a>

        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-400 mb-0.5">Sewa / Minggu</p>
                <p class="text-lg font-bold text-secondary-accent">Rp {{ number_format($book->rental_fee, 0, ',', '.') }}</p>
            </div>

            @if($showActions)
                <div class="flex items-center gap-2">
                    @auth
                        <form action="{{ route('wishlist.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 dark:bg-white/10 hover:bg-red-50 dark:hover:bg-red-900/20 text-gray-400 hover:text-red-500 transition-colors">
                                <span class="material-symbols-rounded text-lg">favorite</span>
                            </button>
                        </form>
                    @endauth
                </div>
            @endif
        </div>
    </div>
</div>
