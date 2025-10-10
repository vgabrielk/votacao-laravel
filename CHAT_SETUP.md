# Setup Rápido do Chat

## Passo a Passo para Configurar o Chat

### 1. Instalar Dependências do Laravel Reverb

Se ainda não instalou, execute:

```bash
composer require laravel/reverb
php artisan reverb:install
```

### 2. Configurar Variáveis de Ambiente

Adicione ou edite as seguintes linhas no arquivo `.env`:

```env
# Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb Configuration
REVERB_APP_ID=123456
REVERB_APP_KEY=your-reverb-app-key
REVERB_APP_SECRET=your-reverb-app-secret
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http
```

**Importante:** Se essas variáveis já existirem, apenas altere `BROADCAST_CONNECTION` para `reverb`.

### 3. Limpar o Cache de Configuração

```bash
php artisan config:clear
php artisan cache:clear
```

### 4. Criar as Tabelas do Banco (se necessário)

Se as tabelas do chat ainda não foram criadas:

```bash
php artisan migrate
```

### 5. Iniciar o Servidor Reverb

Em um terminal separado, execute:

```bash
php artisan reverb:start
```

**Saída esperada:**
```
  INFO  Reverb server started on 127.0.0.1:8080

  Press Ctrl+C to stop the server
```

### 6. Iniciar o Servidor Web

Em outro terminal, execute:

```bash
php artisan serve
```

### 7. Testar o Chat

1. Abra dois navegadores diferentes ou duas abas em modo anônimo
2. Faça login com dois usuários diferentes em cada navegador
3. Certifique-se de que os dois usuários são amigos:
   - Vá em "Amigos" no menu
   - Adicione um amigo se necessário
4. Vá para "Chat" e abra uma conversa com o amigo
5. Envie uma mensagem
6. Verifique se a mensagem aparece em ambas as telas

## Debug

### Console do Navegador

Abra o console (F12) e procure por mensagens como:

```
🚀 Iniciando chat direto...
✅ DOM carregado
📥 Carregando mensagens...
📦 Mensagens recebidas: 0
📤 Enviando mensagem: Olá
✅ Mensagem enviada
📨 Nova mensagem recebida: { message: {...} }
```

### Problemas Comuns

#### 1. WebSocket não conecta

**Sintoma:** No console aparece erro de conexão WebSocket

**Solução:**
- Verifique se o Reverb está rodando (`php artisan reverb:start`)
- Verifique se a porta 8080 está livre
- Verifique as variáveis de ambiente do Reverb

#### 2. Mensagem não chega no outro usuário

**Sintoma:** A mensagem aparece para quem enviou, mas não para o destinatário

**Solução:**
- Verifique se o Reverb está rodando
- Verifique se os dois usuários estão logados
- Verifique se são amigos
- Abra o console de ambos os navegadores para ver os logs

#### 3. Erro "Unauthenticated" ao enviar mensagem

**Sintoma:** No console aparece erro 401 Unauthenticated

**Solução:**
- Verifique se está logado
- Limpe o cache do navegador e faça login novamente
- Verifique se o CSRF token está presente na página

#### 4. Erro ao se inscrever no canal privado

**Sintoma:** No console aparece erro de autenticação do canal

**Solução:**
- Verifique se o endpoint `/broadcasting/auth` está acessível
- Verifique se está logado
- Verifique se o usuário é participante da sala

## Comandos Úteis

### Ver logs do Laravel
```bash
tail -f storage/logs/laravel.log
```

### Verificar se o Reverb está rodando
```bash
ps aux | grep reverb
```

### Matar processo do Reverb (se travou)
```bash
killall php
# ou
pkill -f "artisan reverb:start"
```

### Recriar tabelas do banco (CUIDADO: apaga dados)
```bash
php artisan migrate:fresh --seed
```

## Estrutura de Canais

O sistema usa canais privados do Reverb/Pusher:

- **Canal:** `private-room.{room_id}`
- **Evento:** `App\Events\MessageSent`
- **Autenticação:** `/broadcasting/auth` (automática via Pusher)
- **Permissão:** Usuário deve ser participante da sala (verificado em `routes/channels.php`)

## Próximos Passos

Após configurar e testar o chat básico, você pode:

1. ✅ Adicionar indicador de "digitando..."
2. ✅ Adicionar notificações de mensagens não lidas
3. ✅ Adicionar suporte a emojis
4. ✅ Adicionar upload de imagens
5. ✅ Adicionar histórico de mensagens com paginação
6. ✅ Adicionar status online/offline dos usuários

## Ambiente de Produção

Para usar em produção, considere:

1. Usar um servidor de WebSocket dedicado (ex: Soketi, Pusher, Ably)
2. Configurar SSL/TLS (wss://)
3. Configurar o Reverb para rodar como serviço (supervisor, systemd)
4. Adicionar filas (queues) para o broadcasting

Exemplo de configuração com supervisor:

```ini
[program:reverb]
process_name=%(program_name)s
command=php /path/to/your/project/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/project/storage/logs/reverb.log
```

