<a {{ $attributes->merge(['class' => 'px-3 py-1 text-xs 
                                        rounded-full 
                                        bg-brown-100 
                                        hover:bg-brown-200 
                                        dark:bg-cyan-100 
                                        hover:dark:bg-cyan-200
                                        text-brown-700
                                        dark:text-cyan-900
                                        cursor-pointer']) }}>
    {{ $slot }}
</a>