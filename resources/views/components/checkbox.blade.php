@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'type' => 'checkbox',
    'required' => false
])

<div class="space-y-2">
    <label class="flex items-center cursor-pointer">
        <input 
            {{ $attributes->merge([
                'class' => 'rounded border-gray-300 text-purple-600 focus:ring-purple-500',
                'type' => $type
            ]) }}
        />
        @if($label)
            <span class="ml-2 text-sm text-gray-700">
                {{ $label }}
                @if($required)
                    <span class="text-red-500">*</span>
                @endif
            </span>
        @endif
    </label>
    
    @if($error)
        <p class="text-sm text-red-600">{{ $error }}</p>
    @elseif($help)
        <p class="text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>
