@props(['label', 'value' => null])

<div {{ $attributes->merge(['class' => 'p-4 border rounded-lg dark:border-gray-700']) }}>
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $label }}</p>
    <p class="font-medium text-gray-800 dark:text-gray-100 mt-2">{{ $value ?? '—' }}</p>
</div>
