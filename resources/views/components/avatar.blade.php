@props([
    'name' => '',
    'email' => '',
    'size' => 'md',
    'showStatus' => false,
    'status' => 'online',
    'src' => null
])

@php
    $sizes = [
        'sm' => 'w-8 h-8 text-sm',
        'md' => 'w-10 h-10 text-base',
        'lg' => 'w-12 h-12 text-lg',
        'xl' => 'w-16 h-16 text-xl'
    ];
    
    $statusColors = [
        'online' => 'bg-green-500',
        'offline' => 'bg-gray-400',
        'away' => 'bg-yellow-500',
        'busy' => 'bg-red-500'
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $statusColor = $statusColors[$status] ?? $statusColors['online'];
    $initials = strtoupper(substr($name, 0, 1) . substr($name, strpos($name, ' ') + 1, 1));
@endphp

<div class="relative">
    <div class="{{ $sizeClass }} rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
        @if($src)
            <img src="{{ $src }}" alt="{{ $name }}" class="w-full h-full object-cover">
        @else
            <span class="text-gray-500 font-medium">{{ $initials }}</span>
        @endif
    </div>
    @if($showStatus)
        <div class="absolute -bottom-1 -right-1 w-3 h-3 {{ $statusColor }} rounded-full border-2 border-white"></div>
    @endif
</div>
