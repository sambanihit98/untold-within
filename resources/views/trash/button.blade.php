@props(['background' => 'solid'])

@if ($background === 'transparent')
    <a {{ $attributes->merge(['class' => 'px-6 py-3 rounded-lg border border-[#A67C52] text-[#A67C52] font-medium hover:bg-[#F3E8D9] transition']) }}>
        {{ $slot }}
    </a>
@else
    <a {{ $attributes->merge(['class' => 'px-6 py-3 rounded-lg bg-brown-600 dark:bg-cyan-600 hover:dark:bg-cyan-500 text-white font-medium hover:bg-brown-700 transition']) }}>
        {{ $slot }}
    </a>
@endif
