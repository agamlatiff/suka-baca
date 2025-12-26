@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-gray-50 dark:bg-white/5 border-gray-200 dark:border-white/10 rounded-xl focus:ring-primary focus:border-primary text-sm dark:text-white dark:placeholder-gray-500 transition-all shadow-sm']) }}>
