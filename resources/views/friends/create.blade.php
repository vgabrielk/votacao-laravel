@extends('layouts.app')

@section('title', 'Adicionar Amigo')
@section('page-title', 'Adicionar Amigo')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Adicionar Novo Amigo</h1>
        <p class="text-gray-600 mt-1 text-sm sm:text-base">Digite o e-mail do usuário para enviar uma solicitação de amizade</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
        <form action="{{ route('friends.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Campo de E-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail do Usuário <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="mail" class="w-5 h-5 text-gray-400"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="ex: usuario@exemplo.com"
                           required>
                </div>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 mb-1">Como funciona:</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Digite o e-mail exato do usuário que você deseja adicionar</li>
                            <li>• Uma solicitação de amizade será enviada para ele</li>
                            <li>• O usuário receberá um e-mail com a notificação</li>
                            <li>• Ele poderá aceitar ou rejeitar sua solicitação</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação -->
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('friends.index') }}" 
                   class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium">
                    <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    <i data-lucide="user-plus" class="w-4 h-4 inline mr-2"></i>
                    Enviar Solicitação
                </button>
            </div>
        </form>
    </div>

    <!-- Dicas Adicionais -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Card de Dicas -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-start">
                <i data-lucide="lightbulb" class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0"></i>
                <div>
                    <h3 class="text-sm font-medium text-green-900 mb-1">Dicas para encontrar amigos:</h3>
                    <ul class="text-sm text-green-800 space-y-1">
                        <li>• Use o e-mail exato do usuário</li>
                        <li>• Verifique se o usuário já está cadastrado</li>
                        <li>• Você não pode adicionar a si mesmo</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card de Status -->
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-start">
                <i data-lucide="users" class="w-5 h-5 text-purple-600 mr-3 mt-0.5 flex-shrink-0"></i>
                <div>
                    <h3 class="text-sm font-medium text-purple-900 mb-1">Status da Solicitação:</h3>
                    <ul class="text-sm text-purple-800 space-y-1">
                        <li>• <strong>Pendente:</strong> Aguardando resposta</li>
                        <li>• <strong>Aceita:</strong> Vocês são amigos</li>
                        <li>• <strong>Rejeitada:</strong> Solicitação negada</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
