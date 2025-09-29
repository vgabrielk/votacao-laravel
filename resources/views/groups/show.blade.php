@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $users = [];
    $members = [];
@endphp
@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">

        <!-- Botão Criar Grupo -->
        <div class="flex justify-end">
            <a href="{{ route('groups.create') }}"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                Criar Novo Grupo
            </a>
        </div>

        <!-- Detalhes do Grupo -->
        <div class="bg-white shadow rounded-lg p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-4 text-center">{{ $group->name }}</h1>

            <div class="border border-gray-200 rounded-lg p-4 mb-6">
                <p class="text-gray-600 mb-2">{{ $group->description }}</p>
                <p class="text-sm text-gray-500">Criado por: {{ $group->creator->name }}</p>
                <p class="text-sm text-gray-500">Visibilidade: {{ $group->visibility }}</p>
            </div>

            <!-- Adicionar Membros -->
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">Adicionar Membros</h2>
                <form action="{{ route('groups.create', $group->id) }}" method="POST"
                    class="flex flex-col sm:flex-row gap-4">
                    @csrf
                    <select name="user_id" class="border border-gray-300 rounded-lg px-3 py-2 flex-1">
                        <option value="">Selecione um usuário</option>
                        {{-- @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach --}}
                    </select>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
                        Adicionar
                    </button>
                </form>
            </div>

            <!-- Lista de Membros -->
            <div class="mt-6">
                <h2 class="text-xl font-semibold mb-4">Membros do Grupo</h2>
                <ul class="divide-y divide-gray-200">
                    @foreach ($members as $member)
                        <li class="py-2 flex justify-between items-center">
                            <span>{{ $member->name }} ({{ $member->email }})</span>
                            <form action="{{ route('groups.create', [$group->id, $member->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                    Remover
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

    </div>
@endsection
