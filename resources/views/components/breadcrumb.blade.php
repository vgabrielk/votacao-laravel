@props(['items' => []])

<nav class="flex items-center space-x-2 text-sm mb-6" aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @if($index > 0)
            <i class="ri-arrow-right-s-line text-gray-400"></i>
        @endif
        
        @if($index === count($items) - 1)
            <!-- Current page -->
            <span class="text-gray-600 font-medium">{{ $item['label'] }}</span>
        @else
            <!-- Link to previous page -->
            <a href="{{ $item['url'] }}" class="text-purple-600 hover:text-purple-700 font-medium transition-colors">
                {{ $item['label'] }}
            </a>
        @endif
    @endforeach
</nav>
