@extends('layouts.app')

@section('title', $group->name)
@section('page-title', $group->name)

@php
    $firstName = explode(' ', $group->creator->name)[0];
    $shortName = strlen($firstName) > 5 ? substr($firstName, 0, 5) . '...' : $firstName;
@endphp
@section('content')
    <!-- Breadcrumb -->
    <x-breadcrumb :items="[
        ['label' => 'Dashboard', 'url' => route('dashboard')],
        ['label' => 'Grupos', 'url' => route('groups.index')],
        ['label' => $group->name, 'url' => '#']
    ]" />

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
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-group-line text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-4 lg:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Criado por</p>
                    <p class="text-base lg:text-lg font-semibold truncate">{{ $shortName }}</p>
                </div>
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-full flex items-center justify-center">
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
                <div class="w-10 h-10 lg:w-12 lg:h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-eye-line text-lg lg:text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="space-y-6">
        <!-- Group Details -->
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl p-6 lg:p-8 shadow-lg border border-gray-200/50 backdrop-blur-sm">
            <!-- Header com gradiente -->
            <div class="mb-8">
                <!-- Mobile: Badge acima, título abaixo -->
                <div class="flex flex-col space-y-4 sm:hidden">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-md w-fit
                        @if ($group->visibility === 'public') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                        @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif">
                        <div class="w-2 h-2 rounded-full mr-2 bg-white/80"></div>
                        {{ ucfirst($group->visibility) }}
                    </span>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">Detalhes do Grupo</h2>
                        <p class="text-sm text-gray-500">Informações e estatísticas</p>
                    </div>
                </div>
                
                <!-- Desktop: Badge ao lado do título -->
                <div class="hidden sm:flex items-center space-x-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-md
                        @if ($group->visibility === 'public') bg-gradient-to-r from-green-500 to-emerald-500 text-white
                        @else bg-gradient-to-r from-gray-500 to-gray-600 text-white @endif">
                        <div class="w-2 h-2 rounded-full mr-2 bg-white/80"></div>
                        {{ ucfirst($group->visibility) }}
                    </span>
                    <div>
                        <h2 class="text-xl lg:text-2xl font-bold text-gray-900">Detalhes do Grupo</h2>
                        <p class="text-sm text-gray-500">Informações e estatísticas</p>
                    </div>
                </div>
            </div>

            <!-- Cards de informações -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-calendar-line text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Criado em</p>
                            <p class="text-lg font-bold text-gray-900">{{ $group->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-user-line text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Criado por</p>
                            <p class="text-lg font-bold text-gray-900">{{ $shortName }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-md border border-gray-100 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="ri-group-line text-white text-lg"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 font-medium">Membros</p>
                            <p class="text-lg font-bold text-gray-900">{{ $group->members->count() }}</p>
                        </div>
                    </div>
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
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl p-6 lg:p-8 shadow-lg border border-gray-200/50 backdrop-blur-sm">
            <!-- Header com gradiente -->
            <div class="mb-8">
                <!-- Mobile: Layout vertical -->
                <div class="flex flex-col space-y-4 sm:hidden">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="ri-bar-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Enquetes do Grupo</h3>
                            <p class="text-sm text-gray-500">Pesquisas e votações</p>
                        </div>
                    </div>
                    <div class="flex justify-start">
                        <a href="{{ route('polls.index', $group) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                            <i class="ri-eye-line mr-2"></i>
                            Ver todas
                        </a>
                    </div>
                </div>
                
                <!-- Desktop: Layout horizontal -->
                <div class="hidden sm:flex sm:items-center sm:justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg">
                            <i class="ri-bar-chart-line text-white text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl lg:text-2xl font-bold text-gray-900">Enquetes do Grupo</h3>
                            <p class="text-sm text-gray-500">Pesquisas e votações</p>
                        </div>
                    </div>
                    <a href="{{ route('polls.index', $group) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-full text-sm font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                        <i class="ri-eye-line mr-2"></i>
                        Ver todas
                    </a>
                </div>
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
        <div class="bg-gradient-to-br from-white to-gray-50 rounded-3xl p-6 lg:p-8 shadow-lg border border-gray-200/50 backdrop-blur-sm">
            <!-- Header com gradiente -->
            <div class="flex items-center space-x-3 mb-8">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="ri-user-add-line text-white text-xl"></i>
                </div>
                <div>
                    <h3 class="text-xl lg:text-2xl font-bold text-gray-900">Adicionar Membros</h3>
                    <p class="text-sm text-gray-500">Convide pessoas para o grupo</p>
                </div>
            </div>
            <!-- Formulário moderno -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-6 shadow-md border border-gray-100">
                <form action="{{ route('groups.addMember', $group->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">
                            <i class="ri-mail-line mr-2 text-indigo-500"></i>
                            E-mail do usuário <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   required
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-4 pl-12 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 outline-none bg-gray-50/50 backdrop-blur-sm @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                   placeholder="ex: usuario@exemplo.com">
                            <i class="ri-mail-line absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600 flex items-center">
                                <i class="ri-error-warning-line mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-8 py-4 rounded-2xl hover:shadow-lg transition-all duration-300 font-semibold flex items-center justify-center">
                            <i class="ri-user-add-line mr-2"></i>
                            Adicionar Membro
                        </button>
                    </div>
                </form>
            </div>
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
                                <button type="button"
                                        class="text-red-500 hover:text-red-700 transition-colors p-2 rounded-lg hover:bg-red-50"
                                        onclick="confirmRemoveMember('{{ $member->id }}', '{{ $member->name }}')">
                                    <i class="ri-user-unfollow-line w-4"></i>
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

    <script>
        function confirmRemoveMember(memberId, memberName) {
            openConfirmationModal(
                'Remover Membro',
                `Tem certeza que deseja remover ${memberName} do grupo?`,
                'Remover',
                function() {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/groups/{{ $group->id }}/members/${memberId}`;
                    
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
