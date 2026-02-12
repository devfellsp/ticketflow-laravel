# 🎫 Ticket Flow - Sistema de Gestão de Chamados

Sistema de gestão de chamados (tickets) desenvolvido em Laravel com autenticação, autorização e auditoria completa.

## 📋 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Tecnologias](#tecnologias)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Execução](#execução)
- [Testes](#testes)
- [Credenciais](#credenciais)
- [API - Documentação](#api---documentação)
- [Funcionalidades](#funcionalidades)
- [Arquitetura](#arquitetura)

---

## 🎯 Sobre o Projeto

Aplicação completa de gerenciamento de chamados internos com:

- ✅ Autenticação via Laravel Breeze + Sanctum
- ✅ CRUD completo de tickets
- ✅ Sistema de autorização com Policies
- ✅ Auditoria de mudanças de status
- ✅ Filtros avançados (status, prioridade, busca)
- ✅ API REST protegida
- ✅ Soft Delete
- ✅ Validações server-side
- ✅ Testes automatizados (Feature + Unit)

---

## 🛠️ Tecnologias

- **Laravel 11**
- **PHP 8.2+**
- **SQLite** (banco de dados)
- **Sanctum** (autenticação API)
- **Breeze** (autenticação web)
- **Pest** (testes)

---

## ⚙️ Requisitos

- PHP >= 8.2
- Composer
- Node.js >= 18
- NPM ou Yarn

---

## 🚀 Instalação

### 1️⃣ Clonar o repositório

```bash
git clone <seu-repositorio>
cd ticket-flow
```

### 2️⃣ Instalar dependências

```bash
composer install
npm install
```

### 3️⃣ Criar arquivo .env

```bash
cp .env.example .env
```

### 4️⃣ Gerar chave da aplicação

```bash
php artisan key:generate
```

### 5️⃣ Criar banco de dados SQLite

```bash
touch database/database.sqlite
```

### 6️⃣ Rodar migrations e seeders

```bash
php artisan migrate:fresh --seed
```

### 7️⃣ Compilar assets (opcional)

```bash
npm run dev
```

---

## ▶️ Execução

### Servidor de desenvolvimento

```bash
php artisan serve
```

Acesse: **http://localhost:8000**

---

## 🧪 Testes

### Rodar todos os testes

```bash
php artisan test
```

### Rodar testes específicos

```bash
php artisan test --filter TicketTest
php artisan test --filter TicketValidationTest
```

### Cobertura de testes

```bash
php artisan test --coverage
```

---

## 🔑 Credenciais

### Usuários criados pelo Seeder:

| Email | Senha | Role | Descrição |
|-------|-------|------|-----------|
| `admin@teste.com` | `password` | ADMIN | Administrador (pode deletar qualquer ticket) |
| `user@teste.com` | `password` | USER | Usuário comum |
| `tecnico@teste.com` | `password` | TECNICO | Técnico de suporte |

---

## 📡 API - Documentação

### Base URL

```
http://localhost:8000/api
```

### Autenticação

Todas as rotas da API requerem autenticação via **Sanctum Token**.

#### 1. Login (obter token)

```bash
POST /login
Content-Type: application/json

{
  "email": "admin@teste.com",
  "password": "password"
}
```

**Resposta:**
```json
{
  "user": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@teste.com"
  },
  "token": "1|abc123..."
}
```

#### 2. Usar o token nas requisições

```bash
Authorization: Bearer {seu-token}
```

---

### 📋 Endpoints

#### **GET /api/tickets**
Lista todos os tickets (com filtros opcionais)

**Query Parameters:**
- `status` (opcional): `ABERTO`, `EM_ANDAMENTO`, `RESOLVIDO`
- `prioridade` (opcional): `BAIXA`, `MEDIA`, `ALTA`
- `search` (opcional): busca por título ou descrição

**Exemplo:**
```bash
curl -X GET "http://localhost:8000/api/tickets?status=ABERTO&prioridade=ALTA" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "titulo": "Problema no sistema",
      "descricao": "Sistema não está respondendo corretamente",
      "status": "ABERTO",
      "prioridade": "ALTA",
      "solicitante": {
        "id": 2,
        "name": "Usuário Comum"
      },
      "responsavel": null,
      "resolved_at": null,
      "created_at": "2026-02-12T10:30:00.000000Z"
    }
  ]
}
```

---

#### **GET /api/tickets/{id}**
Detalha um ticket específico

**Exemplo:**
```bash
curl -X GET "http://localhost:8000/api/tickets/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

#### **POST /api/tickets**
Cria um novo ticket

**Body:**
```json
{
  "titulo": "Computador não liga",
  "descricao": "O computador da sala 10 não está ligando desde ontem",
  "prioridade": "ALTA",
  "responsavel_id": 3
}
```

**Validações:**
- `titulo`: obrigatório, 5-120 caracteres
- `descricao`: obrigatório, mínimo 20 caracteres
- `prioridade`: obrigatório, valores: BAIXA, MEDIA, ALTA
- `responsavel_id`: opcional, deve existir na tabela users

**Exemplo:**
```bash
curl -X POST "http://localhost:8000/api/tickets" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "titulo": "Impressora com problema",
    "descricao": "A impressora do segundo andar está com erro de papel",
    "prioridade": "MEDIA"
  }'
```

**Resposta (201):**
```json
{
  "data": {
    "id": 5,
    "titulo": "Impressora com problema",
    "status": "ABERTO",
    "prioridade": "MEDIA",
    "created_at": "2026-02-12T15:45:00.000000Z"
  }
}
```

---

#### **PATCH /api/tickets/{id}/status**
Atualiza apenas o status do ticket (cria log de auditoria)

**Body:**
```json
{
  "status": "RESOLVIDO"
}
```

**Validações:**
- `status`: obrigatório, valores: ABERTO, EM_ANDAMENTO, RESOLVIDO

**Exemplo:**
```bash
curl -X PATCH "http://localhost:8000/api/tickets/1/status" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status": "RESOLVIDO"}'
```

**Comportamento especial:**
- Quando status = `RESOLVIDO`, o campo `resolved_at` é preenchido automaticamente
- Cria registro na tabela `audit_logs` com detalhes da mudança

---

#### **PUT /api/tickets/{id}**
Atualiza um ticket completo

**Body (todos campos opcionais):**
```json
{
  "titulo": "Novo título",
  "descricao": "Nova descrição com pelo menos 20 caracteres",
  "status": "EM_ANDAMENTO",
  "prioridade": "ALTA",
  "responsavel_id": 3
}
```

---

#### **DELETE /api/tickets/{id}**
Remove um ticket (soft delete)

**Autorização:**
- Apenas o solicitante (criador) ou usuário com role ADMIN pode deletar

**Exemplo:**
```bash
curl -X DELETE "http://localhost:8000/api/tickets/1" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Resposta (200):**
```json
{
  "message": "Ticket excluído com sucesso"
}
```

---

#### **GET /api/tickets/{id}/logs**
Lista histórico de mudanças (auditoria) do ticket

**Exemplo:**
```bash
curl -X GET "http://localhost:8000/api/tickets/1/logs" \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

**Resposta:**
```json
{
  "data": [
    {
      "id": 1,
      "action": "updated",
      "description": "Ticket #1 atualizado: status: 'ABERTO' → 'RESOLVIDO'",
      "user": {
        "id": 1,
        "name": "Administrador"
      },
      "before": null,
      "after": null,
      "created_at": "2026-02-12T16:00:00.000000Z"
    }
  ]
}
```

---

#### **PATCH /api/tickets/{id}/assign**
Atribui um responsável ao ticket (cria log de auditoria)

**Body:**
```json
{
  "responsavel_id": 3
}
```

**Exemplo:**
```bash
curl -X PATCH "http://localhost:8000/api/tickets/1/assign" \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"responsavel_id": 3}'
```

---

## ⚡ Funcionalidades

### ✅ Requisitos Funcionais Implementados

| Requisito | Status |
|-----------|--------|
| Login obrigatório | ✅ Middleware auth |
| CRUD de tickets | ✅ Completo |
| Filtros (status, prioridade, busca) | ✅ Implementado |
| Soft Delete | ✅ Implementado |
| Status → RESOLVIDO preenche `resolved_at` | ✅ Automático |
| Apenas dono/admin pode deletar | ✅ Policy |
| Auditoria de mudanças de status | ✅ Tabela audit_logs |
| API REST com Sanctum | ✅ Protegida |
| Validações server-side | ✅ Form Requests |
| Migrations com índices | ✅ Otimizado |
| Seeders (users + tickets) | ✅ Implementado |
| Testes (mínimo 2) | ✅ 5 testes (Feature) |

---
---

## 🎁 BÔNUS: Sistema de Notificações com Queue

### ✅ Implementado

Quando um ticket é marcado como **RESOLVIDO**, o sistema:

1. 📧 **Envia email** para o solicitante
2. 💾 **Registra notificação** no banco de dados
3. ⚡ **Processa em background** usando Queue

---

### 📊 **Tabelas criadas**

- `jobs` - Fila de processamento
- `notifications` - Notificações enviadas

---

### 🔔 **Como funciona**

**Fluxo:**

```
PATCH /api/tickets/{id}/status {"status": "RESOLVIDO"}
          ↓
   TicketService::changeStatus()
          ↓
   resolved_at = now()
          ↓
   Dispara: TicketResolvidoNotification
          ↓
   Job entra na Queue (database)
          ↓
   Worker processa: envia email + salva no DB
```

---

### ⚙️ **Configuração**

**1. Queue no .env:**

```env
QUEUE_CONNECTION=database
MAIL_MAILER=log  # Em produção use smtp
```

**2. Rodar worker (em produção):**

```bash
php artisan queue:work
```

**3. Ver notificações de um usuário:**

```php
$user->notifications;  // Todas notificações
$user->unreadNotifications;  // Apenas não lidas
```

---

### 📧 **Exemplo de email enviado**

```
Assunto: Ticket #5 foi resolvido! 🎉

Olá, João Silva!

Seu ticket #5 - Impressora com problema foi marcado como RESOLVIDO.

Descrição: A impressora do segundo andar está com erro de papel
Prioridade: MEDIA
Resolvido em: 12/02/2026 14:30

[Ver Ticket]

Obrigado por usar nosso sistema!
```

---

### 🧪 **Testes implementados**

```bash
php artisan test --filter TicketNotification
```

- ✅ `notificacao e enviada quando ticket e resolvido`
- ✅ `notificacao nao e enviada quando status nao e resolvido`

---

### 📌 **Tecnologias usadas**

- Laravel Queues (database driver)
- Laravel Notifications (mail + database channels)
- Jobs assíncronos
- ShouldQueue interface

---

## 🏗️ Arquitetura

### Camadas da aplicação

```
┌─────────────────────────────────────────┐
│         Controller (HTTP Layer)         │  ← Recebe requests
│   - TicketController                    │
│   - Validação (Form Requests)           │
│   - Autorização (Policies)              │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│      Service Layer (Business Logic)     │  ← Lógica de negócio
│   - TicketService                       │
│   - changeStatus()                      │
│   - assignResponsible()                 │
│   - Criação de audit logs               │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│    Repository Layer (Data Access)       │  ← Acesso aos dados
│   - TicketRepository                    │
│   - Filtros e queries                   │
└─────────────────────────────────────────┘
                   ↓
┌─────────────────────────────────────────┐
│         Model (Eloquent ORM)            │  ← Dados
│   - Ticket                              │
│   - AuditLog                            │
│   - User                                │
└─────────────────────────────────────────┘
```

### Estrutura de diretórios

```
app/
├── Enums/
│   ├── TicketStatus.php
│   └── TicketPriority.php
├── Http/
│   ├── Controllers/
│   │   └── TicketController.php
│   ├── Requests/
│   │   ├── StoreTicketRequest.php
│   │   └── UpdateTicketRequest.php
│   └── Resources/
│       └── TicketResource.php
├── Models/
│   ├── Ticket.php
│   ├── AuditLog.php
│   └── User.php
├── Policies/
│   └── TicketPolicy.php
├── Repositories/
│   ├── Contracts/
│   │   └── TicketRepositoryInterface.php
│   └── TicketRepository.php
└── Services/
    └── TicketService.php
```

---

## 📊 Diagrama de Entidades

```
┌─────────────────┐         ┌─────────────────┐
│     USERS       │         │    TICKETS      │
├─────────────────┤         ├─────────────────┤
│ id              │◄───┐    │ id              │
│ name            │    │    │ titulo          │
│ email           │    └────┤ solicitante_id  │
│ password        │    ┌────┤ responsavel_id  │
│ role            │◄───┘    │ status          │
└─────────────────┘         │ prioridade      │
                            │ descricao       │
                            │ resolved_at     │
                            │ deleted_at      │
                            └─────────────────┘
                                    │
                                    │ 1:N
                                    ▼
                            ┌─────────────────┐
                            │  AUDIT_LOGS     │
                            ├─────────────────┤
                            │ id              │
                            │ auditable_type  │
                            │ auditable_id    │
                            │ user_id         │
                            │ action          │
                            │ description     │
                            │ changes (JSON)  │
                            └─────────────────┘
```

---

## 🎨 Enums

### TicketStatus

- `ABERTO` - Status inicial (padrão)
- `EM_ANDAMENTO` - Ticket sendo trabalhado
- `RESOLVIDO` - Ticket finalizado

### TicketPriority

- `BAIXA` - Peso 1
- `MEDIA` - Peso 2
- `ALTA` - Peso 3

---

## 📝 Licença

Este projeto foi desenvolvido como teste técnico.

---

## 👨‍💻 Desenvolvedor

Desenvolvido com ❤️ usando Laravel e boas práticas de Clean Architecture.