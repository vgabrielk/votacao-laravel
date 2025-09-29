@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 p-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Bem-vindo ao Dashboard!</h1>
                <p class="text-lg text-gray-600 mb-6">Você está logado com sucesso.</p>
                
                <div class="bg-white shadow rounded-lg p-6 max-w-md mx-auto">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Informações da Conta</h2>
                    <div class="space-y-2">
                        <p><strong>Nome:</strong> {{ Auth::user()->name }}</p>
                        <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        <p><strong>Status:</strong> 
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ Auth::user()->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ Auth::user()->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </p>
                        <p><strong>Membro desde:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
