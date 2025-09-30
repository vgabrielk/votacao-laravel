@props([
    'status' => 'active',
    'text' => '',
    'showDot' => true
])

@php
    $statusClasses = [
        'active' => 'bg-green-100 text-green-800',
        'inactive' => 'bg-gray-100 text-gray-800',
        'pending' => 'bg-yellow-100 text-yellow-800',
        'public' => 'bg-green-100 text-green-800',
        'private' => 'bg-gray-100 text-gray-800',
        'open' => 'bg-green-100 text-green-800',
        'closed' => 'bg-red-100 text-red-800',
        'draft' => 'bg-gray-100 text-gray-800',
        'accepted' => 'bg-green-100 text-green-800',
        'rejected' => 'bg-red-100 text-red-800'
    ];
    
    $dotColors = [
        'active' => 'bg-green-500',
        'inactive' => 'bg-gray-500',
        'pending' => 'bg-yellow-500',
        'public' => 'bg-green-500',
        'private' => 'bg-gray-500',
        'open' => 'bg-green-500',
        'closed' => 'bg-red-500',
        'draft' => 'bg-gray-500',
        'accepted' => 'bg-green-500',
        'rejected' => 'bg-red-500'
    ];
    
    $classes = $statusClasses[$status] ?? 'bg-gray-100 text-gray-800';
    $dotColor = $dotColors[$status] ?? 'bg-gray-500';
@endphp

<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $classes }}">
    @if($showDot)
        <div class="w-2 h-2 rounded-full mr-2 {{ $dotColor }}"></div>
    @endif
    {{ $text ?: ucfirst($status) }}
</span>
