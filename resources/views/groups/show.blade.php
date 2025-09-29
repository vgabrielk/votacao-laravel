@extends('layouts.app')

@section('title', $group->name)
@section('page-title', $group->name)

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                <i data-lucide="users-2" class="w-8 h-8 text-white"></i>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $group->name }}</h1>
                <p class="text-gray-600">{{ $group->description }}</p>
            </div>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('groups.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Voltar
            </a>
            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Criar Grupo
            </a>
        </div>
    </div>

    <!-- Group Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total de Membros</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $group->members->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="user" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Criado por</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $group->creator->name }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="eye" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Visibilidade</p>
                    <p class="text-lg font-semibold text-gray-900 capitalize">{{ $group->visibility }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Group Details -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Detalhes do Grupo</h2>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($group->visibility === 'public') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        <div class="w-1.5 h-1.5 rounded-full mr-1.5
                            @if($group->visibility === 'public') bg-green-400
                            @else bg-gray-400 @endif"></div>
                        {{ ucfirst($group->visibility) }}
                    </span>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4 mr-3"></i>
                        <span>Criado em: <strong>{{ $group->created_at->format('d/m/Y H:i') }}</strong></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="user" class="w-4 h-4 mr-3"></i>
                        <span>Criado por: <strong>{{ $group->creator->name }}</strong></span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i data-lucide="users" class="w-4 h-4 mr-3"></i>
                        <span>Membros: <strong>{{ $group->members->count() }}</strong></span>
                    </div>
                </div>

                @if($group->description)
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Descrição</h3>
                    <p class="text-gray-600">{{ $group->description }}</p>
                </div>
                @endif
            </div>

            <!-- Add Members Section -->
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Adicionar Membros</h3>
                <form action="{{ route('groups.create', $group->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="flex flex-col sm:flex-row gap-4">
                        <select name="user_id" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecione um usuário</option>
                            {{-- @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach --}}
                        </select>
                        <button type="submit" class="inline-flex items-center px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                            Adicionar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Members List -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Membros do Grupo</h3>
                
                @if($group->members->count() > 0)
                <div class="space-y-3">
                    @foreach ($group->members as $member)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
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
                            <button type="submit" class="text-red-500 hover:text-red-700 transition-colors p-1" 
                                    onclick="return confirm('Tem certeza que deseja remover este membro?')">
                                <i data-lucide="user-minus" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">Nenhum membro ainda</p>
                    <p class="text-sm text-gray-400">Adicione membros ao grupo</p>
                </div>
                @endif
            </div>

            <!-- Group Actions -->
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações do Grupo</h3>
                <div class="space-y-3">
                    <button class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="message-circle" class="w-4 h-4 text-blue-600"></i>
                        </div>
                        <span class="text-gray-700">Enviar Mensagem</span>
                    </button>
                    <button class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="settings" class="w-4 h-4 text-green-600"></i>
                        </div>
                        <span class="text-gray-700">Configurações</span>
                    </button>
                    @if($group->creator_id === Auth::id())
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center space-x-3 p-3 rounded-lg hover:bg-red-50 transition-colors text-red-600" 
                                onclick="return confirm('Tem certeza que deseja deletar este grupo?')">
                            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="trash-2" class="w-4 h-4 text-red-600"></i>
                            </div>
                            <span>Deletar Grupo</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
