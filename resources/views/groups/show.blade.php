@extends('layouts.app')

@section('title', $group->name)
@section('page-title', $group->name)

@section('content')
    <!-- Header Section -->
    <div class="mb-6 lg:mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('groups.index') }}"
                class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 text-sm">
                <i class="ri-arrow-left-line mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total de Membros</p>
                    <p class="text-xl lg:text-2xl font-bold">{{ $group->members->count() }}</p>
                </div>
                <div class="w-10 lg:w-12 lg:h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-group-line text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Criado por</p>
                    <p class="text-base lg:text-lg font-semibold truncate">{{ $group->creator->name }}</p>
                </div>
                <div class="w-10 lg:w-12 lg:h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-user-line text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Visibilidade</p>
                    <p class="text-base lg:text-lg font-semibold capitalize">{{ $group->visibility }}</p>
                </div>
                <div class="w-10 lg:w-12 lg:h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-eye-line text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
        <!-- Group Details -->
        <div class="bg-white rounded-2xl p-4 lg:p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h2 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2 sm:mb-0">Detalhes do Grupo</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                    @if ($group->visibility === 'public') bg-green-100 text-green-800
                    @else bg-gray-100 text-gray-800 @endif">
                    <div class="w-2 h-2 rounded-full mr-2
                        @if ($group->visibility === 'public') bg-green-500
                        @else bg-gray-500 @endif"></div>
                    {{ ucfirst($group->visibility) }}
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <div class="flex items-center text-sm text-gray-600">
                    <i class="ri-calendar-line mr-3 flex-shrink-0"></i>
                    <span>Criado em: <strong>{{ $group->created_at->format('d/m/Y') }}</strong></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="ri-user-line mr-3 flex-shrink-0"></i>
                    <span>Criado por: <strong>{{ $group->creator->name }}</strong></span>
                </div>
                <div class="flex items-center text-sm text-gray-600">
                    <i class="ri-group-line mr-3 flex-shrink-0"></i>
                    <span>Membros: <strong>{{ $group->members->count() }}</strong></span>
                </div>
            </div>

            @if ($group->description)
                <div class="p-6 bg-gray-50 rounded-2xl">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Descrição</h3>
                    <p class="text-gray-600">{{ $group->description }}</p>
                </div>
            @endif
        </div>

        <!-- Polls Section -->
        <div class="bg-white rounded-2xl p-4 lg:p-8 shadow-sm border border-gray-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
                <h3 class="text-lg lg:text-xl font-semibold text-gray-900 mb-2 sm:mb-0">Enquetes do Grupo</h3>
                <a href="{{ route('polls.index', $group) }}" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                    Ver todas
                </a>
            </div>

            @if($group->polls->count() > 0)
                <!-- Polls List -->
                <div class="space-y-4">
                    @foreach($group->polls->take(3) as $poll)
                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-900">{{ $poll->title }}</h4>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($poll->status === 'open') bg-green-100 text-green-800
                                    @elseif($poll->status === 'closed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    @if($poll->status === 'open') Ativa
                                    @elseif($poll->status === 'closed') Encerrada
                                    @else Rascunho @endif
                                </span>
                            </div>
                            @if($poll->description)
                                <p class="text-xs text-gray-600 mb-2">{{ Str::limit($poll->description, 100) }}</p>
                            @endif
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-xs text-gray-500">
                                    <span>Criada por {{ $poll->creator->name ?? 'Usuário' }}</span>
                                    <span>{{ $poll->created_at->format('d/m/Y') }}</span>
                                </div>
                                <a href="{{ route('polls.show', [$group, $poll->id]) }}" 
                                   class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                    Ver enquete →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State for Polls -->
                <div class="text-center py-8">
                    <div class="w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="bar-chart-3" class="w-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-500 mb-2">Nenhuma enquete ainda</p>
                    <p class="text-sm text-gray-400 mb-4">Crie a primeira enquete para o grupo</p>
                    <a href="{{ route('polls.create', $group) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                        <i data-lucide="plus" class="w-4 mr-2"></i>
                        Criar Enquete
                    </a>
                </div>
            @endif
        </div>

        <!-- Add Members Section -->
        <div class="bg-white rounded-xl p-4 lg:p-8 card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Adicionar Membros</h3>
            <form action="{{ route('groups.addMember', $group->id) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        E-mail do usuário <span class="text-red-500">*</span>
                    </label>
                    <input id="email"
                           name="email"
                           type="email"
                           required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="ex: usuario@exemplo.com">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <i data-lucide="user-plus" class="w-4 mr-2"></i>
                        Adicionar Membro
                    </button>
                </div>
            </form>
        </div>

        <!-- Members List -->
        <div class="bg-white rounded-xl p-4 lg:p-8 card-shadow border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Membros do Grupo</h3>

            @if ($group->members->count() > 0)
                <div class="space-y-3">
                    @foreach ($group->members as $member)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">{{ substr($member->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $member->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $member->email }}</p>
                                </div>
                            </div>
                            <form action="{{ route('groups.create', [$group->id, $member->id]) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        onclick="return confirm('Tem certeza que deseja remover este membro?')">
                                    <i data-lucide="user-minus" class="w-4"></i>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="w-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">Nenhum membro ainda</p>
                    <p class="text-sm text-gray-400">Adicione membros ao grupo</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
