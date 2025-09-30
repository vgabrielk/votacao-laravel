@props(['paginator'])

<div class="flex items-center justify-between mt-6 px-6 py-4">
    <div class="text-sm text-gray-600">
        Mostrando <span class="font-medium">{{ $paginator->firstItem() ?? 0 }}</span> a <span class="font-medium">{{ $paginator->lastItem() ?? 0 }}</span> de <span class="font-medium">{{ $paginator->total() }}</span> resultados
    </div>
    <div class="flex items-center space-x-2">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage() || !$paginator->hasPages())
            <button class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 p-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <i class="ri-arrow-left-s-line"></i>
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 p-2 text-sm">
                <i class="ri-arrow-left-s-line"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center space-x-1">
            @if($paginator->hasPages())
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <button class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-3 py-2 text-sm">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-2 text-sm">{{ $page }}</a>
                    @endif
                @endforeach
            @else
                <button class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-3 py-2 text-sm">1</button>
            @endif
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 p-2 text-sm">
                <i class="ri-arrow-right-s-line"></i>
            </a>
        @else
            <button class="inline-flex items-center justify-center font-medium rounded-lg transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 p-2 text-sm disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <i class="ri-arrow-right-s-line"></i>
            </button>
        @endif
    </div>
</div>
