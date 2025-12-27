<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Aktivitas Terbaru
        </x-slot>

        <div class="space-y-6">
            @foreach($activities as $activity)
                <div class="flex gap-4" style="display: flex; gap: 1rem; align-items: flex-start; padding-bottom: 0.75rem; border-bottom: 1px solid #f3f4f6;">
                    <!-- Icon -->
                    <div class="flex-shrink-0" style="flex-shrink: 0;">
                        @if($activity['type'] === 'borrowing')
                            <div class="h-10 w-10 rounded-full" style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center;">
                                <x-heroicon-o-book-open style="width: 1.25rem; height: 1.25rem;" />
                            </div>
                        @else
                            <div class="h-10 w-10 rounded-full" style="width: 2.5rem; height: 2.5rem; border-radius: 9999px; background-color: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center;">
                                <x-heroicon-o-banknotes style="width: 1.25rem; height: 1.25rem;" />
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 space-y-1" style="flex: 1 1 0%; min-width: 0;">
                        <div class="flex items-center justify-between" style="display: flex; align-items: center; justify-content: space-between;">
                            <p class="text-sm font-medium" style="font-size: 0.875rem; font-weight: 500; color: #111827; margin: 0;">
                                {{ $activity['user'] }}
                            </p>
                            <span class="text-xs text-gray-500" style="font-size: 0.75rem; color: #6b7280;">
                                {{ $activity['time'] }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500" style="font-size: 0.875rem; color: #6b7280; margin: 0.25rem 0;">
                            {{ $activity['description'] }}
                        </p>
                        @if ($activity['status'])
                            @php
                                $statusColors = [
                                    'active' => 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;',
                                    'verified' => 'background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe;',
                                    'returned' => 'background-color: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;',
                                    'overdue' => 'background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;',
                                    'rejected' => 'background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca;',
                                    'pending' => 'background-color: #fefce8; color: #a16207; border: 1px solid #fde68a;',
                                ];
                                $style = $statusColors[$activity['status']] ?? 'background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb;';
                            @endphp
                            <span style="display: inline-flex; align-items: center; border-radius: 0.375rem; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 500; {{ $style }}">
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
            @else
                <div class="text-center mt-4 pt-4 border-t border-gray-100 dark:border-gray-800" style="text-align: center; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #f3f4f6;">
                    <x-filament::button color="gray" size="sm" wire:click="loadMore">
                        Load More
                    </x-filament::button>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
