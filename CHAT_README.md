# Sistema de Chat em Tempo Real

Este sistema de chat foi implementado usando Laravel Reverb para comunicação em tempo real entre usuários.

## Funcionalidades

### ✅ Implementadas

1. **Criação de Salas de Chat**
   - Salas públicas e privadas
   - Descrição opcional
   - Sistema de participantes

2. **Mensagens em Tempo Real**
   - Envio de mensagens instantâneo
   - Notificações em tempo real
   - Histórico de mensagens

3. **Sistema de Participantes**
   - Adicionar/remover participantes
   - Controle de acesso às salas
   - Status de leitura das mensagens

4. **Interface Moderna**
   - Design responsivo com Tailwind CSS
   - Interface intuitiva
   - Modal para criação de salas

## Como Usar

### 1. Acessar o Chat
- Faça login no sistema
- Clique em "Chat" na sidebar
- Você verá suas salas de chat

### 2. Criar uma Nova Sala
- Clique no botão "Nova Sala"
- Preencha o nome da sala
- Adicione uma descrição (opcional)
- Marque como privada se necessário
- Clique em "Criar Sala"

### 3. Enviar Mensagens
- Selecione uma sala da lista
- Digite sua mensagem no campo de texto
- Pressione Enter ou clique em "Enviar"

### 4. Gerenciar Participantes
- Apenas o criador da sala pode adicionar/remover participantes
- Use as opções de gerenciamento na interface

## Configuração Técnica

### Servidores Necessários

1. **Servidor Laravel** (porta 8000)
```bash
php artisan serve
```

2. **Servidor Reverb** (porta 8080)
```bash
php artisan reverb:start --host=localhost --port=8080
```

### Estrutura do Banco de Dados

- `rooms` - Salas de chat
- `messages` - Mensagens
- `room_participants` - Participantes das salas

### Eventos de Broadcasting

- `MessageSent` - Nova mensagem enviada
- `UserJoinedRoom` - Usuário entrou na sala
- `UserLeftRoom` - Usuário saiu da sala

## Rotas da API

### Salas
- `GET /api/rooms` - Listar salas do usuário
- `POST /api/rooms` - Criar nova sala
- `GET /api/rooms/{room}` - Detalhes da sala
- `POST /api/rooms/{room}/participants` - Adicionar participante
- `DELETE /api/rooms/{room}/participants` - Remover participante

### Mensagens
- `GET /api/rooms/{room}/messages` - Listar mensagens
- `POST /api/rooms/{room}/messages` - Enviar mensagem
- `POST /api/rooms/{room}/mark-read` - Marcar como lida

## Tecnologias Utilizadas

- **Backend**: Laravel 12
- **Broadcasting**: Laravel Reverb
- **Frontend**: JavaScript + Pusher.js
- **Styling**: Tailwind CSS
- **Database**: SQLite

## Próximos Passos

### Funcionalidades Futuras
- [ ] Upload de arquivos/imagens
- [ ] Emojis e reações
- [ ] Mensagens privadas diretas
- [ ] Notificações push
- [ ] Histórico de mensagens com paginação
- [ ] Busca de mensagens
- [ ] Temas personalizáveis

### Melhorias Técnicas
- [ ] Testes automatizados
- [ ] Cache Redis para melhor performance
- [ ] Logs de auditoria
- [ ] Backup automático de mensagens
- [ ] Moderação de conteúdo

## Troubleshooting

### Problemas Comuns

1. **Reverb não conecta**
   - Verifique se o servidor Reverb está rodando na porta 8080
   - Confirme as configurações no arquivo .env

2. **Mensagens não aparecem em tempo real**
   - Verifique se o JavaScript está carregando corretamente
   - Confirme se o Pusher.js está funcionando

3. **Erro de permissão nas salas**
   - Verifique se o usuário é participante da sala
   - Confirme se as rotas de autorização estão funcionando

## Suporte

Para problemas técnicos ou dúvidas sobre o sistema de chat, consulte a documentação do Laravel Reverb ou entre em contato com a equipe de desenvolvimento.
