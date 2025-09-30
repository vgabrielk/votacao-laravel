@props([
    'title' => '',
    'value' => '',
    'icon' => '',
    'gradient' => 'from-blue-500 to-blue-600',
    'textColor' => 'text-blue-100',
    'description' => null
])

<div class="bg-gradient-to-r {{ $gradient }} rounded-2xl p-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="{{ $textColor }} text-sm">{{ $title }}</p>
            <p class="text-2xl font-bold">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
            <i class="{{ $icon }} text-xl"></i>
        </div>
    </div>
    @if($description)
        <div class="flex items-center mt-2">
            <i class="ri-time-line {{ $textColor }} mr-1"></i>
            <span class="{{ $textColor }} text-sm">{{ $description }}</span>
        </div>
    @endif
</div>
