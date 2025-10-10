# Debug do Chat - Guia Completo

## 🔍 Como Testar e Ver os Logs

### 1. Preparação

Abra **3 terminais** diferentes:

#### Terminal 1 - Servidor Laravel
```bash
cd /home/vgabrielk/laravel-desafio
php artisan serve
```

#### Terminal 2 - Servidor Reverb
```bash
cd /home/vgabrielk/laravel-desafio
php artisan reverb:start
```

#### Terminal 3 - Logs do Laravel
```bash
cd /home/vgabrielk/laravel-desafio
tail -f storage/logs/laravel.log
```

### 2. Testar no Navegador

1. Abra o navegador e pressione **F12** para abrir o Console
2. Vá para `http://localhost:8000/chat`
3. Abra uma conversa com um amigo
4. Digite uma mensagem e envie

### 3. O Que Você Deve Ver

#### No Console do Navegador (F12):

```
📤 Enviando mensagem: teste
📤 Room ID: 1
🔑 CSRF Token encontrado
🌐 URL: /api/rooms/1/messages
📦 Body: {content: "teste"}
📥 Response status: 201
📥 Response OK: true
📦 Response data: {success: true, message: {...}}
✅ Mensagem enviada com sucesso!
📝 Mensagem completa: {...}
```

#### No Terminal 3 (Logs do Laravel):

```
[2024-10-09 15:30:00] local.INFO: 📥 MessageController::store - Iniciando {"room_id":1,"user_id":1,"request_data":{"content":"teste"}}
[2024-10-09 15:30:00] local.INFO: ✅ Usuário é participante da sala
[2024-10-09 15:30:00] local.INFO: ✅ Dados validados com sucesso {"content":"teste"}
[2024-10-09 15:30:00] local.INFO: ✅ Mensagem criada no banco {"message_id":1,"content":"teste"}
[2024-10-09 15:30:00] local.INFO: 📡 Enviando broadcast...
[2024-10-09 15:30:00] local.INFO: ✅ Broadcast enviado com sucesso
[2024-10-09 15:30:00] local.INFO: ✅ Retornando resposta de sucesso
```

#### No Terminal 2 (Reverb):

```
[2024-10-09 15:30:00] INFO  Message sent on channel private-room.1
```

### 4. Possíveis Erros e Soluções

#### ❌ CSRF Token não encontrado

**Sintoma no Console:**
```
❌ CSRF token não encontrado!
```

**Solução:**
1. Verifique se o layout `app.blade.php` tem a tag:
   ```html
   <meta name="csrf-token" content="{{ csrf_token() }}">
   ```
2. Recarregue a página (Ctrl+F5)

#### ❌ Nenhuma sala selecionada

**Sintoma no Console:**
```
❌ Nenhuma sala selecionada!
```

**Solução:**
1. Certifique-se de que está dentro de uma conversa
2. A URL deve ser `/chat/direct/{friend_id}` ou você deve ter clicado em uma sala

#### ❌ Response status: 403

**Sintoma no Console:**
```
📥 Response status: 403
❌ Resposta não foi bem sucedida: {error: "Acesso negado"}
```

**Sintoma nos Logs:**
```
[2024-10-09 15:30:00] local.WARNING: ❌ Usuário não é participante da sala
```

**Solução:**
1. Verifique se você é participante da sala
2. Execute no terminal:
   ```bash
   php artisan tinker
   ```
   Depois:
   ```php
   $room = \App\Models\Room::find(1);
   $room->participants()->attach([1 => ['joined_at' => now()]]);
   ```

#### ❌ Response status: 422

**Sintoma no Console:**
```
📥 Response status: 422
❌ Resposta não foi bem sucedida: {message: "The content field is required."}
```

**Sintoma nos Logs:**
```
[2024-10-09 15:30:00] local.ERROR: ❌ Erro na validação {"error":"The content field is required."}
```

**Solução:**
- A mensagem está vazia ou o formato do JSON está errado
- Verifique se o campo de input tem ID correto: `id="messageInput"`

#### ❌ Response status: 500

**Sintoma no Console:**
```
📥 Response status: 500
```

**Sintoma nos Logs:**
```
[2024-10-09 15:30:00] local.ERROR: SQLSTATE[HY000]: General error...
```

**Solução:**
1. Verifique se as migrações foram executadas:
   ```bash
   php artisan migrate:status
   ```
2. Execute as migrações se necessário:
   ```bash
   php artisan migrate
   ```

#### ❌ WebSocket não conecta

**Sintoma no Console:**
```
Pusher : Error : {"type":"WebSocketError","error":{"type":"PusherError","data":{"code":null,"message":"Unable to connect"}}}
```

**Solução:**
1. Verifique se o Reverb está rodando:
   ```bash
   ps aux | grep reverb
   ```
2. Se não estiver, inicie:
   ```bash
   php artisan reverb:start
   ```
3. Verifique as variáveis de ambiente:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

#### ❌ Broadcast não chega no outro usuário

**Sintoma:**
- A mensagem aparece para quem enviou
- Mas não aparece para o destinatário
- Nos logs do backend aparece tudo OK

**Solução:**
1. Verifique se o Reverb está rodando
2. Abra o console do navegador do **destinatário**
3. Procure por:
   ```
   📨 Nova mensagem recebida: {...}
   ```
4. Se não aparecer, verifique a conexão WebSocket
5. Verifique se o destinatário se inscreveu no canal correto:
   ```javascript
   Pusher : Subscribed to private-room.1
   ```

### 5. Testar Conexão WebSocket

Cole isso no console do navegador (F12):

```javascript
// Verificar se o Pusher está conectado
if (typeof pusher !== 'undefined') {
    console.log('✅ Pusher definido');
    console.log('Estado da conexão:', pusher.connection.state);
    console.log('Canais inscritos:', pusher.allChannels());
} else {
    console.log('❌ Pusher não está definido');
}
```

**Resposta esperada:**
```
✅ Pusher definido
Estado da conexão: connected
Canais inscritos: [PrivateChannel]
```

### 6. Verificar Banco de Dados

```bash
php artisan tinker
```

```php
// Ver todas as mensagens
\App\Models\Message::all();

// Ver última mensagem enviada
\App\Models\Message::latest()->first();

// Ver participantes de uma sala
$room = \App\Models\Room::find(1);
$room->participants;
```

### 7. Limpar Tudo e Recomeçar

Se nada funcionar, tente limpar tudo:

```bash
# Parar Reverb (Ctrl+C no terminal 2)

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Recriar banco (CUIDADO: apaga tudo)
php artisan migrate:fresh --seed

# Reiniciar servidor
php artisan serve

# Reiniciar Reverb
php artisan reverb:start
```

### 8. Checklist Final

Antes de testar, certifique-se de que:

- [ ] Servidor Laravel está rodando (`php artisan serve`)
- [ ] Servidor Reverb está rodando (`php artisan reverb:start`)
- [ ] Variável `BROADCAST_CONNECTION=reverb` no `.env`
- [ ] Dois usuários estão cadastrados e são amigos
- [ ] Console do navegador está aberto (F12)
- [ ] Logs do Laravel estão sendo visualizados (`tail -f storage/logs/laravel.log`)

### 9. Teste Passo a Passo

1. **Abra dois navegadores diferentes** (Chrome e Firefox, por exemplo)
2. **Faça login** com usuários diferentes em cada navegador
3. **No Navegador 1:**
   - Vá para "Amigos"
   - Verifique que são amigos
   - Vá para "Chat" > clique no amigo
4. **No Navegador 2:**
   - Vá para "Chat" > clique no amigo
5. **No Navegador 1:**
   - Abra o Console (F12)
   - Digite uma mensagem: "Teste 1"
   - Clique em "Enviar"
   - Verifique os logs no console
6. **No Navegador 2:**
   - Verifique se a mensagem apareceu
   - Verifique os logs no console
7. **Repita do Navegador 2 para o 1**

### 10. Informações Úteis

#### Ver configuração do Broadcast
```bash
php artisan tinker
```
```php
config('broadcasting.default');
config('broadcasting.connections.reverb');
```

#### Ver rotas da API
```bash
php artisan route:list | grep messages
```

#### Ver estrutura das tabelas
```bash
php artisan tinker
```
```php
\Schema::getColumnListing('messages');
\Schema::getColumnListing('rooms');
\Schema::getColumnListing('room_participants');
```

