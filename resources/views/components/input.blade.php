@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'icon' => null,
    'iconPosition' => 'left',
    'type' => 'text',
    'required' => false
])

<div class="space-y-2">
    @if($label)
        <label class="block text-sm font-medium text-gray-700">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        @if($icon && $iconPosition === 'left')
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
        @endif
        
        <input 
            {{ $attributes->merge([
                'class' => 'w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors' . ($icon && $iconPosition === 'left' ? ' pl-10' : '') . ($icon && $iconPosition === 'right' ? ' pr-10' : ''),
                'type' => $type
            ]) }}
        />
        
        @if($icon && $iconPosition === 'right')
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
        @endif
    </div>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
