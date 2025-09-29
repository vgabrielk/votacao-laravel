@extends('layouts.app')

@section('title', 'Amigos')
@section('page-title', 'Amigos')

@section('content')
<div class="p-6">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Meus Amigos</h1>
            <p class="text-gray-600 mt-1">Gerencie suas conexões e solicitações de amizade</p>
        </div>
        <div class="mt-4 sm:mt-0 flex space-x-3">
            <a href="{{ route('friends.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                Adicionar Amigo
            </a>
            <form action="{{ route('friends.index') }}" method="GET" class="inline">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Atualizar
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="users" class="w-6 h-6 text-green-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Amigos Aceitos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $friends->where('pivot.status', 'accepted')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Solicitações Pendentes</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $user->friendRequests->where('pivot.status', 'pending')->count() }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i data-lucide="user-plus" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total de Conexões</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $friends->count() + $user->friendRequests->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Friends List -->
    @if($friends->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Seus Amigos</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($friends as $friend)
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr($friend->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $friend->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $friend->email }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if ($friend->pivot->status === 'accepted') bg-green-100 text-green-800
                        @elseif($friend->pivot->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        <div class="w-1.5 h-1.5 rounded-full mr-1.5
                            @if ($friend->pivot->status === 'accepted') bg-green-400
                            @elseif($friend->pivot->status === 'pending') bg-yellow-400
                            @else bg-red-400 @endif"></div>
                        {{ ucfirst($friend->pivot->status) }}
                    </span>
                </div>

                @if ($friend->pivot->status === 'pending')
                <div class="flex space-x-2">
                    <form action="{{ route('friends.acceptFriend', $friend->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <i data-lucide="check" class="w-4 h-4 inline mr-1"></i>
                            Aceitar
                        </button>
                    </form>
                    <form action="{{ route('friends.removeFriend', $friend->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1"></i>
                            Recusar
                        </button>
                    </form>
                </div>
                @elseif ($friend->pivot->status === 'accepted')
                <div class="flex space-x-2">
                    <button class="flex-1 bg-blue-600 text-white px-3 py-2 rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        <i data-lucide="message-circle" class="w-4 h-4 inline mr-1"></i>
                        Mensagem
                    </button>
                    <form action="{{ route('friends.removeFriend', $friend->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-gray-600 text-white px-3 py-2 rounded-lg hover:bg-gray-700 transition-colors text-sm font-medium">
                            <i data-lucide="user-minus" class="w-4 h-4 inline mr-1"></i>
                            Remover
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Friend Requests -->
    @if($user->friendRequests->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Solicitações de Amizade</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($user->friendRequests as $friendRequest)
            <div class="bg-white rounded-xl p-6 card-shadow border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-semibold">{{ substr($friendRequest->name, 0, 1) }}</span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900">{{ $friendRequest->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $friendRequest->email }}</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <div class="w-1.5 h-1.5 rounded-full mr-1.5 bg-yellow-400"></div>
                        Pendente
                    </span>
                </div>

                <div class="flex space-x-2">
                    <form action="{{ route('friends.acceptFriend', $friendRequest->pivot->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-green-600 text-white px-3 py-2 rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            <i data-lucide="check" class="w-4 h-4 inline mr-1"></i>
                            Aceitar
                        </button>
                    </form>
                    <form action="{{ route('friends.removeFriend', $friendRequest->pivot->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full bg-red-600 text-white px-3 py-2 rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                            <i data-lucide="x" class="w-4 h-4 inline mr-1"></i>
                            Recusar
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty States -->
    @if($friends->count() === 0 && $user->friendRequests->count() === 0)
    <div class="text-center py-12">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-lucide="users" class="w-12 h-12 text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Nenhum amigo ainda</h3>
        <p class="text-gray-600 mb-6">Comece adicionando seus primeiros amigos!</p>
        <a href="{{ route('friends.create') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
            Adicionar Primeiro Amigo
        </a>
    </div>
    @endif
</div>
@endsection
