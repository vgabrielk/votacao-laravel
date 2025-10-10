@extends('layouts.app')

@section('title', 'Amigos')
@section('page-title', 'Amigos')

@section('content')
    <!-- Header Section -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 lg:mb-8">
        <div class="mb-4 lg:mb-0">
            <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Meus Amigos</h1>
            <p class="text-gray-600 mt-2">Gerencie suas conexões e solicitações de amizade</p>
        </div>
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-3">
            <a href="{{ route('friends.create') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm">
                <i class="ri-user-add-line mr-2"></i>
                Adicionar Amigo
            </a>
            <form action="{{ route('friends.index') }}" method="GET" class="inline">
                <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 text-sm">
                    <i class="ri-refresh-line mr-2"></i>
                    Atualizar
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Amigos Aceitos</p>
                    <p class="text-2xl font-bold">{{ $friends->where('pivot.status', 'accepted')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-user-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Solicitações Pendentes</p>
                    <p class="text-2xl font-bold">{{ $user->friendRequests->where('pivot.status', 'pending')->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-time-line text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total de Conexões</p>
                    <p class="text-2xl font-bold">{{ $friends->count() + $user->friendRequests->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="ri-user-add-line text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Friends List -->
    @if($friends->count() > 0)
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Seus Amigos</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($friends as $friend)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-2xl flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $friend->name }}</h3>
                        <p class="text-sm text-gray-600 truncate">{{ $friend->email }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                        @if ($friend->pivot->status === 'accepted') bg-green-100 text-green-800
                        @elseif($friend->pivot->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        <div class="w-2 h-2 rounded-full mr-2
                            @if ($friend->pivot->status === 'accepted') bg-green-500
                            @elseif($friend->pivot->status === 'pending') bg-yellow-500
                            @else bg-red-500 @endif"></div>
                        {{ ucfirst($friend->pivot->status) }}
                    </span>
                </div>

                @if ($friend->pivot->status === 'pending')
                <div class="flex space-x-2">
                    <form action="{{ route('friends.acceptFriend', $friend->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-green-600 text-white hover:bg-green-700 px-4 py-2 text-sm">
                            <i class="ri-check-line mr-2"></i>
                            Aceitar
                        </button>
                    </form>
                    <button onclick="confirmRejectFriend('{{ $friend->id }}', '{{ $friend->name }}')" class="w-full inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-red-600 text-white hover:bg-red-700 px-4 py-2 text-sm">
                        <i class="ri-close-line mr-2"></i>
                        Recusar
                    </button>
                </div>
                @elseif ($friend->pivot->status === 'accepted')
                <div class="flex space-x-2">
                    <a href="{{ route('chat.direct', $friend->id) }}" class="flex-1 inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-blue-600 text-white hover:bg-blue-700 px-4 py-2 text-sm">
                        <i class="ri-chat-3-line mr-2"></i>
                        Chat
                    </a>
                    <button onclick="confirmRemoveFriend('{{ $friend->id }}', '{{ $friend->name }}')" class="flex-1 inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 text-sm">
                        <i class="ri-user-unfollow-line mr-2"></i>
                        Remover
                    </button>
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($user->friendRequests as $friendRequest)
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                <div class="flex items-center space-x-4 mb-4">
                    <div class="w-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-2xl flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr($friendRequest->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $friendRequest->name }}</h3>
                        <p class="text-sm text-gray-600 truncate">{{ $friendRequest->email }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                        <div class="w-2 h-2 rounded-full mr-2 bg-yellow-500"></div>
                        Pendente
                    </span>
                </div>

                <div class="flex space-x-2">
                    <form action="{{ route('friends.acceptFriend', $friendRequest->pivot->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-green-600 text-white hover:bg-green-700 px-4 py-2 text-sm">
                            <i class="ri-check-line mr-2"></i>
                            Aceitar
                        </button>
                    </form>
                    <button onclick="confirmRejectFriend('{{ $friendRequest->pivot->id }}', '{{ $friendRequest->name }}')" class="w-full inline-flex items-center justify-center font-medium rounded-xl transition-colors whitespace-nowrap cursor-pointer bg-red-600 text-white hover:bg-red-700 px-4 py-2 text-sm">
                        <i class="ri-close-line mr-2"></i>
                        Recusar
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Empty States -->
    @if($friends->count() === 0 && $user->friendRequests->count() === 0)
    <div class="text-center py-12">
        <div class="w-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <i class="ri-user-line text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-xl font-semibold text-gray-900 mb-2">Nenhum amigo ainda</h3>
        <p class="text-gray-600 mb-6">Comece adicionando seus primeiros amigos!</p>
        <a href="{{ route('friends.create') }}" class="inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm">
            <i class="ri-user-add-line mr-2"></i>
            Adicionar Primeiro Amigo
        </a>
    </div>
    @endif
    </div>

    <script>
        function confirmRemoveFriend(friendId, friendName) {
            openConfirmationModal(
                'Remover Amigo',
                `Tem certeza que deseja remover ${friendName} da sua lista de amigos?`,
                'Remover',
                function() {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/friends/${friendId}`;
                    
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

        function confirmRejectFriend(friendId, friendName) {
            openConfirmationModal(
                'Recusar Solicitação',
                `Tem certeza que deseja recusar a solicitação de amizade de ${friendName}?`,
                'Recusar',
                function() {
                    // Create and submit form
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/friends/${friendId}`;
                    
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
