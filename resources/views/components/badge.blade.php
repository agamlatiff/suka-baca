@props([
    'type' => 'default', // default, success, warning, danger, info
    'size' => 'md', // sm, md, lg
    'dot' => false,
    'icon' => null
])

@php
    $typeClasses = match($type) {
        'success' => 'bg-green-100 text-green-700 border-green-200 dark:bg-green-900/30 dark:text-green-400 dark:border-green-800',
        'warning' => 'bg-yellow-100 text-yellow-700 border-yellow-200 dark:bg-yellow-900/30 dark:text-yellow-400 dark:border-yellow-800',
        'danger' => 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-400 dark:border-red-800',
        'info' => 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-400 dark:border-blue-800',
        'primary' => 'bg-primary/10 text-primary border-primary/20 dark:bg-primary/20 dark:text-primary-light dark:border-primary/30',
        'secondary' => 'bg-secondary-accent/10 text-secondary-accent border-secondary-accent/20',
        default => 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-white/10 dark:text-gray-300 dark:border-white/10',
    };
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px]',
        'lg' => 'px-4 py-2 text-sm',
        default => 'px-3 py-1 text-xs',
    };
    
    $dotColors = match($type) {
        'success' => 'bg-green-500',
        'warning' => 'bg-yellow-500',
        'danger' => 'bg-red-500',
        'info' => 'bg-blue-500',
        'primary' => 'bg-primary dark:bg-primary-light',
        'secondary' => 'bg-secondary-accent',
        default => 'bg-gray-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center gap-1.5 font-bold rounded-full border {$typeClasses} {$sizeClasses}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColors }} {{ $type === 'warning' || $type === 'danger' ? 'animate-pulse' : '' }}"></span>
    @endif
    
    @if($icon)
        <span class="material-symbols-rounded text-sm">{{ $icon }}</span>
    @endif
    
    {{ $slot }}
</span>
