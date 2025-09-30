## 🏛️ Principais Entidades e Tabelas

### 1️⃣ **users**

Representa qualquer pessoa autenticada no sistema.

| Coluna     | Tipo                  | Observação                          |
| ---------- | --------------------- | ----------------------------------- |
| id (PK)    | BIGINT                | Identificador único                 |
| name       | VARCHAR               | Nome do usuário                     |
| email      | VARCHAR UNIQUE        | Login                               |
| password   | VARCHAR               | Senha hash                          |
| status     | ENUM(active,inactive) | Para suspender usuários sem excluir |
| created_at | TIMESTAMP             |                                     |
| updated_at | TIMESTAMP             |                                     |

---

### 2️⃣ **groups**

Grupos de votação/enquetes.

| Coluna        | Tipo                  | Observação                                                                        |
| ------------- | --------------------- | --------------------------------------------------------------------------------- |
| id (PK)       | BIGINT                |                                                                                   |
| name          | VARCHAR               | Nome do grupo (ex.: “Turma A”, “Time Dev”)                                        |
| description   | TEXT NULL             | Descrição opcional                                                                |
| creator_id FK | BIGINT                | Usuário criador do grupo                                                          |
| visibility    | ENUM(public, private) | Define se o grupo é público (qualquer um pode entrar) ou privado (só com convite) |
| created_at    | TIMESTAMP             |                                                                                   |
| updated_at    | TIMESTAMP             |                                                                                   |

---

### 3️⃣ **group_members**

Usuários participantes de cada grupo, com papéis.

| Coluna        | Tipo                           | Observação                  |
| ------------- | ------------------------------ | --------------------------- |
| id (PK)       | BIGINT                         |                             |
| group_id FK   | BIGINT                         |                             |
| user_id FK    | BIGINT                         |                             |
| role          | ENUM(member, moderator, owner) | `owner` é sempre o criador. |
| status        | ENUM(pending, active, banned)  | Para convites/aceitação     |
| invited_by FK | BIGINT NULL                    | Quem enviou o convite       |
| created_at    | TIMESTAMP                      |                             |

---

### 4️⃣ **polls**

Enquetes criadas dentro de um grupo.

| Coluna         | Tipo                      | Observação                                                                    |
| -------------- | ------------------------- | ----------------------------------------------------------------------------- |
| id (PK)        | BIGINT                    |                                                                               |
| group_id FK    | BIGINT                    |                                                                               |
| creator_id FK  | BIGINT                    | Somente `owner` ou `moderator` podem criar                                    |
| title          | VARCHAR                   | Título da enquete                                                             |
| description    | TEXT NULL                 |                                                                               |
| type           | ENUM(public, private)     | **public**: visível a todos no grupo. **private**: só convidados específicos. |
| anonymous      | BOOLEAN                   | Se TRUE, não exibe quem votou                                                 |
| allow_multiple | BOOLEAN                   | Permite múltiplas escolhas                                                    |
| start_at       | DATETIME                  | Data de início                                                                |
| end_at         | DATETIME                  | Data de encerramento                                                          |
| status         | ENUM(draft, open, closed) | Estado da enquete                                                             |
| created_at     | TIMESTAMP                 |                                                                               |

---

### 5️⃣ **poll_options**

Opções de resposta para cada enquete.

| Coluna     | Tipo      | Observação         |
| ---------- | --------- | ------------------ |
| id (PK)    | BIGINT    |                    |
| poll_id FK | BIGINT    |                    |
| text       | VARCHAR   | Descrição da opção |
| created_at | TIMESTAMP |                    |

---

### 6️⃣ **poll_votes**

Registra os votos.

| Coluna       | Tipo         | Observação                                      |
| ------------ | ------------ | ----------------------------------------------- |
| id (PK)      | BIGINT       |                                                 |
| poll_id FK   | BIGINT       |                                                 |
| option_id FK | BIGINT       | Opção escolhida                                 |
| voter_id FK  | BIGINT NULL  | **NULL** se for anônimo (armazenar só contagem) |
| created_at   | TIMESTAMP    | Data do voto                                    |
| ip_address   | VARCHAR NULL | Opcional, para auditoria/segurança              |

> ⚡ Importante:
>
> Para enquetes **anônimas**, você pode:
>
> -   Salvar `voter_id` como **NULL**, **OU**
> -   Guardar em uma tabela separada criptografada para evitar rastreio.

---

### 7️⃣ **invitations** (opcional)

Convites diretos para grupos ou enquetes privadas.

| Coluna        | Tipo                                       | Observação                        |
| ------------- | ------------------------------------------ | --------------------------------- |
| id (PK)       | BIGINT                                     |                                   |
| group_id FK   | BIGINT                                     |                                   |
| email         | VARCHAR                                    | E-mail do convidado               |
| token         | VARCHAR                                    | Código único para aceitar convite |
| status        | ENUM(pending, accepted, declined, expired) |                                   |
| invited_by FK | BIGINT                                     | Quem enviou                       |
| created_at    | TIMESTAMP                                  |                                   |

---

## 🔄 Fluxo de Funcionamento

1. **Cadastro/Autenticação**
    - Usuário cria conta ou entra via SSO (Google, Facebook, etc.).
    - Cada usuário possui um **id** único.
2. **Criação de Grupo**
    - Usuário cria um **group** (se torna `owner`).
    - Define se o grupo é **public** ou **private**.
    - Se privado, convida membros via **group_members** ou **invitations**.
3. **Entrada no Grupo**
    - Público: usuário solicita entrada ou entra direto.
    - Privado: precisa aceitar convite.
4. **Criação de Enquete (poll)**
    - Apenas `owner` ou `moderator` do grupo podem criar.
    - Define:
        - Tipo (**public** ou **private**)
        - Se é **anonymous**
        - Opções de voto (**poll_options**)
        - Data de início/fim.
5. **Votação**
    - Usuário autenticado e membro do grupo vota.
    - Se `anonymous = true` → grava voto com `voter_id` NULL ou criptografado.
    - Se `anonymous = false` → grava normalmente em **poll_votes**.
6. **Resultado**
    - Enquetes públicas: resultado visível a todos.
    - Enquetes privadas: resultado só para criador/administradores ou conforme regra definida.
