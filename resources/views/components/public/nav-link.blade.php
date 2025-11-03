@props(['active' => false, 'element' => 'anchor'])

@if ($element === 'anchor')
    
    <a {{ $attributes->merge([
        'class'  => ($active ? 'text-brown-600 dark:text-cyan-600 font-bold' : 'text-gray-700 dark:text-white') . ' transition-colors duration-300 hover:text-brown-600 dark:hover:text-cyan-400'
    ]) }}>
        {{ $slot }}
    </a>

@elseif ($element === 'button')

 <a {{ $attributes->merge([
        'class'  => 'px-4 py-2 rounded-lg bg-brown-600 dark:bg-cyan-600 text-white font-medium hover:bg-brown-700 dark:hover:bg-cyan-500 transition'
    ]) }}>
        {{ $slot }}
    </a>
    
@endif

