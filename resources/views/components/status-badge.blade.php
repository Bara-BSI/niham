@php
    $colors = [
        'in_service'      => 'bg-green-100 text-green-800',
        'out_of_service' => 'bg-yellow-100 text-yellow-800',
        'disposed'     => 'bg-red-100 text-red-800',
        'default'     => 'bg-gray-100 text-gray-800',
    ];

    $color = $colors[$status] ?? $colors['default'];
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $color }}">
    {{ ucfirst(str_replace('_', ' ', $status)) }}
</span>
