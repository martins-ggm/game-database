@props(['name'])

{{--
    Biblioteca de ícones desenhados à mão (SVG por coordenadas — sem lib externa).

    Uso:  <x-icon name="escudo-check" class="w-5 h-5 text-[#6B5B9E]" />

    - Cada ícone é o "miolo" (os <path>) de um viewBox 24x24.
    - Monocromático: a cor vem do currentColor (controle pela classe text-*).
    - O traço/tamanho padrão dá pra sobrescrever passando classes.
--}}

@php
    $icones = [
        // escudo com um "check" dentro — usado como selo de administrador
        'escudo-check' =>
            '<path d="M12 3 5 6v5c0 4.2 2.8 7.6 7 8.8 4.2-1.2 7-4.6 7-8.8V6l-7-3Z"/>' .
            '<path d="m9 11.5 2 2 4-4.5"/>',
    ];
@endphp

@if (isset($icones[$name]))
    <svg {{ $attributes->merge(['class' => 'w-5 h-5']) }}
        viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
        aria-hidden="true">
        {!! $icones[$name] !!}
    </svg>
@endif
