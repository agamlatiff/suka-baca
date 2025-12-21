<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900">
                    Selamat datang, {{ auth()->user()->name }}!
                </h1>
                <p class="text-gray-600">Berikut ringkasan peminjaman kamu.</p>
            </div>

            <!-- Alerts -->
            @if($stats['overdue_count'] > 0)
                <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded" role="alert">
                    <div class="flex">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold">Perhatian!</p>
                            <p>Kamu punya {{ $stats['overdue_count'] }} buku yang sudah melewati jatuh tempo. Segera kembalikan untuk menghindari denda.</p>
                        </div>
                    </div>
                </div>
            @endif

            @if($stats['due_soon_count'] > 0 && $stats['overdue_count'] == 0)
                <div class="mb-6 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded" role="alert">
                    <div class="flex">
                        <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold">Pengingat</p>
                            <p>{{ $stats['due_soon_count'] }} buku akan jatuh tempo dalam 3 hari ke depan.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Peminjaman Aktif</div>
                        <div class="mt-1 text-3xl font-semibold text-indigo-600">{{ $stats['active_count'] }}</div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Segera Jatuh Tempo</div>
                        <div class="mt-1 text-3xl font-semibold {{ $stats['due_soon_count'] > 0 ? 'text-yellow-600' : 'text-gray-900' }}">
                            {{ $stats['due_soon_count'] }}
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Terlambat</div>
                        <div class="mt-1 text-3xl font-semibold {{ $stats['overdue_count'] > 0 ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $stats['overdue_count'] }}
                        </div>
                    </div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-sm font-medium text-gray-500">Biaya Tertunggak</div>
                        <div class="mt-1 text-3xl font-semibold {{ $stats['outstanding_fees'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                            Rp {{ number_format($stats['outstanding_fees'], 0, ',', '.') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Borrowings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Peminjaman Aktif</h3>
                        <a href="{{ route('borrowings.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($activeBorrowings->count() > 0)
                        <div class="space-y-4">
                            @foreach($activeBorrowings as $borrowing)
                                @php
                                    $dueDate = \Carbon\Carbon::parse($borrowing->due_date);
                                    $daysLeft = now()->diffInDays($dueDate, false);
                                    $isOverdue = $daysLeft < 0;
                                @endphp
                                <div class="flex items-center justify-between p-4 border rounded-lg {{ $isOverdue ? 'bg-red-50 border-red-200' : ($daysLeft <= 3 ? 'bg-yellow-50 border-yellow-200' : 'bg-gray-50') }}">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">{{ $borrowing->book->title }}</h4>
                                        <p class="text-sm text-gray-500">{{ $borrowing->code }} • {{ $borrowing->bookCopy->copy_code }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($isOverdue)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Terlambat {{ abs($daysLeft) }} hari
                                            </span>
                                        @elseif($daysLeft <= 3)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                {{ $daysLeft }} hari lagi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                {{ $daysLeft }} hari lagi
                                            </span>
                                        @endif
                                        <p class="text-xs text-gray-500 mt-1">
                                            Jatuh tempo: {{ $dueDate->format('d M Y') }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            <h3 class="mt-4 text-sm font-medium text-gray-900">Tidak ada peminjaman aktif</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan meminjam buku dari katalog.</p>
                            <div class="mt-4">
                                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700">
                                    Jelajahi Katalog
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent History -->
            @if($recentHistory->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Riwayat Terakhir</h3>
                        <div class="space-y-3">
                            @foreach($recentHistory as $history)
                                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $history->book->title }}</p>
                                        <p class="text-sm text-gray-500">Dikembalikan {{ \Carbon\Carbon::parse($history->returned_at)->diffForHumans() }}</p>
                                    </div>
                                    <div class="text-right">
                                        @if($history->is_paid)
                                            <span class="text-green-600 text-sm">Lunas</span>
                                        @else
                                            <span class="text-red-600 text-sm">Rp {{ number_format($history->total_fee, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-8 text-center">
                <a href="{{ route('catalog.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Jelajahi Katalog Buku
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
