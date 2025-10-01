@props(['items' => []])

<!-- Desktop Breadcrumb -->
<nav class="hidden lg:flex items-center space-x-2 text-sm mb-6" aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @php
            $maxLength = 9;
            $label = $item['label'];
            $truncatedLabel = strlen($label) > $maxLength ? substr($label, 0, $maxLength) . '...' : $label;
        @endphp
        
        @if($index > 0)
            <i class="ri-arrow-right-s-line text-gray-400"></i>
        @endif
        
        @if($index === count($items) - 1)
            <span class="text-gray-600 font-medium">{{ $truncatedLabel }}</span>
        @else
            <a href="{{ $item['url'] }}" class="text-purple-600 hover:text-purple-700 font-medium transition-colors">
                {{ $truncatedLabel }}
            </a>
        @endif
    @endforeach
</nav>

<!-- Mobile Breadcrumb - Breadcrumb completo com truncamento -->
<nav class="lg:hidden flex items-center space-x-1 text-xs mb-6 overflow-x-auto" aria-label="Breadcrumb">
    @foreach($items as $index => $item)
        @php
            $mobileMaxLength = 8;
            $label = $item['label'];
            $mobileLabel = strlen($label) > $mobileMaxLength ? substr($label, 0, $mobileMaxLength) . '...' : $label;
        @endphp
        
        @if($index > 0)
            <i class="ri-arrow-right-s-line text-gray-400 text-xs"></i>
        @endif
        
        @if($index === count($items) - 1)
            <span class="text-gray-600 font-medium whitespace-nowrap">{{ $mobileLabel }}</span>
        @else
            <a href="{{ $item['url'] }}" class="text-purple-600 hover:text-purple-700 font-medium transition-colors whitespace-nowrap">
                {{ $mobileLabel }}
            </a>
        @endif
    @endforeach
</nav>
