@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'iconPosition' => 'left',
    'disabled' => false,
    'href' => null,
    'type' => 'button'
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer';
    
    $variants = [
        'primary' => 'bg-purple-600 text-white hover:bg-purple-700',
        'secondary' => 'bg-gray-100 text-gray-900 hover:bg-gray-200',
        'outline' => 'border border-gray-300 text-gray-700 hover:bg-gray-50',
        'ghost' => 'text-gray-600 hover:bg-gray-100',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'success' => 'bg-green-600 text-white hover:bg-green-700',
        'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700'
    ];
    
    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-sm',
        'lg' => 'px-6 py-3 text-sm',
        'xl' => 'px-8 py-4 text-base'
    ];
    
    $classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
    
    if($disabled) {
        $classes .= ' disabled:opacity-50 disabled:cursor-not-allowed';
    }
@endphp

@if($href)
    <a href="{{ $href }}" class="{{ $classes }}" @if($disabled) aria-disabled="true" @endif>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" class="{{ $classes }}" @if($disabled) disabled @endif>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif
