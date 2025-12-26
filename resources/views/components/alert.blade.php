@props([
    'type' => 'info', // info, success, warning, danger
    'dismissible' => false,
    'icon' => null
])

@php
    $typeConfig = match($type) {
        'success' => [
            'bg' => 'bg-green-50 dark:bg-green-900/20',
            'border' => 'border-green-200 dark:border-green-800',
            'text' => 'text-green-700 dark:text-green-300',
            'icon' => $icon ?? 'check_circle',
            'iconColor' => 'text-green-600 dark:text-green-400',
        ],
        'warning' => [
            'bg' => 'bg-yellow-50 dark:bg-yellow-900/20',
            'border' => 'border-yellow-200 dark:border-yellow-800',
            'text' => 'text-yellow-700 dark:text-yellow-300',
            'icon' => $icon ?? 'warning',
            'iconColor' => 'text-yellow-600 dark:text-yellow-400',
        ],
        'danger' => [
            'bg' => 'bg-red-50 dark:bg-red-900/20',
            'border' => 'border-red-200 dark:border-red-800',
            'text' => 'text-red-700 dark:text-red-300',
            'icon' => $icon ?? 'error',
            'iconColor' => 'text-red-600 dark:text-red-400',
        ],
        default => [
            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
            'border' => 'border-blue-200 dark:border-blue-800',
            'text' => 'text-blue-700 dark:text-blue-300',
            'icon' => $icon ?? 'info',
            'iconColor' => 'text-blue-600 dark:text-blue-400',
        ],
    };
@endphp

<div 
    x-data="{ show: true }" 
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-y-2"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-y-0"
    x-transition:leave-end="opacity-0 transform -translate-y-2"
    {{ $attributes->merge(['class' => "rounded-xl p-4 border flex items-start gap-3 {$typeConfig['bg']} {$typeConfig['border']}"]) }}
>
    <span class="material-symbols-rounded {{ $typeConfig['iconColor'] }} mt-0.5">{{ $typeConfig['icon'] }}</span>
    
    <div class="flex-1 {{ $typeConfig['text'] }}">
        {{ $slot }}
    </div>
    
    @if($dismissible)
        <button 
            @click="show = false" 
            class="p-1 rounded-lg hover:bg-black/5 dark:hover:bg-white/5 transition-colors {{ $typeConfig['text'] }} opacity-70 hover:opacity-100"
        >
            <span class="material-symbols-rounded text-lg">close</span>
        </button>
    @endif
</div>
