@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Bem-vindo de volta, {{ $user->name }}!</h1>
        <p class="text-gray-600">Aqui está um resumo da sua atividade social.</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <x-stats-card 
            title="Total de Amigos" 
            :value="$user->friends->count()" 
            icon="ri-user-line" 
            gradient="from-blue-500 to-blue-600" 
            text-color="text-blue-100" 
        />

        <x-stats-card 
            title="Solicitações Pendentes" 
            :value="$user->friendRequests->count()" 
            icon="ri-user-add-line" 
            gradient="from-yellow-500 to-yellow-600" 
            text-color="text-yellow-100" 
            description="Aguardando aprovação"
        />

    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Seu Perfil</h2>
                    <button class="text-purple-600 hover:text-purple-800 text-sm font-medium">Editar</button>
                </div>

                <div class="flex items-center space-x-4 mb-6">
                
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $user->name }}</h3>
                        <p class="text-base text-gray-600 truncate">{{ $user->email }}</p>
                        <div class="flex items-center mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $user->status === 'active' ? 'bg-green-400' : 'bg-red-400' }}"></div>
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-gray-50 rounded-2xl p-4">
                        <p class="text-sm text-gray-600">Membro desde</p>
                        <p class="font-semibold text-base text-gray-900">{{ $user->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                <div class="space-y-3">
                    <a href="{{ route('friends.create') }}" class="flex items-center space-x-3 py-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <div class="w-8 bg-blue-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="ri-user-add-line text-blue-600"></i>
                        </div>
                        <span class="text-base text-gray-700">Adicionar Amigo</span>
                    </a>
                    <a href="{{ route('friends.index') }}" class="flex items-center space-x-3 py-3 rounded-2xl hover:bg-gray-50 transition-colors">
                        <div class="w-8 bg-green-100 rounded-2xl flex items-center justify-center flex-shrink-0">
                            <i class="ri-user-line text-green-600"></i>
                        </div>
                        <span class="text-base text-gray-700">Ver Amigos</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
