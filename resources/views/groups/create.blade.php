@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @if (session('error'))
        <div class="mb-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif
    </div>
    <form method="POST" action="{{ route('groups.store') }}" class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        @csrf
        <input type="text" name="name" placeholder="Group Name" class="border p-2 rounded w-full mb-4">
        <input type="text" name="description" placeholder="Description" class="border p-2 rounded w-full mb-4">
        <select name="visibility" class="border p-2 rounded w-full mb-4">
            <option value="public">Público</option>
            <option value="private">Privado</option>
        </select>
        <button type="submit">Enviar</button>
    </form>
@endsection
