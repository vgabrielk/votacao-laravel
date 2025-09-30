@extends('layouts.app')

@section('title', 'Grupos')
@section('page-title', 'Grupos')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Meus Grupos</h1>
            <p class="text-gray-600 mt-2">Gerencie seus grupos e participe de comunidades</p>
        </div>
        <div class="mt-4 lg:mt-0">
            <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm w-full sm:w-auto">
                <i class="ri-add-line mr-2"></i>
                Criar Novo Grupo
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total de Grupos</p>
                    <p class="text-2xl font-bold">{{ $groups->count() }}</p>
                </div>
                <div class="w-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-group-line text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Membros Totais</p>
                    <p class="text-2xl font-bold">{{ $groups->sum('members_count') ?? 0 }}</p>
                </div>
                <div class="w-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-user-line text-xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Grupos Criados</p>
                    <p class="text-2xl font-bold">{{ $groups->where('creator_id', Auth::id())->count() }}</p>
                </div>
                <div class="w-12 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="ri-crown-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups List -->
    @if($groups->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Todos os Grupos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($groups as $group)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate mb-2">{{ $group->name }}</h3>
                        <p class="text-sm text-gray-600 truncate">{{ $group->description }}</p>
                    </div>
                    <div class="flex items-center space-x-2 ml-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                            @if($group->visibility === 'public') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            <div class="w-2 h-2 rounded-full mr-2
                                @if($group->visibility === 'public') bg-green-500
                                @else bg-gray-500 @endif"></div>
                            {{ ucfirst($group->visibility) }}
                        </span>
                    </div>
                </div>
                
                <div class="space-y-3 mb-6">
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="ri-user-line mr-2"></i>
                        <span class="truncate">Criado por: <strong>{{ $group->creator->name }}</strong></span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="ri-calendar-line mr-2"></i>
                        <span>Criado em: {{ $group->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <i class="ri-group-line mr-2"></i>
                        <span>{{ $group->members()->count() }} membros</span>
                    </div>
                </div>

                <div class="flex space-x-2">
                    <a href="{{ route('groups.show', $group) }}" class="flex-1 inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-4 py-2 text-sm">
                        <i class="ri-eye-line mr-2"></i>
                        Ver Grupo
                    </a>
                    @if($group->creator_id === Auth::id())
                    <form action="{{ route('groups.destroy', $group) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-red-600 text-white hover:bg-red-700 px-4 py-2 text-sm" 
                                onclick="return confirm('Tem certeza que deseja deletar este grupo?')">
                            <i class="ri-delete-bin-line mr-2"></i>
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
        <div class="w-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ri-group-line text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum grupo ainda</h3>
        <p class="text-gray-600 mb-6">Crie seu primeiro grupo e comece a conectar pessoas!</p>
        <a href="{{ route('groups.create') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm">
            <i class="ri-add-line mr-2"></i>
            Criar Primeiro Grupo
        </a>
    </div>
    @endif
</div>
@endsection
