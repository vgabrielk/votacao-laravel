@extends('layouts.app')

@section('title', 'Grupos')
@section('page-title', 'Grupos')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Meus Grupos</h1>
            <p class="text-gray-600 mt-1 text-sm sm:text-base">Gerencie seus grupos e participe de comunidades</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base w-full sm:w-auto">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Criar Novo Grupo
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-6 sm:mb-8">
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="users-2" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Total de Grupos</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $groups->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-green-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="users" class="w-5 h-5 sm:w-6 sm:h-6 text-green-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Membros Totais</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $groups->sum('members_count') ?? 0 }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100 sm:col-span-2 lg:col-span-1">
            <div class="flex items-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-3 sm:mr-4 flex-shrink-0">
                    <i data-lucide="crown" class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600">Grupos Criados</p>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ $groups->where('creator_id', Auth::id())->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups List -->
    @if($groups->count() > 0)
    <div class="mb-6 sm:mb-8">
        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4 sm:mb-6">Todos os Grupos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            @foreach ($groups as $group)
            <div class="bg-white rounded-xl p-4 sm:p-6 card-shadow border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between mb-3 sm:mb-4">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                       
                        <div class="flex-1 min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 truncate">{{ $group->name }}</h3>
                            <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $group->description }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-2">
                        <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($group->visibility === 'public') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <div class="w-1.5 h-1.5 rounded-full mr-1.5
                                @if($group->visibility === 'public') bg-green-400
                                @else bg-gray-400 @endif"></div>
                            {{ ucfirst($group->visibility) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-2 sm:space-y-3 mb-4 sm:mb-6">
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <i data-lucide="user" class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0"></i>
                        <span class="truncate">Criado por: <strong>{{ $group->creator->name }}</strong></span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <i data-lucide="calendar" class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0"></i>
                        <span>Criado em: {{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center text-xs sm:text-sm text-gray-600">
                        <i data-lucide="users" class="w-3 h-3 sm:w-4 sm:h-4 mr-2 flex-shrink-0"></i>
                        <span>{{ $group->members()->count() }} membros</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <a href="{{ route('groups.show', $group) }}" class="flex-1 bg-blue-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-xs sm:text-sm font-medium text-center">
                        <i data-lucide="eye" class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1"></i>
                        Ver Grupo
                    </a>
                    @if($group->creator_id === Auth::id())
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-red-700 transition-colors text-xs sm:text-sm font-medium" 
                                onclick="return confirm('Tem certeza que deseja deletar este grupo?')">
                            <i data-lucide="trash-2" class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1"></i>
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
    <div class="text-center py-8 sm:py-12">
        <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="users-2" class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400"></i>
        </div>
        <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-2">Nenhum grupo ainda</h3>
        <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Crie seu primeiro grupo e comece a conectar pessoas!</p>
        <a href="{{ route('groups.create') }}" class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm sm:text-base">
            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
            Criar Primeiro Grupo
        </a>
    </div>
    @endif
</div>
@endsection
