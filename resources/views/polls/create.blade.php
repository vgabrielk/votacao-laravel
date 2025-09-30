@extends('layouts.app')

@section('title', 'Criar Enquete')
@section('page-title', 'Criar Enquete')

@section('content')
    <div class="p-4 sm:p-6">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="mb-4 sm:mb-0">
                    <p class="text-gray-600 mt-1 text-sm sm:text-base">Crie uma enquete interativa para o grupo
                        <strong>{{ $group->name }}</strong>
                    </p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('polls.index', $group) }}"
                        class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Voltar ao Grupo
                    </a>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl p-6 sm:p-8 card-shadow border border-gray-100">
            <form method="POST" action="{{ route('polls.store', $group) }}" class="space-y-6" id="pollForm">
                @csrf

                <!-- Título da Enquete -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Título da Enquete <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('title') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        placeholder="Ex: Qual sua linguagem de programação favorita?" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Descrição -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Descrição (Opcional)
                    </label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none @error('description') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                        placeholder="Adicione uma descrição para sua enquete...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Opções de Resposta -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Opções de Resposta <span class="text-red-500">*</span>
                    </label>
                    <div id="optionsContainer" class="space-y-3">
                        <!-- Opção 1 -->
                        <div class="flex items-center space-x-3 option-item">
                            <div class="flex-1">
                                <input type="text" name="options[]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none"
                                    placeholder="Digite uma opção de resposta..." required>
                            </div>
                            <button type="button"
                                class="remove-option text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                style="display: none;">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <!-- Opção 2 -->
                        <div class="flex items-center space-x-3 option-item">
                            <div class="flex-1">
                                <input type="text" name="options[]"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none"
                                    placeholder="Digite uma opção de resposta..." required>
                            </div>
                            <button type="button"
                                class="remove-option text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors"
                                style="display: none;">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <button type="button" id="addOption"
                        class="mt-3 inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Adicionar Opção
                    </button>
                </div>

                <!-- Configurações da Enquete -->
                <div class="space-y-6">
                    <!-- Tipo de Enquete -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Tipo de Enquete
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="type" value="public"
                                    {{ old('type', 'public') === 'public' ? 'checked' : '' }}
                                    class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <i data-lucide="globe" class="w-4 h-4 text-green-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Pública - Visível a todos no grupo</span>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="type" value="private"
                                    {{ old('type') === 'private' ? 'checked' : '' }}
                                    class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <i data-lucide="lock" class="w-4 h-4 text-gray-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Privada - Apenas convidados específicos</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Configurações de Votação -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Votação Anônima -->
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="anonymus" value="1" {{ old('anonymus') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="flex items-center">
                                    <i data-lucide="eye-off" class="w-4 h-4 text-gray-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Votação Anônima</span>
                                </div>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Não exibe quem votou</p>
                        </div>

                        <!-- Múltipla Escolha -->
                        <div>
                            <label class="flex items-center space-x-3">
                                <input type="checkbox" name="allow_multiple" value="1"
                                    {{ old('allow_multiple') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <div class="flex items-center">
                                    <i data-lucide="check-square" class="w-4 h-4 text-gray-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Permitir Múltipla Escolha</span>
                                </div>
                            </label>
                            <p class="mt-1 text-xs text-gray-500">Permite selecionar várias opções</p>
                        </div>
                    </div>

                    <!-- Datas de Início e Fim -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Data de Início -->
                        <div>
                            <label for="start_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Início
                            </label>
                            <input type="datetime-local" name="start_at" id="start_at"
                                value="{{ old('start_at', now()->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none">
                            <p class="mt-1 text-xs text-gray-500">Quando a enquete será ativada</p>
                        </div>

                        <!-- Data de Encerramento -->
                        <div>
                            <label for="end_at" class="block text-sm font-medium text-gray-700 mb-2">
                                Data de Encerramento (Opcional)
                            </label>
                            <input type="datetime-local" name="end_at" id="end_at" value="{{ old('end_at') }}"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none">
                            <p class="mt-1 text-xs text-gray-500">Deixe em branco para enquete sem prazo</p>
                        </div>
                    </div>

                    <!-- Status da Enquete -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Status Inicial
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="status" value="draft"
                                    {{ old('status', 'draft') === 'draft' ? 'checked' : '' }}
                                    class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <i data-lucide="edit" class="w-4 h-4 text-gray-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Rascunho - Salvar para editar depois</span>
                                </div>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="status" value="open"
                                    {{ old('status') === 'open' ? 'checked' : '' }}
                                    class="mr-3 text-blue-600 focus:ring-blue-500">
                                <div class="flex items-center">
                                    <i data-lucide="play" class="w-4 h-4 text-green-600 mr-2"></i>
                                    <span class="text-sm text-gray-700">Aberta - Iniciar votação imediatamente</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('polls.index', $group) }}"
                        class="flex-1 sm:flex-none px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-center font-medium">
                        <i data-lucide="arrow-left" class="w-4 h-4 inline mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit"
                        class="flex-1 sm:flex-none px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                        <i data-lucide="plus" class="w-4 h-4 inline mr-2"></i>
                        Criar Enquete
                    </button>
                </div>
            </form>
        </div>

        <!-- Dicas -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Card de Dicas -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i data-lucide="lightbulb" class="w-5 h-5 text-blue-600 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h3 class="text-sm font-medium text-blue-900 mb-1">Dicas para uma boa enquete:</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• Use perguntas claras e objetivas</li>
                            <li>• Adicione pelo menos 2 opções de resposta</li>
                            <li>• Evite opções muito similares</li>
                            <li>• Considere adicionar uma opção "Outro"</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Card de Configurações -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i data-lucide="settings" class="w-5 h-5 text-green-600 mr-3 mt-0.5 flex-shrink-0"></i>
                    <div>
                        <h3 class="text-sm font-medium text-green-900 mb-1">Configurações Avançadas:</h3>
                        <ul class="text-sm text-green-800 space-y-1">
                            <li>• <strong>Pública:</strong> Visível a todos no grupo</li>
                            <li>• <strong>Privada:</strong> Apenas convidados específicos</li>
                            <li>• <strong>Anônima:</strong> Não exibe quem votou</li>
                            <li>• <strong>Múltipla escolha:</strong> Permite várias respostas</li>
                            <li>• <strong>Agendamento:</strong> Define início e fim da votação</li>
                            <li>• <strong>Status:</strong> Rascunho ou aberta imediatamente</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const optionsContainer = document.getElementById('optionsContainer');
            const addOptionBtn = document.getElementById('addOption');
            let optionCount = 2; // Começa com 2 opções

            // Adicionar nova opção
            addOptionBtn.addEventListener('click', function() {
                if (optionCount >= 10) {
                    alert('Máximo de 10 opções permitidas');
                    return;
                }

                const newOption = document.createElement('div');
                newOption.className = 'flex items-center space-x-3 option-item';
                newOption.innerHTML = `
            <div class="flex-1">
                <input type="text"
                       name="options[]"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors outline-none"
                       placeholder="Digite uma opção de resposta..."
                       required>
            </div>
            <button type="button"
                    class="remove-option text-red-500 hover:text-red-700 p-2 rounded-lg hover:bg-red-50 transition-colors">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
            </button>
        `;

                optionsContainer.appendChild(newOption);
                optionCount++;

                // Mostrar botões de remover se houver mais de 2 opções
                if (optionCount > 2) {
                    document.querySelectorAll('.remove-option').forEach(btn => {
                        btn.style.display = 'block';
                    });
                }

                // Recriar ícones
                lucide.createIcons();
            });

            // Remover opção
            optionsContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option')) {
                    if (optionCount <= 2) {
                        alert('Mínimo de 2 opções necessárias');
                        return;
                    }

                    e.target.closest('.option-item').remove();
                    optionCount--;

                    // Esconder botões de remover se houver apenas 2 opções
                    if (optionCount <= 2) {
                        document.querySelectorAll('.remove-option').forEach(btn => {
                            btn.style.display = 'none';
                        });
                    }
                }
            });

            // Validação do formulário
            document.getElementById('pollForm').addEventListener('submit', function(e) {
                const options = document.querySelectorAll('input[name="options[]"]');
                const validOptions = Array.from(options).filter(input => input.value.trim() !== '');

                if (validOptions.length < 2) {
                    e.preventDefault();
                    alert('Adicione pelo menos 2 opções de resposta');
                    return;
                }

                // Verificar se há opções duplicadas
                const optionValues = validOptions.map(input => input.value.trim().toLowerCase());
                const uniqueValues = [...new Set(optionValues)];

                if (optionValues.length !== uniqueValues.length) {
                    e.preventDefault();
                    alert('Não é possível ter opções duplicadas');
                    return;
                }

                // Validação de datas
                const startAt = document.getElementById('start_at').value;
                const endAt = document.getElementById('end_at').value;

                if (startAt && endAt) {
                    const startDate = new Date(startAt);
                    const endDate = new Date(endAt);

                    if (endDate <= startDate) {
                        e.preventDefault();
                        alert('A data de encerramento deve ser posterior à data de início');
                        return;
                    }
                }

                // Validação de data de início no passado
                if (startAt) {
                    const startDate = new Date(startAt);
                    const now = new Date();

                    if (startDate < now) {
                        e.preventDefault();
                        alert('A data de início não pode ser no passado');
                        return;
                    }
                }
            });
        });
    </script>
@endsection
