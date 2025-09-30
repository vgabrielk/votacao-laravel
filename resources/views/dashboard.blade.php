@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Welcome Section -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Bem-vindo de volta, {{ $user->name }}!</h1>
        <p class="text-gray-600 text-sm sm:text-base">Aqui está um resumo da sua atividade social.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <!-- Total Friends Card -->
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Total de Amigos</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->friends->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Solicitações Pendentes</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->friendRequests->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="user-plus" class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-3 sm:mt-4">
                <span class="text-xs sm:text-sm text-yellow-600 font-medium">Aguardando aprovação</span>
            </div>
        </div>

        <!-- Groups Card -->
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm font-medium text-gray-600">Grupos Ativos</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $user->groups->count() }}</p>
                </div>
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i data-lucide="users-2" class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-900">Seu Perfil</h2>
                    <button class="text-blue-600 hover:text-blue-800 text-xs sm:text-sm font-medium">Editar</button>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-4 mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-lg sm:text-xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $user->name }}</h3>
                        <p class="text-sm sm:text-base text-gray-600 truncate">{{ $user->email }}</p>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-600">Membro desde</p>
                        <p class="font-semibold text-sm sm:text-base text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-4 sm:space-y-6">
            <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3 sm:mb-4">Ações Rápidas</h3>
                <div class="space-y-2 sm:space-y-3">
                    <a href="{{ route('friends.create') }}" class="flex items-center space-x-3 p-2 sm:p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="user-plus" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-blue-600"></i>
                        </div>
                        <span class="text-sm sm:text-base text-gray-700">Adicionar Amigo</span>
                    </a>
                    <a href="{{ route('groups.create') }}" class="flex items-center space-x-3 p-2 sm:p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="users-2" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-purple-600"></i>
                        </div>
                        <span class="text-sm sm:text-base text-gray-700">Criar Grupo</span>
                    </a>
                    <a href="{{ route('friends.index') }}" class="flex items-center space-x-3 p-2 sm:p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-7 h-7 sm:w-8 sm:h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i data-lucide="users" class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-green-600"></i>
                        </div>
                        <span class="text-sm sm:text-base text-gray-700">Ver Amigos</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
