@extends('layouts.app')

@section('title', 'Criar Grupo')
@section('page-title', 'Criar Grupo')

@section('content')
<div class="p-4 sm:p-6">
    <!-- Header Section -->
    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Criar Novo Grupo</h1>
        <p class="text-gray-600 mt-1 text-sm sm:text-base">Preencha as informações abaixo para criar seu grupo</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
            <form method="POST" action="{{ route('groups.store') }}" class="space-y-6">
                @csrf

                <!-- Nome do Grupo -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nome do Grupo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('name') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                           placeholder="Ex: Grupo de Desenvolvedores"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                              placeholder="Descreva o propósito e objetivos do grupo...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Visibilidade -->
                <div>
                    <label for="visibility" class="block text-sm font-medium text-gray-700 mb-2">
                        Visibilidade <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" 
                                   name="visibility" 
                                   value="public" 
                                   {{ old('visibility', 'public') === 'public' ? 'checked' : '' }}
                                   class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all hover:bg-gray-50 {{ old('visibility', 'public') === 'public' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 border-2 rounded-full mr-3 {{ old('visibility', 'public') === 'public' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}">
                                        <div class="w-2 h-2 bg-white rounded-full mx-auto mt-0.5 {{ old('visibility', 'public') === 'public' ? '' : 'hidden' }}"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <i data-lucide="globe" class="w-4 h-4 text-green-600 mr-2"></i>
                                            <span class="font-medium text-gray-900">Público</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Qualquer pessoa pode encontrar e participar</p>
                                    </div>
                                </div>
                            </div>
                        </label>

                        <label class="relative cursor-pointer">
                            <input type="radio" 
                                   name="visibility" 
                                   value="private" 
                                   {{ old('visibility') === 'private' ? 'checked' : '' }}
                                   class="sr-only">
                            <div class="border-2 rounded-lg p-4 transition-all hover:bg-gray-50 {{ old('visibility') === 'private' ? 'border-blue-500 bg-blue-50' : 'border-gray-300' }}">
                                <div class="flex items-center">
                                    <div class="w-4 h-4 border-2 rounded-full mr-3 {{ old('visibility') === 'private' ? 'border-blue-500 bg-blue-500' : 'border-gray-300' }}">
                                        <div class="w-2 h-2 bg-white rounded-full mx-auto mt-0.5 {{ old('visibility') === 'private' ? '' : 'hidden' }}"></div>
                                    </div>
                                    <div>
                                        <div class="flex items-center">
                                            <i data-lucide="lock" class="w-4 h-4 text-gray-600 mr-2"></i>
                                            <span class="font-medium text-gray-900">Privado</span>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Apenas membros convidados podem participar</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('visibility')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('groups.index') }}" 
                       class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                        Criar Grupo
                    </button>
                </div>
            </form>
    </div>

    <!-- Dicas -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-start">
                <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                <div>
                    <h3 class="text-sm font-medium text-blue-900 mb-1">Dicas para criar um bom grupo:</h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Escolha um nome claro e descritivo</li>
                        <li>• Descreva o propósito do grupo na descrição</li>
                        <li>• Grupos públicos são mais fáceis de descobrir</li>
                        <li>• Grupos privados oferecem mais controle sobre os membros</li>
                    </ul>
                </div>
            </div>
    </div>
</div>

<script>
    // Atualizar visual dos radio buttons
    document.addEventListener('DOMContentLoaded', function() {
        const radioButtons = document.querySelectorAll('input[name="visibility"]');
        
        radioButtons.forEach(radio => {
            radio.addEventListener('change', function() {
                // Remove todas as classes ativas
                document.querySelectorAll('label input[name="visibility"]').forEach(input => {
                    const label = input.closest('label');
                    const div = label.querySelector('div:last-child');
                    const radioCircle = label.querySelector('.w-4.h-4.border-2.rounded-full');
                    const radioDot = radioCircle.querySelector('.w-2.h-2');
                    
                    div.classList.remove('border-blue-500', 'bg-blue-50');
                    div.classList.add('border-gray-300');
                    radioCircle.classList.remove('border-blue-500', 'bg-blue-500');
                    radioCircle.classList.add('border-gray-300');
                    radioDot.classList.add('hidden');
                });
                
                // Adiciona classes ativas ao selecionado
                const label = this.closest('label');
                const div = label.querySelector('div:last-child');
                const radioCircle = label.querySelector('.w-4.h-4.border-2.rounded-full');
                const radioDot = radioCircle.querySelector('.w-2.h-2');
                
                div.classList.remove('border-gray-300');
                div.classList.add('border-blue-500', 'bg-blue-50');
                radioCircle.classList.remove('border-gray-300');
                radioCircle.classList.add('border-blue-500', 'bg-blue-500');
                radioDot.classList.remove('hidden');
            });
        });
    });
</script>
@endsection
