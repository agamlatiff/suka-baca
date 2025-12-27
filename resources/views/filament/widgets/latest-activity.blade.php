<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aktivitas Terbaru
        </x-slot>

        <div class="space-y-6">
            @foreach($activities as $activity)
                <div class="flex gap-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0">
                        @if($activity['type'] === 'borrowing')
                            <div class="h-10 w-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 dark:bg-blue-900/20 dark:text-blue-400">
                                @svg('heroicon-o-book-open', 'h-5 w-5')
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full bg-green-50 flex items-center justify-center text-green-600 dark:bg-green-900/20 dark:text-green-400">
                                @svg('heroicon-o-banknotes', 'h-5 w-5')
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $activity['user'] }}
                            </p>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $activity['time'] }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $activity['description'] }}
                        </p>
                        @if ($activity['status'])
                            <span @class([
                                'inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset',
                                'bg-green-50 text-green-700 ring-green-600/20' => $activity['status'] === 'active' || $activity['status'] === 'verified' || $activity['status'] === 'returned',
                                'bg-red-50 text-red-700 ring-red-600/20' => $activity['status'] === 'overdue' || $activity['status'] === 'rejected',
                                'bg-yellow-50 text-yellow-700 ring-yellow-600/20' => $activity['status'] === 'pending',
                            ])>
                                {{ ucfirst($activity['status']) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
            
            @if(count($activities) === 0)
                <div class="text-center text-sm text-gray-500 dark:text-gray-400 py-4">
                    Belum ada aktivitas terbaru.
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
