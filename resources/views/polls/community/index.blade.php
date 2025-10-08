@extends('layouts.app')

@section('title', 'Enquetes')
@section('page-title', 'Enquetes')

@section('content')
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Enquetes', 'url' => '#'],
    ]" />

    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Enquetes</h1>
            <p class="text-gray-600 mt-2">Todas as enquetes disponíveis</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('public.polls.create') }}"
                class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm w-full sm:w-auto">
                <i class="ri-add-line mr-2"></i>
                Criar Nova Enquete
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total de Enquetes</p>
                    <p class="text-2xl font-bold">{{ $polls->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-bar-chart-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Enquetes Ativas</p>
                    <p class="text-2xl font-bold">{{ $polls->where('status', 'open')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-check-circle-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total de Votos</p>
                    <p class="text-2xl font-bold">0</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-user-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    @if ($polls->count() > 0)
        <!-- Polls List -->
        <div class="space-y-4 sm:space-y-6">
            @foreach ($polls as $poll)
                <div
                    class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between mb-4">
                        <div class="flex-1 mb-4 sm:mb-0">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg sm:text-xl font-semibold text-gray-900">{{ $poll->title }}</h3>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($poll->status === 'open') bg-green-100 text-green-800
                                    @elseif($poll->status === 'closed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if ($poll->status === 'open')
                                        Ativa
                                    @elseif($poll->status === 'closed')
                                        Encerrada
                                    @else
                                        Rascunho
                                    @endif
                                </span>
                            </div>
                            @if ($poll->description)
                                <p class="text-gray-600 text-sm sm:text-base mb-3">{{ $poll->description }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-4 text-xs sm:text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="ri-user-line w-4 mr-1"></i>
                                    <span>Criada por {{ $poll->creator->name ?? 'Usuário' }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="ri-calendar-line w-4 mr-1"></i>
                                    <span>{{ $poll->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                @if ($poll->end_at)
                                    <div class="flex items-center">
                                        <i class="ri-time-line w-4 mr-1"></i>
                                        <span>Encerra em {{ $poll->end_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <a href="{{ route('public.polls.show', $poll->id) }}"
                                class="inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                <i class="ri-eye-line w-4 mr-1"></i>
                                Ver Enquete
                            </a>

                            @if ($poll->creator_id === Auth::id())
                                @if ($poll->status === 'draft')
                                    <form action="{{ route('public.polls.publish', $poll->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                            <i class="ri-send-plane-line w-4 mr-1"></i>
                                            Publicar
                                        </button>
                                    </form>
                                @elseif($poll->status === 'open')
                                    <form action="{{ route('public.polls.close', $poll->id) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm"
                                            onclick="return confirm('Tem certeza que deseja encerrar esta enquete?')">
                                            <i class="ri-lock-line w-4 mr-1"></i>
                                            Encerrar
                                        </button>
                                    </form>
                                @endif

                                <button onclick="confirmDeletePoll('{{ $poll->id }}', '{{ $poll->title }}')"
                                    class="inline-flex items-center px-3 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm">
                                    <i class="ri-delete-bin-line w-4 mr-1"></i>
                                    Deletar
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Poll Options Preview -->
                    <div class="mt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Opções de Resposta:</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            @foreach ($poll->options as $option)
                                <div class="flex items-center space-x-2 p-2 bg-gray-50 rounded-lg">
                                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                    <span class="text-sm text-gray-700">{{ $option->text }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Poll Settings -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        @if ($poll->type === 'private')
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-orange-100 text-orange-800">
                                <i ri-lock-line class="w-3 h-3 mr-1"></i>
                                Privada
                            </span>
                        @endif
                        @if ($poll->anonymus)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                <i class="ri-eye-off-line w-3 mr-1"></i>
                                Anônima
                            </span>
                        @endif
                        @if ($poll->allow_multiple)
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                <i class="ri-checkbox-multiple-line w-3 mr-1"></i>
                                Múltipla Escolha
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-xl p-8 sm:p-12 card-shadow border border-gray-100">
            <div class="text-center">
                <div class="w-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ri-bar-chart-line w-8 sm:w-10 text-gray-400"></i>
                </div>
                <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-2">Nenhuma enquete ainda</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-6">Crie a primeira enquete</p>
                <a href="{{ route('public.polls.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i class="ri-add-line w-4 mr-2"></i>
                    Criar Enquete
                </a>
            </div>
        </div>
    @endif

    <script>
        function confirmDeletePoll(pollId, pollTitle) {
            openConfirmationModal(
                'Deletar Enquete',
                `Tem certeza que deseja deletar a enquete "${pollTitle}"? Esta ação é permanente e não pode ser desfeita.`,
                'Deletar',
                function() {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/polls/${pollId}`;

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            );
        }
    </script>
@endsection
