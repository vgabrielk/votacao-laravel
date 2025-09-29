@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <a href="{{ route('groups.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Criar Novo
            Grupo</a>
        <div class="px-4 py-6 sm:px-0">
            <div class="border-4 border-dashed border-gray-200 rounded-lg h-96 p-8">
                <div class="text-center">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Grupos!</h1>

                    <div class="bg-white shadow rounded-lg p-6 max-w-md mx-auto">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Todos os grupos</h2>
                        <div class="space-y-2">
                            @foreach ($groups as $group)
                                <a  href={{ route('groups.show', $group) }} class="block border border-gray-200 rounded-lg p-4">
                                    <h3 class="font-semibold text-gray-800">{{ $group->name }}</h3>
                                    <p class="text-gray-600">{{ $group->description }}</p>
                                    <p class="text-sm text-gray-500">Criado por: {{ $group->creator->name }}</p>
                                    <p class="text-sm text-gray-500">Visibilidade: {{ $group->visibility }}</p>
                                    <form method="POST" action="{{ route('groups.destroy', $group) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="mt-2 bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600">
                                            Deletar
                                        </button>
                                    </form>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
