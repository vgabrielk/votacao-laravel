@extends('layouts.app')

@section('title', 'Adicionar Amigo')
@section('page-title', 'Adicionar Amigo')

@section('content')
    <!-- Header Section -->
    <div class="mb-6 lg:mb-8">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-900">Adicionar Novo Amigo</h1>
        <p class="text-gray-600 mt-2">Digite o e-mail do usuário para enviar uma solicitação de amizade</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border border-gray-100">
        <form action="{{ route('friends.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Campo de E-mail -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-3">
                    E-mail do Usuário <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ri-mail-line text-gray-400"></i>
                    </div>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email') }}"
                           class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-2xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors outline-none @error('email') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="ex: usuario@exemplo.com"
                           required>
                </div>
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Informações Adicionais -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <div class="flex items-start">
                    <i class="ri-information-line text-blue-600 mr-3 mt-1 flex-shrink-0"></i>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 mb-2">Como funciona:</h3>
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
            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('friends.index') }}" 
                   class="flex-1 sm:flex-none inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 text-sm">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Cancelar
                </a>
                <button type="submit" 
                        class="flex-1 sm:flex-none inline-flex items-center justify-center font-medium rounded-2xl transition-colors whitespace-nowrap cursor-pointer bg-purple-600 text-white hover:bg-purple-700 px-6 py-3 text-sm">
                    <i class="ri-user-add-line mr-2"></i>
                    Enviar Solicitação
                </button>
            </div>
        </form>
    </div>

    <!-- Dicas Adicionais -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Card de Dicas -->
        <div class="bg-green-50 border border-green-200 rounded-2xl p-6">
            <div class="flex items-start">
                <i class="ri-lightbulb-line text-green-600 mr-3 mt-1 flex-shrink-0"></i>
                <div>
                    <h3 class="text-sm font-medium text-green-900 mb-2">Dicas para encontrar amigos:</h3>
                    <ul class="text-sm text-green-800 space-y-1">
                        <li>• Use o e-mail exato do usuário</li>
                        <li>• Verifique se o usuário já está cadastrado</li>
                        <li>• Você não pode adicionar a si mesmo</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Card de Status -->
        <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6">
            <div class="flex items-start">
                <i class="ri-user-line text-purple-600 mr-3 mt-1 flex-shrink-0"></i>
                <div>
                    <h3 class="text-sm font-medium text-purple-900 mb-2">Status da Solicitação:</h3>
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
