@extends('layouts.app')

@section('title', 'Grupos')
@section('page-title', 'Grupos')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Meus Grupos</h1>
            <p class="text-gray-600 mt-1">Gerencie seus grupos e participe de comunidades</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('groups.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Criar Novo Grupo
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="users-2" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total de Grupos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $groups->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Membros Totais</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $groups->sum('members_count') ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="crown" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Grupos Criados</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $groups->where('creator_id', Auth::id())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups List -->
    @if($groups->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Todos os Grupos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($groups as $group)
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <i data-lucide="users-2" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $group->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $group->description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($group->visibility === 'public') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <div class="w-1.5 h-1.5 rounded-full mr-1.5
                                @if($group->visibility === 'public') bg-green-400
                                @else bg-gray-400 @endif"></div>
                            {{ ucfirst($group->visibility) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="user" class="w-4 h-4 mr-2"></i>
                        <span>Criado por: <strong>{{ $group->creator->name }}</strong></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="calendar" class="w-4 h-4 mr-2"></i>
                        <span>Criado em: {{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i data-lucide="users" class="w-4 h-4 mr-2"></i>
                        <span>{{ $group->members()->count() }} membros</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('groups.show', $group) }}" class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium text-center">
                        <i data-lucide="eye" class="w-4 h-4 inline mr-1"></i>
                        Ver Grupo
                    </a>
                    @if($group->creator_id === Auth::id())
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium" 
                                onclick="return confirm('Tem certeza que deseja deletar este grupo?')">
                            <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                            Deletar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty State -->
    @if($groups->count() === 0)
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="users-2" class="w-12 h-12 text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum grupo ainda</h3>
        <p class="text-gray-600 mb-6">Crie seu primeiro grupo e comece a conectar pessoas!</p>
        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Criar Primeiro Grupo
        </a>
    </div>
    @endif
</div>
@endsection
