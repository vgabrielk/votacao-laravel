@extends('layouts.app')

@section('title', 'Adicionar Amigo')

@section('content')
<div class="max-w-3xl mx-auto py-10 sm:px-6 lg:px-8">

    <!-- Título -->
    <div class="mb-8 text-center">
        <h1 class="text-3xl font-bold text-gray-900">Adicionar Novo Amigo</h1>
        <p class="text-gray-500 mt-2">Escolha um usuário para enviar uma solicitação de amizade.</p>
    </div>

    <!-- Formulário -->
    <div class="bg-white shadow rounded-lg p-6">
        <form action="{{ route('friends.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Campo de busca / email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail do Usuário</label>
                <input type="email" name="email" id="email"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm
                              focus:border-blue-500 focus:ring focus:ring-blue-200"
                       placeholder="ex: usuario@exemplo.com" required>
            </div>

            <!-- Mensagens de erro -->
            @error('email')
                <p class="text-red-500 text-sm">{{ $message }}</p>
            @enderror

            <!-- Botões -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('friends.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-700 hover:bg-gray-100">
                   Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                    Enviar Solicitação
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
