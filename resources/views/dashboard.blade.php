@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="p-6">
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Bem-vindo de volta, {{ $user->name }}!</h1>
        <p class="text-gray-600">Aqui está um resumo da sua atividade social.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Friends Card -->
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total de Amigos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->friends->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card -->
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Solicitações Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->friendRequests->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user-plus" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-4">
                <span class="text-sm text-yellow-600 font-medium">Aguardando aprovação</span>
            </div>
        </div>

        <!-- Groups Card -->
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Grupos Ativos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->groups->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="users-2" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Seu Perfil</h2>
                    <button class="text-blue-600 hover:text-blue-800 text-sm font-medium">Editar</button>
                </div>

                <div class="flex items-center space-x-4 mb-6">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm text-gray-600">Membro desde</p>
                        <p class="font-semibold text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('friends.create') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="user-plus" class="w-4 h-4 text-blue-600"></i>
                        </div>
                        <span class="text-gray-700">Adicionar Amigo</span>
                    </a>
                    <a href="{{ route('groups.create') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="users-2" class="w-4 h-4 text-purple-600"></i>
                        </div>
                        <span class="text-gray-700">Criar Grupo</span>
                    </a>
                    <a href="{{ route('friends.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <i data-lucide="users" class="w-4 h-4 text-green-600"></i>
                        </div>
                        <span class="text-gray-700">Ver Amigos</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
