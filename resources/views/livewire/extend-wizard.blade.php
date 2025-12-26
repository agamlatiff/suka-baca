<div>
    {{-- Step Indicator --}}
    <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-6 mb-8 border border-gray-100 dark:border-white/5">
        <ol class="flex items-center w-full text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base">
            <li class="flex md:w-full items-center {{ $currentStep >= 1 ? 'text-primary dark:text-secondary-accent' : '' }} sm:after:content-[''] after:w-full after:h-1 after:border-b {{ $currentStep > 1 ? 'after:border-primary dark:after:border-secondary-accent' : 'after:border-gray-200 dark:after:border-gray-700' }} after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                    <span class="me-2 rounded-full border {{ $currentStep >= 1 ? 'border-primary dark:border-secondary-accent bg-primary dark:bg-secondary-accent text-white' : 'border-gray-300 dark:border-gray-600' }} w-6 h-6 flex items-center justify-center text-xs">
                        @if($currentStep > 1)
                            <span class="material-symbols-rounded text-sm">check</span>
                        @else
                            1
                        @endif
                    </span>
                    Info
                </span>
            </li>
            <li class="flex md:w-full items-center {{ $currentStep >= 2 ? 'text-primary dark:text-secondary-accent' : '' }} sm:after:content-[''] after:w-full after:h-1 after:border-b {{ $currentStep > 2 ? 'after:border-primary dark:after:border-secondary-accent' : 'after:border-gray-200 dark:after:border-gray-700' }} after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                    <span class="me-2 rounded-full border {{ $currentStep >= 2 ? 'border-primary dark:border-secondary-accent bg-primary dark:bg-secondary-accent text-white' : 'border-gray-300 dark:border-gray-600' }} w-6 h-6 flex items-center justify-center text-xs">
                        @if($currentStep > 2)
                            <span class="material-symbols-rounded text-sm">check</span>
                        @else
                            2
                        @endif
                    </span>
                    Bayar
                </span>
            </li>
            <li class="flex md:w-full items-center {{ $currentStep >= 3 ? 'text-primary dark:text-secondary-accent' : '' }} sm:after:content-[''] after:w-full after:h-1 after:border-b {{ $currentStep > 3 ? 'after:border-primary dark:after:border-secondary-accent' : 'after:border-gray-200 dark:after:border-gray-700' }} after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10">
                <span class="flex items-center after:content-['/'] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500">
                    <span class="me-2 rounded-full border {{ $currentStep >= 3 ? 'border-primary dark:border-secondary-accent bg-primary dark:bg-secondary-accent text-white' : 'border-gray-300 dark:border-gray-600' }} w-6 h-6 flex items-center justify-center text-xs">
                        @if($currentStep > 3)
                            <span class="material-symbols-rounded text-sm">check</span>
                        @else
                            3
                        @endif
                    </span>
                    Upload
                </span>
            </li>
            <li class="flex items-center {{ $currentStep >= 4 ? 'text-primary dark:text-secondary-accent' : '' }}">
                <span class="me-2 rounded-full border {{ $currentStep >= 4 ? 'border-primary dark:border-secondary-accent bg-primary dark:bg-secondary-accent text-white' : 'border-gray-300 dark:border-gray-600' }} w-6 h-6 flex items-center justify-center text-xs">
                    @if($currentStep >= 4)
                        <span class="material-symbols-rounded text-sm">check</span>
                    @else
                        4
                    @endif
                </span>
                Selesai
            </li>
        </ol>
    </div>

    {{-- Flash Messages --}}
    @if(session()->has('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 border border-red-200 dark:border-red-800 flex items-center gap-3">
            <span class="material-symbols-rounded">error</span>
            {{ session('error') }}
        </div>
    @endif

    @php
        $book = $borrowing->bookCopy->book;
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Step 1: Info Konfirmasi --}}
            @if($currentStep === 1)
                <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-8 border border-gray-100 dark:border-white/5">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Konfirmasi Perpanjangan</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">
                        Anda akan menambahkan durasi peminjaman untuk buku yang sedang dipinjam. Mohon periksa detail durasi dan biaya di bawah ini.
                    </p>

                    <div class="bg-gray-50 dark:bg-white/5 rounded-2xl p-6 border border-gray-100 dark:border-white/5">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="p-2 bg-primary/10 dark:bg-secondary-accent/10 rounded-lg text-primary dark:text-secondary-accent">
                                <span class="material-symbols-rounded">update</span>
                            </div>
                            <h3 class="font-bold text-gray-900 dark:text-white">Perubahan Jatuh Tempo</h3>
                        </div>

                        <div class="flex flex-col sm:flex-row items-center justify-between gap-6 relative">
                            <div class="flex flex-col items-center sm:items-start text-center sm:text-left w-full">
                                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Saat Ini</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $currentDueDate->translatedFormat('d M') }}</p>
                                <p class="text-sm text-gray-500">{{ $currentDueDate->year }}</p>
                            </div>
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-10 h-10 rounded-full bg-white dark:bg-surface-dark border border-gray-200 dark:border-white/10 flex items-center justify-center text-primary dark:text-secondary-accent shadow-sm z-10">
                                    <span class="material-symbols-rounded">arrow_forward</span>
                                </div>
                                <span class="mt-2 text-xs font-bold text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-md whitespace-nowrap z-10">
                                    +{{ $extensionDays }} Hari
                                </span>
                            </div>
                            <div class="flex flex-col items-center sm:items-end text-center sm:text-right w-full">
                                <p class="text-xs font-semibold text-primary dark:text-secondary-accent uppercase tracking-wider mb-1">Baru</p>
                                <p class="text-2xl font-bold text-primary dark:text-secondary-accent">{{ $newDueDate->translatedFormat('d M') }}</p>
                                <p class="text-sm text-gray-500">{{ $newDueDate->year }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Biaya Perpanjangan ({{ $extensionDays }} Hari)</span>
                            <span class="font-semibold text-gray-900 dark:text-white">Rp {{ number_format($extensionFee, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Biaya Layanan</span>
                            <span class="font-bold text-green-600 dark:text-green-400">Gratis</span>
                        </div>
                        <div class="border-t border-dashed border-gray-200 dark:border-white/10 my-2"></div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-lg text-gray-900 dark:text-white">Total Tagihan</span>
                            <span class="font-bold text-2xl text-primary dark:text-secondary-accent">Rp {{ number_format($extensionFee, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Step 2: Pilih Metode Bayar --}}
            @if($currentStep === 2)
                <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-8 border border-gray-100 dark:border-white/5">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-full bg-secondary-accent/10 flex items-center justify-center text-secondary-accent">
                            <span class="material-symbols-rounded">payments</span>
                        </div>
                        <h3 class="text-2xl font-bold text-primary dark:text-white">Pilih Metode Pembayaran</h3>
                    </div>

                    <div class="space-y-4">
                        <label class="relative block cursor-pointer group">
                            <input wire:model="paymentMethod" type="radio" value="cash" class="peer sr-only">
                            <div class="p-6 rounded-2xl border-2 border-gray-100 dark:border-white/10 peer-checked:border-secondary-accent peer-checked:bg-secondary-accent/5 hover:border-primary/50 dark:hover:border-secondary-accent/50 transition-all duration-300 bg-gray-50/50 dark:bg-white/5 flex items-center gap-6">
                                <div class="w-14 h-14 rounded-xl bg-green-50 dark:bg-green-900/20 flex items-center justify-center text-green-600 dark:text-green-400 flex-shrink-0">
                                    <span class="material-symbols-rounded text-3xl">storefront</span>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 dark:text-white">Bayar Tunai di Tempat</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bayar langsung di kasir perpustakaan saat pengambilan tiket perpanjangan.</p>
                                </div>
                            </div>
                        </label>

                        <label class="relative block cursor-pointer group">
                            <input wire:model="paymentMethod" type="radio" value="transfer" class="peer sr-only">
                            <div class="p-6 rounded-2xl border-2 border-gray-100 dark:border-white/10 peer-checked:border-secondary-accent peer-checked:bg-secondary-accent/5 hover:border-primary/50 dark:hover:border-secondary-accent/50 transition-all duration-300 bg-gray-50/50 dark:bg-white/5 flex items-center gap-6">
                                <div class="w-14 h-14 rounded-xl bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                    <span class="material-symbols-rounded text-3xl">qr_code_scanner</span>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-lg text-gray-900 dark:text-white">Transfer / QRIS</h4>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-200">Rekomendasi</span>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Metode instan. Scan kode QR dan upload bukti pembayaran.</p>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            @endif

            {{-- Step 3: Upload Bukti --}}
            @if($currentStep === 3)
                <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-8 border border-gray-100 dark:border-white/5">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary/10 dark:bg-primary/30 flex items-center justify-center text-primary dark:text-secondary-accent">
                                <span class="material-symbols-rounded">cloud_upload</span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Upload Bukti Pembayaran</h2>
                        </div>
                    </div>

                    <div class="text-center py-10">
                        <div wire:loading wire:target="proofFile" class="mb-4">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary mx-auto"></div>
                            <p class="text-sm text-gray-500 mt-2">Uploading...</p>
                        </div>

                        <div wire:loading.remove wire:target="proofFile">
                            @if($proofFile)
                                <div class="mb-4">
                                    <img src="{{ $proofFile->temporaryUrl() }}" class="max-h-64 mx-auto rounded-xl shadow-lg">
                                </div>
                            @else
                                <div class="w-20 h-20 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center mx-auto mb-4">
                                    <span class="material-symbols-rounded text-4xl text-gray-400">cloud_upload</span>
                                </div>
                            @endif
                        </div>

                        <label class="inline-block cursor-pointer">
                            <input type="file" wire:model="proofFile" accept="image/*" class="hidden">
                            <span class="px-6 py-3 bg-primary hover:bg-primary-light text-white font-bold rounded-xl transition-colors inline-flex items-center gap-2">
                                <span class="material-symbols-rounded">add_photo_alternate</span>
                                {{ $proofFile ? 'Ganti Foto' : 'Pilih Foto Bukti' }}
                            </span>
                        </label>
                        
                        <p class="text-xs text-gray-400 mt-4">Format: JPG, PNG. Maksimal 2MB</p>

                        @error('proofFile')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- Step 4: Selesai --}}
            @if($currentStep === 4)
                <div class="bg-white dark:bg-surface-dark rounded-3xl shadow-xl shadow-gray-200/50 dark:shadow-black/30 p-8 border border-gray-100 dark:border-white/5 text-center">
                    <div class="w-24 h-24 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center mx-auto mb-6">
                        <span class="material-symbols-rounded text-5xl text-green-600 dark:text-green-400">check_circle</span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Perpanjangan Berhasil!</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8">
                        Jatuh tempo peminjaman Anda telah diperpanjang.
                    </p>

                    <div class="bg-gray-50 dark:bg-white/5 rounded-xl p-6 mb-8 text-left">
                        <div class="flex items-center gap-4 mb-4">
                            <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('placeholder-book.jpg') }}" 
                                alt="{{ $book->title }}" 
                                class="w-16 h-20 object-cover rounded-lg">
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">{{ $book->title }}</h3>
                                <p class="text-sm text-gray-500">{{ $book->author }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-400 text-xs">Jatuh Tempo Baru</p>
                                <p class="font-medium text-primary dark:text-secondary-accent">{{ $newDueDate->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs">Total Biaya</p>
                                <p class="font-bold text-primary dark:text-secondary-accent">Rp {{ number_format($extensionFee, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('borrowings.index') }}" class="px-6 py-3 bg-primary hover:bg-primary-light text-white font-bold rounded-xl transition-colors inline-flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded">library_books</span>
                            Lihat Pinjaman Saya
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gray-100 dark:bg-white/10 hover:bg-gray-200 dark:hover:bg-white/20 text-gray-700 dark:text-gray-200 font-bold rounded-xl transition-colors inline-flex items-center justify-center gap-2">
                            <span class="material-symbols-rounded">dashboard</span>
                            Ke Dashboard
                        </a>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="lg:col-span-1">
            <div class="sticky top-28 space-y-6">
                {{-- Book Info Card --}}
                <div class="bg-white dark:bg-surface-dark rounded-2xl p-6 border border-gray-100 dark:border-white/5 shadow-xl">
                    <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-100 dark:border-white/5">
                        <div class="w-16 h-24 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0 shadow-md">
                            <img src="{{ $book->image ? asset('storage/' . $book->image) : asset('placeholder-book.jpg') }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <span class="text-[10px] font-bold tracking-wider text-secondary-accent uppercase mb-1 block">Perpanjangan</span>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-2 leading-tight mb-1">{{ $book->title }}</h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $book->author }}</p>
                        </div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Kode Pinjam</span>
                            <span class="font-mono font-bold text-gray-700 dark:text-gray-200">#{{ $borrowing->borrowing_code }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500 dark:text-gray-400">Tgl Pinjam</span>
                            <span class="font-medium text-gray-700 dark:text-gray-200">{{ $borrowing->borrowed_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                @if($currentStep < 4)
                    <div class="bg-primary dark:bg-surface-dark rounded-3xl shadow-xl shadow-primary/20 p-6 text-white relative overflow-hidden border border-white/10">
                        <div class="absolute -top-10 -right-10 w-40 h-40 bg-secondary-accent/20 rounded-full blur-3xl"></div>
                        <div class="relative z-10">
                            <h3 class="font-bold text-xl mb-4 flex items-center gap-2">
                                <span class="material-symbols-rounded text-secondary-accent">receipt_long</span>
                                Total Pembayaran
                            </h3>
                            <div class="text-3xl font-extrabold text-secondary-accent mb-6">
                                Rp {{ number_format($extensionFee, 0, ',', '.') }}
                            </div>

                            @if($currentStep > 1)
                                <button wire:click="previousStep" class="w-full mb-3 bg-white/10 hover:bg-white/20 text-white font-bold py-3 px-6 rounded-xl transition-all flex items-center justify-center gap-2">
                                    <span class="material-symbols-rounded">arrow_back</span>
                                    Kembali
                                </button>
                            @endif

                            <button wire:click="nextStep" class="w-full bg-secondary-accent hover:bg-yellow-500 text-primary font-bold py-4 px-6 rounded-xl transition-all shadow-lg shadow-yellow-500/20 transform hover:-translate-y-1 flex items-center justify-between group">
                                <span>
                                    @if($currentStep === 1)
                                        Lanjut ke Pembayaran
                                    @elseif($currentStep === 2)
                                        {{ $paymentMethod === 'cash' ? 'Konfirmasi' : 'Lanjut Upload Bukti' }}
                                    @elseif($currentStep === 3)
                                        Selesaikan
                                    @endif
                                </span>
                                <span class="bg-black/10 rounded-full p-1 group-hover:bg-black/20 transition-colors">
                                    <span class="material-symbols-rounded text-xl block group-hover:translate-x-0.5 transition-transform">arrow_forward</span>
                                </span>
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
