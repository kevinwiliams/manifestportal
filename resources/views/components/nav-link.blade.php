@props(['active'])

@php
$classes = $active
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-blue-600 text-sm font-medium text-blue-700'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium text-gray-600 hover:text-gray-800 hover:border-gray-300';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
