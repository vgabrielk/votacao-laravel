@props(['headers' => [], 'maxHeight' => 'max-h-96'])

<div class="border border-gray-200 rounded-lg overflow-hidden">
    <div class="{{ $maxHeight }} overflow-y-auto">
        <table class="w-full border-collapse" style="min-width: 800px;">
            <thead class="bg-gray-50 sticky top-0 z-10">
                <tr class="border-b border-gray-200">
                    @foreach($headers as $header)
                        <th class="text-left py-4 px-6 font-medium text-gray-700 whitespace-nowrap bg-gray-50">
                            @if(isset($header['sortable']) && $header['sortable'])
                                <button class="flex items-center space-x-1 cursor-pointer">
                                    <span>{{ $header['label'] }}</span>
                                    <i class="ri-arrow-up-down-line text-xs"></i>
                                </button>
                            @else
                                {{ $header['label'] }}
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>
