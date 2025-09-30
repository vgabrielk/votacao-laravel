@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'padding' => 'p-6',
    'shadow' => true,
    'border' => true
])

<div class="bg-white rounded-2xl {{ $shadow ? 'shadow-sm' : '' }} {{ $border ? 'border border-gray-100' : '' }} {{ $padding }}">
    @if($title || $actions)
        <div class="flex items-center justify-between mb-6">
            <div>
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-sm text-gray-600 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
            @if($actions)
                <div class="flex items-center space-x-2">
                    {{ $actions }}
                </div>
            @endif
        </div>
    @endif
    
    {{ $slot }}
</div>
