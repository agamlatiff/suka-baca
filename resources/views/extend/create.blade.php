@section('title', 'Perpanjangan Buku')

<x-app-layout>
    <div class="pt-28 pb-20 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @livewire('extend-wizard', ['borrowing' => $borrowing])
        </div>
    </div>
</x-app-layout>
