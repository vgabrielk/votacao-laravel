@extends('layouts.app')

@section('title', 'Chat com ' . $friend->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('friends.index') }}" class="text-gray-600 hover:text-gray-900">
                        <i class="ri-arrow-left-line text-2xl"></i>
                    </a>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                        {{ strtoupper(substr($friend->name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $friend->name }}</h1>
                        <p class="text-sm text-gray-500">{{ $friend->email }}</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <div class="w-2 h-2 rounded-full mr-2 bg-green-500"></div>
                        Online
                    </span>
                </div>
            </div>
        </div>

        <!-- Chat Area -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="flex flex-col h-[600px]">
                <!-- Messages Container -->
                <div id="messagesContainer" class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50">
                    <div class="text-center text-gray-500 py-8">
                        <i class="ri-chat-3-line text-4xl text-gray-400 mb-2"></i>
                        <p>Comece a conversar com {{ $friend->name }}</p>
                    </div>
                </div>

                <!-- Input Area -->
                <div class="border-t bg-white p-4">
                    <form id="messageForm" class="flex space-x-3">
                        <input type="text" id="messageInput" placeholder="Digite sua mensagem..." 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               autocomplete="off">
                        <button type="submit" id="sendButton" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="ri-send-plane-fill"></i>
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos do chat -->
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
console.log('🔥🔥🔥 SCRIPT CARREGADO! @@push funciona! 🔥🔥🔥');

(function() {
    'use strict';
    
    console.log('===================================');
    console.log('🚀 INICIANDO CHAT DIRETO');
    console.log('===================================');
    
    const roomId = {{ $room->id }};
    const friendId = {{ $friend->id }};
    const currentUserId = {{ auth()->id() }};
    const friendName = "{{ $friend->name }}";
    
    console.log('📊 Configurações:', {
        roomId: roomId,
        friendId: friendId,
        currentUserId: currentUserId,
        friendName: friendName
    });
    
    // Configuração do Pusher
    console.log('🔧 Configurando Pusher...');
    console.log('   App Key:', '{{ config('broadcasting.connections.reverb.key') }}');
    console.log('   Host:', '127.0.0.1');
    console.log('   Port:', 8080);
    
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

    // Log de eventos de conexão do Pusher
    pusher.connection.bind('connected', function() {
        console.log('✅ Pusher conectado com sucesso!');
        console.log('   Socket ID:', pusher.connection.socket_id);
    });

    pusher.connection.bind('connecting', function() {
        console.log('🔄 Pusher conectando...');
    });

    pusher.connection.bind('disconnected', function() {
        console.log('❌ Pusher desconectado');
    });

    pusher.connection.bind('error', function(err) {
        console.error('❌ Erro de conexão Pusher:', err);
    });

    pusher.connection.bind('state_change', function(states) {
        console.log('🔄 Estado da conexão:', states.previous, '->', states.current);
    });

    document.addEventListener('DOMContentLoaded', function() {
        console.log('===================================');
        console.log('✅ DOM CARREGADO - Inicializando...');
        console.log('===================================');
        
        console.log('🔍 Procurando elementos do DOM...');
        const messagesContainer = document.getElementById('messagesContainer');
        console.log('messagesContainer:', messagesContainer);
        
        const messageForm = document.getElementById('messageForm');
        console.log('messageForm:', messageForm);
        
        const messageInput = document.getElementById('messageInput');
        console.log('messageInput:', messageInput);
        
        const sendButton = document.getElementById('sendButton');
        console.log('sendButton:', sendButton);
        
        console.log('📝 Status dos elementos:', {
            messagesContainer: !!messagesContainer,
            messageForm: !!messageForm,
            messageInput: !!messageInput,
            sendButton: !!sendButton
        });
        
        if (!messageForm || !messageInput || !sendButton) {
            console.error('❌❌❌ ERRO CRÍTICO: Elementos não encontrados!');
            console.error('Verifique se o HTML foi gerado corretamente');
            return;
        }
        
        console.log('✅ Todos os elementos encontrados!');
        
        // Inscrever no canal da sala
        console.log('📡 Inscrevendo no canal:', 'private-room.' + roomId);
        const channel = pusher.subscribe('private-room.' + roomId);
        
        // Logs de subscrição
        channel.bind('pusher:subscription_succeeded', function() {
            console.log('✅ Inscrito no canal com sucesso!');
        });
        
        channel.bind('pusher:subscription_error', function(error) {
            console.error('❌ Erro ao inscrever no canal:', error);
        });
        
        // Escutar mensagens
        console.log('👂 Esperando por mensagens no evento: App\\Events\\MessageSent');
        
        // Bind para evento (apenas uma vez!)
        channel.bind('App\\Events\\MessageSent', function(data) {
            console.log('===================================');
            console.log('📨 NOVA MENSAGEM RECEBIDA VIA BROADCAST!');
            console.log('===================================');
            console.log('   Data:', data);
            console.log('   Mensagem:', data.message);
            console.log('   Room ID:', data.room_id);
            console.log('   User que enviou:', data.message.user_id);
            console.log('   User atual:', currentUserId);
            
            // Adicionar mensagem ao chat
            addMessageToChat(data.message);
        });
        
        // Carregar mensagens existentes
        console.log('📥 Iniciando carregamento de mensagens...');
        loadMessages();
        
        console.log('===================================');
        console.log('🎯 ADICIONANDO EVENT LISTENERS');
        console.log('===================================');
        
        // Event listener para envio de mensagem - SUBMIT
        console.log('➕ Adicionando listener: SUBMIT');
        messageForm.addEventListener('submit', function(e) {
            console.log('===================================');
            console.log('🔵 EVENT: FORM SUBMIT');
            console.log('===================================');
            e.preventDefault();
            console.log('✅ preventDefault() chamado');
            console.log('🚀 Chamando sendMessage()...');
            sendMessage();
            return false;
        });
        console.log('✅ Listener SUBMIT adicionado');
        
        // Event listener para envio de mensagem - CLICK no botão
        console.log('➕ Adicionando listener: CLICK no botão');
        sendButton.addEventListener('click', function(e) {
            console.log('===================================');
            console.log('🔵 EVENT: BOTÃO CLICADO');
            console.log('===================================');
            e.preventDefault();
            console.log('✅ preventDefault() chamado');
            console.log('🚀 Chamando sendMessage()...');
            sendMessage();
        });
        console.log('✅ Listener CLICK adicionado');
        
        // Event listener para ENTER no input
        console.log('➕ Adicionando listener: KEYPRESS no input');
        messageInput.addEventListener('keypress', function(e) {
            console.log('⌨️ Tecla pressionada:', e.key);
            if (e.key === 'Enter' && !e.shiftKey) {
                console.log('===================================');
                console.log('🔵 EVENT: ENTER PRESSIONADO');
                console.log('===================================');
                e.preventDefault();
                console.log('✅ preventDefault() chamado');
                console.log('🚀 Chamando sendMessage()...');
                sendMessage();
            }
        });
        console.log('✅ Listener KEYPRESS adicionado');
        
        console.log('===================================');
        console.log('✅ TODOS OS LISTENERS ADICIONADOS');
        console.log('===================================');
        
        // Focar no input
        console.log('🎯 Focando no input...');
        messageInput.focus();
        console.log('✅ Input focado');
        
        console.log('===================================');
        console.log('🎉 INICIALIZAÇÃO COMPLETA!');
        console.log('===================================');
        
        async function loadMessages() {
            console.log('📥 Carregando mensagens...');
            try {
                const response = await fetch(`/api/rooms/${roomId}/messages`);
                const result = await response.json();
                
                console.log('📦 Mensagens recebidas:', result.data.length);
                
                if (result.data.length === 0) {
                    messagesContainer.innerHTML = `
                        <div class="text-center text-gray-500 py-8">
                            <i class="ri-chat-3-line text-4xl text-gray-400 mb-2"></i>
                            <p>Comece a conversar com ${friendName}</p>
                        </div>
                    `;
                    return;
                }
                
                messagesContainer.innerHTML = '';
                result.data.reverse().forEach(message => {
                    addMessageToChat(message);
                });
                
                scrollToBottom();
            } catch (error) {
                console.error('❌ Erro ao carregar mensagens:', error);
            }
        }
        
        function addMessageToChat(message) {
            const isOwn = message.user_id === currentUserId;
            
            const messageDiv = document.createElement('div');
            messageDiv.className = `flex ${isOwn ? 'justify-end' : 'justify-start'} animate-fade-in`;
            
            const time = formatTime(message.created_at);
            
            messageDiv.innerHTML = `
                <div class="max-w-xs lg:max-w-md">
                    <div class="${isOwn ? 'bg-blue-600 text-white' : 'bg-white border border-gray-200 text-gray-900'} rounded-2xl px-4 py-3 shadow-sm">
                        ${!isOwn ? `<p class="text-xs font-semibold mb-1 text-gray-600">${escapeHtml(message.user.name)}</p>` : ''}
                        <p class="text-sm break-words">${escapeHtml(message.content)}</p>
                        <p class="text-xs ${isOwn ? 'text-blue-100' : 'text-gray-400'} mt-1 text-right">
                            ${time}
                        </p>
                    </div>
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }
        
        async function sendMessage() {
            console.log('===================================');
            console.log('📤 FUNÇÃO sendMessage() INICIADA');
            console.log('===================================');
            
            console.log('1️⃣ Pegando valor do input...');
            const content = messageInput.value.trim();
            console.log('   Conteúdo:', content);
            console.log('   Tamanho:', content.length);
            
            if (!content) {
                console.warn('⚠️ Mensagem vazia, não enviando');
                console.log('===================================');
                return;
            }
            
            console.log('2️⃣ Preparando envio...');
            console.log('   Mensagem:', content);
            console.log('   Room ID:', roomId);
            
            try {
                console.log('3️⃣ Procurando CSRF token...');
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                console.log('   Token element:', csrfToken);
                
                if (!csrfToken) {
                    console.error('❌ CSRF token não encontrado no DOM!');
                    alert('Erro: Token CSRF não encontrado. Recarregue a página.');
                    return;
                }
                
                const tokenValue = csrfToken.getAttribute('content');
                console.log('   Token value:', tokenValue ? tokenValue.substring(0, 10) + '...' : 'null');
                console.log('✅ CSRF Token encontrado');
                
                console.log('4️⃣ Preparando requisição...');
                const url = `/api/rooms/${roomId}/messages`;
                console.log('   URL:', url);
                
                const requestBody = { content };
                console.log('   Body:', requestBody);
                console.log('   Body JSON:', JSON.stringify(requestBody));
                
                const headers = {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': tokenValue,
                    'Accept': 'application/json',
                    'X-Socket-ID': pusher.connection.socket_id
                };
                console.log('   Headers:', headers);
                console.log('   Socket ID:', pusher.connection.socket_id);
                
                console.log('5️⃣ Enviando requisição fetch...');
                const response = await fetch(url, {
                    method: 'POST',
                    headers: headers,
                    body: JSON.stringify(requestBody)
                });
                
                console.log('6️⃣ Resposta recebida!');
                console.log('   Status:', response.status);
                console.log('   Status Text:', response.statusText);
                console.log('   OK:', response.ok);
                console.log('   Headers:', Object.fromEntries(response.headers));
                
                console.log('7️⃣ Parseando JSON...');
                const result = await response.json();
                console.log('   Result:', result);
                
                if (response.ok && result.success) {
                    console.log('8️⃣ Sucesso! Limpando input...');
                    messageInput.value = '';
                    console.log('   Input limpo');
                    
                    console.log('9️⃣ Adicionando mensagem ao chat...');
                    console.log('   Mensagem:', result.message);
                    addMessageToChat(result.message);
                    console.log('   Mensagem adicionada');
                    
                    console.log('===================================');
                    console.log('✅ MENSAGEM ENVIADA COM SUCESSO!');
                    console.log('===================================');
                } else {
                    console.error('❌ Resposta não foi bem sucedida');
                    console.error('   Status:', response.status);
                    console.error('   Result:', result);
                    throw new Error(result.message || result.error || 'Erro ao enviar mensagem');
                }
            } catch (error) {
                console.error('===================================');
                console.error('❌ ERRO AO ENVIAR MENSAGEM');
                console.error('===================================');
                console.error('   Tipo:', error.name);
                console.error('   Mensagem:', error.message);
                console.error('   Stack:', error.stack);
                console.error('===================================');
                alert('Erro ao enviar mensagem: ' + error.message);
            }
        }
        
        function scrollToBottom() {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
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
            if (diff < 3600000) return Math.floor(diff / 60000) + 'm atrás';
            if (diff < 86400000) return date.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' });
            
            return date.toLocaleDateString('pt-BR', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' });
        }
        
        console.log('✅ Chat direto inicializado!');
    });
})();
</script>

<style>
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>

@endsection
