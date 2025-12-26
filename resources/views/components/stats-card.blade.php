@props([
    'icon' => 'info',
    'label' => 'Label',
    'value' => '0',
    'trend' => null, // 'up', 'down', or null
    'trendValue' => null,
    'color' => 'primary' // primary, secondary, green, red, yellow
])

@php
    $colorClasses = match($color) {
        'primary' => 'bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-light',
        'secondary' => 'bg-secondary-accent/10 text-secondary-accent',
        'green' => 'bg-green-50 text-green-600 dark:bg-green-900/20 dark:text-green-400',
        'red' => 'bg-red-50 text-red-600 dark:bg-red-900/20 dark:text-red-400',
        'yellow' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-900/20 dark:text-yellow-400',
        default => 'bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-400',
    };
    
    $trendColorClass = $trend === 'up' ? 'text-green-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-500');
    $trendIcon = $trend === 'up' ? 'trending_up' : ($trend === 'down' ? 'trending_down' : null);
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-surface-dark rounded-2xl p-5 border border-gray-100 dark:border-white/5 shadow-soft hover:shadow-lg transition-all duration-300']) }}>
    <div class="flex items-start justify-between mb-4">
        <div class="w-12 h-12 rounded-xl {{ $colorClasses }} flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">{{ $icon }}</span>
        </div>
        @if($trend && $trendIcon)
            <div class="flex items-center gap-1 text-sm {{ $trendColorClass }}">
                <span class="material-symbols-rounded text-lg">{{ $trendIcon }}</span>
                @if($trendValue)
                    <span class="font-medium">{{ $trendValue }}</span>
                @endif
            </div>
        @endif
    </div>
    
    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">{{ $label }}</p>
    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
    
    {{ $slot }}
</div>
