@extends('layouts.app')

@section('title', 'Chat')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Chat</h1>
            <button id="createRoomBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors">
                Nova Sala
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Lista de Salas -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Suas Salas</h2>
                    <div id="roomsList" class="space-y-3">
                        <p class="text-gray-500 text-center">Carregando...</p>
                    </div>
                </div>
            </div>

            <!-- Área Principal -->
            <div class="lg:col-span-2">
                <div id="chatArea" class="bg-white rounded-lg shadow-md p-6 min-h-[500px] flex items-center justify-center">
                    <div class="text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p>Selecione uma sala para começar a conversar</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Criar Sala -->
<div id="createRoomModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Criar Nova Sala</h3>
                <form id="createRoomForm">
                    <div class="mb-4">
                        <label for="roomName" class="block text-sm font-medium text-gray-700 mb-2">Nome da Sala</label>
                        <input type="text" id="roomName" name="name" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label for="roomDescription" class="block text-sm font-medium text-gray-700 mb-2">Descrição (opcional)</label>
                        <textarea id="roomDescription" name="description" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="checkbox" id="isPrivate" name="is_private" class="mr-2">
                            <span class="text-sm text-gray-700">Sala privada</span>
                        </label>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" id="cancelCreateRoom" 
                                class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" id="submitCreateRoom" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Criar Sala
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
(function() {
    'use strict';
    
    console.log('🚀 Iniciando aplicação de chat...');
    
    // Configuração do Pusher
    const pusher = new Pusher('{{ config('broadcasting.connections.reverb.key') }}', {
        cluster: '{{ config('broadcasting.connections.reverb.options.cluster', 'mt1') }}',
        wsHost: '127.0.0.1',
        wsPort: 8080,
        wssPort: 8080,
        forceTLS: false,
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }
    });

    // Estado da aplicação
    let currentRoom = null;
    let currentChannel = null;

    // Aguardar DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM carregado, inicializando chat...');
        
        // Elementos DOM
        const elements = {
            roomsList: document.getElementById('roomsList'),
            chatArea: document.getElementById('chatArea'),
            createRoomBtn: document.getElementById('createRoomBtn'),
            createRoomModal: document.getElementById('createRoomModal'),
            createRoomForm: document.getElementById('createRoomForm'),
            cancelCreateRoom: document.getElementById('cancelCreateRoom'),
            submitCreateRoom: document.getElementById('submitCreateRoom')
        };
        
        // Verificar se todos os elementos foram encontrados
        Object.keys(elements).forEach(key => {
            if (!elements[key]) {
                console.error(`❌ Elemento não encontrado: ${key}`);
            } else {
                console.log(`✅ Elemento encontrado: ${key}`);
            }
        });
        
        // ==================== MODAL ====================
        
        // Abrir modal
        elements.createRoomBtn.addEventListener('click', function() {
            console.log('🔵 Botão Nova Sala clicado');
            elements.createRoomModal.classList.remove('hidden');
            console.log('✅ Modal aberto');
        });
        
        // Fechar modal
        elements.cancelCreateRoom.addEventListener('click', function() {
            console.log('🔵 Botão Cancelar clicado');
            elements.createRoomModal.classList.add('hidden');
            elements.createRoomForm.reset();
            console.log('✅ Modal fechado');
        });
        
        // Criar sala
        elements.createRoomForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('🔵 Formulário enviado');
            
            const formData = new FormData(e.target);
            const data = {
                name: formData.get('name'),
                description: formData.get('description') || '',
                is_private: formData.get('is_private') === 'on'
            };
            
            console.log('📤 Enviando dados:', data);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('CSRF token não encontrado');
                }
                
                const response = await fetch('/api/rooms', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });
                
                console.log('📥 Resposta recebida:', response.status);
                
                const result = await response.json();
                console.log('📦 Resultado:', result);
                
                if (response.ok && result.success) {
                    console.log('✅ Sala criada com sucesso!');
                    elements.createRoomModal.classList.add('hidden');
                    elements.createRoomForm.reset();
                    alert('Sala criada com sucesso!');
                    loadRooms();
                } else {
                    throw new Error(result.message || 'Erro ao criar sala');
                }
            } catch (error) {
                console.error('❌ Erro:', error);
                alert('Erro ao criar sala: ' + error.message);
            }
        });
        
        // ==================== SALAS ====================
        
        async function loadRooms() {
            console.log('📥 Carregando salas...');
            try {
                const response = await fetch('/api/rooms');
                const rooms = await response.json();
                
                console.log('📦 Salas recebidas:', rooms.length);
                
                elements.roomsList.innerHTML = '';
                
                if (rooms.length === 0) {
                    elements.roomsList.innerHTML = '<p class="text-gray-500 text-center text-sm">Nenhuma sala encontrada. Crie uma nova sala!</p>';
                    return;
                }

                rooms.forEach(room => {
                    const roomElement = createRoomElement(room);
                    elements.roomsList.appendChild(roomElement);
                });
                
                console.log('✅ Salas carregadas');
            } catch (error) {
                console.error('❌ Erro ao carregar salas:', error);
                elements.roomsList.innerHTML = '<p class="text-red-500 text-center text-sm">Erro ao carregar salas</p>';
            }
        }
        
        function createRoomElement(room) {
            const div = document.createElement('div');
            div.className = 'p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors';
            div.onclick = () => showRoom(room);
            
            const lastMessage = room.messages && room.messages.length > 0 ? room.messages[0] : null;
            
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">${escapeHtml(room.name)}</h3>
                        ${room.description ? `<p class="text-xs text-gray-500 mt-1">${escapeHtml(room.description)}</p>` : ''}
                        ${lastMessage ? `<p class="text-xs text-gray-400 mt-1">${escapeHtml(lastMessage.content.substring(0, 30))}...</p>` : ''}
                    </div>
                    ${room.unread_count > 0 ? `<span class="bg-blue-600 text-white text-xs rounded-full px-2 py-1">${room.unread_count}</span>` : ''}
                </div>
            `;
            
            return div;
        }
        
        // ==================== CHAT ====================
        
        async function showRoom(room) {
            console.log('🔵 Abrindo sala:', room.name);
            
            currentRoom = room;
            
            // Desinscrever do canal anterior
            if (currentChannel) {
                pusher.unsubscribe(currentChannel.name);
            }
            
            // Inscrever no canal da sala
            currentChannel = pusher.subscribe('private-room.' + room.id);
            
            // Escutar mensagens
            currentChannel.bind('App\\Events\\MessageSent', function(data) {
                console.log('📨 Nova mensagem recebida:', data);
                addMessageToChat(data.message);
            });

            // Carregar mensagens da sala
            try {
                const response = await fetch(`/api/rooms/${room.id}/messages`);
                const result = await response.json();
                
                console.log('📦 Mensagens recebidas:', result.data.length);
                
                displayMessages(result.data.reverse());
            } catch (error) {
                console.error('❌ Erro ao carregar mensagens:', error);
            }

            // Atualizar interface
            updateChatInterface(room);
        }
        
        function updateChatInterface(room) {
            elements.chatArea.innerHTML = `
                <div class="flex flex-col h-full">
                    <div class="border-b pb-4 mb-4">
                        <h2 class="text-xl font-semibold">${escapeHtml(room.name)}</h2>
                        <p class="text-sm text-gray-500">${room.description ? escapeHtml(room.description) : 'Sem descrição'}</p>
                        <p class="text-xs text-gray-400">${room.participants ? room.participants.length : 0} participantes</p>
                    </div>
                    
                    <div id="messagesContainer" class="flex-1 overflow-y-auto mb-4 space-y-3">
                        <!-- Mensagens serão inseridas aqui -->
                    </div>
                    
                    <div class="border-t pt-4">
                        <form id="messageForm" class="flex space-x-2">
                            <input type="text" id="messageInput" placeholder="Digite sua mensagem..." 
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   autocomplete="off">
                            <button type="submit" id="sendButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                                Enviar
                            </button>
                        </form>
                    </div>
                </div>
            `;

            // Event listeners para envio de mensagem
            const messageForm = document.getElementById('messageForm');
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.getElementById('sendButton');
            
            console.log('📝 Elementos do form encontrados:', {
                messageForm: !!messageForm,
                messageInput: !!messageInput,
                sendButton: !!sendButton
            });
            
            // Event listener - SUBMIT
            messageForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('🔵 Form submit interceptado');
                await sendMessage();
                return false;
            });
            
            // Event listener - CLICK no botão
            sendButton.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('🔵 Botão clicado');
                await sendMessage();
            });
            
            // Event listener - ENTER no input
            messageInput.addEventListener('keypress', async function(e) {
                if (e.key === 'Enter' && !e.shiftKey) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('🔵 Enter pressionado');
                    await sendMessage();
                }
            });
        }
        
        function displayMessages(messages) {
            const container = document.getElementById('messagesContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            if (messages.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center">Nenhuma mensagem ainda. Seja o primeiro a enviar!</p>';
                return;
            }
            
            messages.forEach(message => {
                addMessageToChat(message);
            });
            
            // Scroll para o final
            container.scrollTop = container.scrollHeight;
        }
        
        function addMessageToChat(message) {
            const container = document.getElementById('messagesContainer');
            if (!container) return;
            
            const isOwn = message.user_id === {{ auth()->id() }};
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'}`;
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md">
                    <div class="${isOwn ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-900'} rounded-lg px-4 py-2">
                        ${!isOwn ? `<p class="text-xs font-semibold mb-1">${escapeHtml(message.user.name)}</p>` : ''}
                        <p class="text-sm">${escapeHtml(message.content)}</p>
                        <p class="text-xs ${isOwn ? 'text-blue-100' : 'text-gray-500'} mt-1">
                            ${formatTime(message.created_at)}
                        </p>
                    </div>
                </div>
            `;
            
            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }
        
        async function sendMessage() {
            const input = document.getElementById('messageInput');
            const content = input.value.trim();
            
            if (!content) {
                console.log('⚠️ Mensagem vazia, não enviando');
                return;
            }
            
            if (!currentRoom) {
                console.error('❌ Nenhuma sala selecionada!');
                alert('Erro: Nenhuma sala selecionada. Selecione uma sala primeiro.');
                return;
            }
            
            console.log('📤 Enviando mensagem:', content);
            console.log('📤 Room ID:', currentRoom.id);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                
                if (!csrfToken) {
                    console.error('❌ CSRF token não encontrado!');
                    alert('Erro: Token CSRF não encontrado. Recarregue a página.');
                    return;
                }
                
                console.log('🔑 CSRF Token encontrado');
                
                const url = `/api/rooms/${currentRoom.id}/messages`;
                console.log('🌐 URL:', url);
                
                const requestBody = { content };
                console.log('📦 Body:', requestBody);
                
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestBody)
                });

                console.log('📥 Response status:', response.status);
                console.log('📥 Response OK:', response.ok);
                
                const result = await response.json();
                console.log('📦 Response data:', result);
                
                if (response.ok && result.success) {
                    input.value = '';
                    console.log('✅ Mensagem enviada com sucesso!');
                    console.log('📝 Mensagem completa:', result.message);
                    // Adicionar a mensagem ao chat imediatamente
                    addMessageToChat(result.message);
                } else {
                    console.error('❌ Resposta não foi bem sucedida:', result);
                    throw new Error(result.message || result.error || 'Erro ao enviar mensagem');
                }
            } catch (error) {
                console.error('❌ Erro ao enviar mensagem:', error);
                console.error('❌ Stack:', error.stack);
                alert('Erro ao enviar mensagem: ' + error.message);
            }
        }
        
        // ==================== UTILIDADES ====================
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function formatTime(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Agora';
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm';
            if (diff < 86400000) return Math.floor(diff / 3600000) + 'h';
            
            return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit' });
        }
        
        // ==================== INICIALIZAÇÃO ====================
        
        console.log('🚀 Carregando salas iniciais...');
        loadRooms();
        
        console.log('✅ Chat inicializado com sucesso!');
    });
})();
</script>
@endpush
