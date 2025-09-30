@extends('layouts.app')

@section('title', 'Enquete')
@section('page-title', 'Enquete')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $poll->title ?? 'Título da Enquete' }}</h1>
                <p class="text-gray-600 mt-1 text-sm sm:text-base">Enquete do grupo <strong>{{ $group->name }}</strong></p>
                @if(isset($poll) && $poll->description)
                    <p class="text-gray-600 mt-2 text-sm sm:text-base">{{ $poll->description }}</p>
                @endif
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('polls.index', $group) }}"
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Voltar às Enquetes
                </a>
                <a href="{{ route('polls.create', $group) }}"
                    class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Criar Enquete
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Total de Votos</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="clock" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Status</p>
                    <p class="text-lg font-semibold text-gray-900">Ativa</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="calendar" class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Criada em</p>
                    <p class="text-lg font-semibold text-gray-900">{{ date('d/m/Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
        <!-- Poll Details -->
        <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Opções de Resposta</h2>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <div class="w-1.5 h-1.5 rounded-full mr-1.5 bg-green-400"></div>
                    Ativa
                </span>
            </div>

            <!-- Poll Options -->
            @if($poll->status === 'open')
                <form id="pollVoteForm" action="{{ route('polls.vote', [$group, $poll->id]) }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        @foreach($poll->options as $option)
                            @php
                                $totalVotes = $poll->votes->count();
                                $optionVotes = $poll->votes->where('option_id', $option->id)->count();
                                $percentage = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100, 1) : 0;
                            @endphp
                            <label class="block border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors cursor-pointer poll-option" data-option-id="{{ $option->id }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <input type="radio" name="option_id" value="{{ $option->id }}" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                        <span class="text-gray-900">{{ $option->text }}</span>
                                    </div>
                                    <span class="text-sm text-gray-500">{{ $optionVotes }} votos ({{ $percentage }}%)</span>
                                </div>
                                <div class="mt-2 bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </form>
            @else
                <!-- Display results without voting -->
                <div class="space-y-4">
                    @foreach($poll->options as $option)
                        @php
                            $totalVotes = $poll->votes->count();
                            $optionVotes = $poll->votes->where('option_id', $option->id)->count();
                            $percentage = $totalVotes > 0 ? round(($optionVotes / $totalVotes) * 100, 1) : 0;
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-4 h-4 border-2 border-gray-300 rounded-full"></div>
                                    <span class="text-gray-900">{{ $option->text }}</span>
                                </div>
                                <span class="text-sm text-gray-500">{{ $optionVotes }} votos ({{ $percentage }}%)</span>
                            </div>
                            <div class="mt-2 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Vote Button -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                @if($poll->status === 'open')
                    <button type="submit" form="pollVoteForm" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i data-lucide="check" class="w-4 h-4 mr-2"></i>
                        Votar na Enquete
                    </button>
                @elseif($poll->status === 'draft')
                    <div class="flex flex-col sm:flex-row gap-3">
                        <form action="{{ route('polls.publish', [$group, $poll->id]) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                                <i data-lucide="send" class="w-4 h-4 mr-2"></i>
                                Publicar Enquete
                            </button>
                        </form>
                        <span class="text-sm text-gray-500 flex items-center">
                            <i data-lucide="info" class="w-4 h-4 mr-1"></i>
                            Esta enquete está em rascunho e não pode receber votos ainda.
                        </span>
                    </div>
                @elseif($poll->status === 'closed')
                    <div class="text-center py-4">
                        <span class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-600 rounded-lg">
                            <i data-lucide="lock" class="w-4 h-4 mr-2"></i>
                            Esta enquete foi encerrada
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Poll Settings Info -->
        <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configurações da Enquete</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="globe" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Visibilidade</p>
                        <p class="text-xs text-gray-500">
                            @if(isset($poll) && $poll->type === 'private')
                                Privada - Apenas convidados
                            @else
                                Pública - Todos no grupo
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="eye-off" class="w-4 h-4 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Anonimato</p>
                        <p class="text-xs text-gray-500">
                            @if(isset($poll) && $poll->anonymus)
                                Votação anônima
                            @else
                                Votos públicos
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="check-square" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Múltipla Escolha</p>
                        <p class="text-xs text-gray-500">
                            @if(isset($poll) && $poll->allow_multiple)
                                Permitida
                            @else
                                Não permitida
                            @endif
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="clock" class="w-4 h-4 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Prazo</p>
                        <p class="text-xs text-gray-500">
                            @if(isset($poll) && $poll->end_at)
                                Encerra em {{ $poll->end_at->format('d/m/Y H:i') }}
                            @else
                                Sem prazo definido
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poll Actions -->
        <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações da Enquete</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="share-2" class="w-4 h-4 text-blue-600"></i>
                    </div>
                    <span class="text-gray-700">Compartilhar</span>
                </button>
                <button class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-green-600"></i>
                    </div>
                    <span class="text-gray-700">Ver Resultados</span>
                </button>
                <button class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i data-lucide="settings" class="w-4 h-4 text-purple-600"></i>
                    </div>
                    <span class="text-gray-700">Configurações</span>
                </button>
                @if(isset($poll) && $poll->creator_id === Auth::id())
                    <form action="{{ route('polls.destroy', [$group, $poll->id]) }}" method="POST" class="sm:col-span-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-red-50 transition-colors text-red-600"
                            onclick="return confirm('Tem certeza que deseja deletar esta enquete?')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="trash-2" class="w-4 h-4 text-red-600"></i>
                            </div>
                            <span>Deletar Enquete</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar feedback visual ao selecionar uma opção
    const pollOptions = document.querySelectorAll('.poll-option');
    
    pollOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        
        option.addEventListener('click', function() {
            // Remover seleção de outras opções
            pollOptions.forEach(opt => {
                opt.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
            });
            
            // Adicionar seleção visual à opção clicada
            this.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
            radio.checked = true;
        });
        
        // Feedback visual quando o radio é clicado diretamente
        radio.addEventListener('change', function() {
            if (this.checked) {
                pollOptions.forEach(opt => {
                    opt.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
                });
                option.classList.add('ring-2', 'ring-blue-500', 'bg-blue-50');
            }
        });
    });
});
</script>
@endsection
