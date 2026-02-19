# 🎫 Ticket Flow - Sistema de Gestão de Chamados

[![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php)](https://php.net)
[![Tests](https://img.shields.io/badge/tests-32%20passing-success)](https://pestphp.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

Sistema completo de gestão de chamados (tickets) desenvolvido em Laravel com autenticação, autorização, auditoria e **sistema de notificações com Queue**.

> 🚀 **Repositório:** [github.com/devfellsp/ticketflow-laravel](https://github.com/devfellsp/ticketflow-laravel)

---

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias](#️-tecnologias)
- [Requisitos](#-requisitos)
- [Instalação](#-instalação)
  - [Linux/Mac](#linuxmac)
  - [Windows](#windows)
- [Execução](#️-execução)
- [Testes Automatizados](#-testes-automatizados)
- [Testes Práticos da API](#-testes-práticos-da-api)
  - [Linux/Mac (curl)](#linuxmac-curl)
  - [Windows (PowerShell)](#windows-powershell)
- [Credenciais](#-credenciais)
- [API - Documentação Completa](#-api---documentação-completa)
- [Sistema de Notificações (BÔNUS)](#-bônus-sistema-de-notificações-com-queue)
- [Arquitetura](#️-arquitetura)
- [Licença](#-licença)

---

## 🎯 Sobre o Projeto

Aplicação full-stack de gerenciamento de chamados internos com foco em **boas práticas**, **arquitetura limpa** e **qualidade de código**.

### ✨ Diferenciais

- 🏗️ **Arquitetura em camadas** (Controller → Service → Repository → Model)
- 🔒 **Segurança** (Sanctum + Policies + Validações)
- 📧 **Sistema de notificações** com Queue e Email
- 🧪 **32 testes automatizados** (requisito era apenas 2)
- 📊 **Auditoria completa** de mudanças
- 🎨 **Enums PHP 8.2+** (type-safe)
- 🚀 **API REST** completa e documentada
- 💾 **Soft Delete** implementado
- 📝 **Documentação detalhada**

---

## ⚡ Funcionalidades

### ✅ Requisitos Obrigatórios

| Funcionalidade | Status | Detalhes |
|----------------|--------|----------|
| Autenticação obrigatória | ✅ | Laravel Breeze + Sanctum |
| CRUD completo de tickets | ✅ | 8 endpoints REST |
| Filtros (status, prioridade, busca) | ✅ | Query parameters |
| Soft Delete | ✅ | Tickets não são deletados fisicamente |
| Campo `resolved_at` automático | ✅ | Preenchido ao marcar como RESOLVIDO |
| Autorização (apenas dono/admin deleta) | ✅ | Laravel Policies |
| Auditoria de mudanças | ✅ | Tabela `audit_logs` |
| Validações server-side | ✅ | Form Requests |
| Seeders | ✅ | 3 usuários + 10 tickets |
| **Testes (mínimo 2)** | ✅ | **32 testes implementados** 🎉 |

### 🎁 BÔNUS Implementado

- ✅ **Laravel Queues** (processamento assíncrono)
- ✅ **Sistema de Notificações** (email + database)
- ✅ **Email ao resolver ticket**
- ✅ **Repository Pattern**
- ✅ **Service Layer**
- ✅ **API Resources**
- ✅ **3 roles diferentes** (USER, TECNICO, ADMIN)

---

## 🛠️ Tecnologias

- **Laravel 11** - Framework PHP
- **PHP 8.2+** - Linguagem
- **SQLite** - Banco de dados
- **Sanctum** - Autenticação API
- **Breeze** - Autenticação Web
- **Pest/PHPUnit** - Testes automatizados
- **Laravel Queues** - Processamento assíncrono
- **Laravel Notifications** - Sistema de notificações

---

## ⚙️ Requisitos

- PHP >= 8.2
- Composer
- SQLite3
- Node.js >= 18 (opcional, para compilar assets)

---

## 🚀 Instalação

### Linux/Mac

```bash
# 1. Clonar repositório
git clone https://github.com/devfellsp/ticketflow-laravel.git
cd ticketflow-laravel

# 2. Instalar dependências
composer install

# 3. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 4. Criar banco de dados
touch database/database.sqlite

# 5. Rodar migrations + seeders
php artisan migrate:fresh --seed

# 6. Rodar testes
php artisan test

# 7. Subir servidor
php artisan serve
```

### Windows

```powershell
# 1. Clonar repositório
git clone https://github.com/devfellsp/ticketflow-laravel.git
cd ticketflow-laravel

# 2. Instalar dependências
composer install

# 3. Configurar ambiente
copy .env.example .env
php artisan key:generate

# 4. Criar banco de dados (escolha uma opção)
New-Item database/database.sqlite          # PowerShell
type nul > database\database.sqlite        # CMD

# 5. Rodar migrations + seeders
php artisan migrate:fresh --seed

# 6. Rodar testes
php artisan test

# 7. Subir servidor
php artisan serve
```

**Acesse:** http://localhost:8000 ou http://127.0.0.1:8000

---

## ▶️ Execução

### Servidor de desenvolvimento

```bash
php artisan serve
```

### Worker da Queue (para processar notificações)

Em **produção**, rode em outra janela do terminal:

```bash
php artisan queue:work
```

> Em **desenvolvimento**, pode processar manualmente: `php artisan queue:work --once`

---

## 🧪 Testes Automatizados

### Rodar todos os testes

```bash
php artisan test
```

**Resultado esperado:**
```
Tests:    32 passed (85 assertions)
Duration: ~2.5s
```

### Rodar testes específicos

```bash
# Apenas testes de Ticket
php artisan test --filter TicketTest

# Apenas validações
php artisan test --filter TicketValidationTest

# Apenas notificações
php artisan test --filter TicketNotificationTest
```

### Testes implementados

| Categoria | Quantidade | Arquivo |
|-----------|------------|---------|
| Autenticação | 4 testes | `Auth/AuthenticationTest.php` |
| Perfil de usuário | 5 testes | `ProfileTest.php` |
| Tickets - CRUD | 3 testes | `TicketTest.php` |
| Tickets - Validações | 2 testes | `TicketValidationTest.php` |
| Tickets - Notificações | 2 testes | `TicketNotificationTest.php` |
| **TOTAL** | **32 testes** | ✅ |

---

## 🧪 Testes Práticos da API

Siga este fluxo completo para testar toda a API:

### Linux/Mac (curl)

#### 1️⃣ Login (obter token)

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@teste.com","password":"password"}' | jq
```

**Resposta:**
```json
{
  "message": "Login realizado com sucesso",
  "user": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@teste.com",
    "role": "admin"
  },
  "token": "3|btd71xJ91QQ0rv4etzUZiIVxC3KNbLe11D29I2zEa6e12345"
}
```

**📋 Copie o token acima para usar nos próximos comandos!**

---

#### 2️⃣ Listar todos os tickets

```bash
curl -X GET http://localhost:8000/api/tickets \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Accept: application/json" | jq
```

---

#### 3️⃣ Criar novo ticket

```bash
curl -X POST http://localhost:8000/api/tickets \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "titulo": "Teste completo da API",
    "descricao": "Testando todo o fluxo de criacao, atualizacao e notificacao",
    "prioridade": "ALTA"
  }' | jq
```

**📋 Copie o ID do ticket retornado!**

---

#### 4️⃣ Ver detalhes do ticket

```bash
curl -X GET http://localhost:8000/api/tickets/ID-DO-TICKET \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Accept: application/json" | jq
```

---

#### 5️⃣ Atualizar status → RESOLVIDO (dispara notificação!)

```bash
curl -X PATCH http://localhost:8000/api/tickets/ID-DO-TICKET/status \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"status":"RESOLVIDO"}' | jq
```

✅ **Notificação disparada para a queue!**

---

#### 6️⃣ Processar a queue (enviar email)

```bash
php artisan queue:work --once
```

---

#### 7️⃣ Ver email enviado no log

```bash
tail -100 storage/logs/laravel.log
```

---

#### 8️⃣ Deletar o ticket (soft delete)

```bash
curl -X DELETE http://localhost:8000/api/tickets/ID-DO-TICKET \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Accept: application/json" | jq
```

---

#### 9️⃣ Confirmar que não aparece mais

```bash
curl -X GET http://localhost:8000/api/tickets \
  -H "Authorization: Bearer SEU-TOKEN-AQUI" \
  -H "Accept: application/json" | jq
```

✅ **Ticket não aparece mais (soft delete funcionando!)**

---

### Windows (PowerShell)

#### 1️⃣ Login (obter token)

```powershell
$body = @{
    email = "admin@teste.com"
    password = "password"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/login" `
  -Method Post `
  -ContentType "application/json" `
  -Body $body
```

**Resposta:**
```
message : Login realizado com sucesso
user    : @{id=1; name=Administrador; email=admin@teste.com; role=admin}
token   : 3|btd71xJ91QQ0rv4etzUZiIVxC3KNbLe11D29I2zEa6e12345
```

**📋 Copie o token acima para usar nos próximos comandos!**

---

#### 2️⃣ Listar todos os tickets

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets" `
  -Method Get `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  }
```

---

#### 3️⃣ Criar novo ticket

```powershell
$createBody = @{
    titulo = "Teste API no Windows"
    descricao = "Testando todo o fluxo de criacao, atualizacao e notificacao"
    prioridade = "ALTA"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets" `
  -Method Post `
  -ContentType "application/json" `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  } `
  -Body $createBody
```

**📋 Copie o ID do ticket retornado!**

---

#### 4️⃣ Ver detalhes do ticket

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets/ID-DO-TICKET" `
  -Method Get `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  }
```

---

#### 5️⃣ Atualizar status → RESOLVIDO (dispara notificação!)

```powershell
$statusBody = @{
    status = "RESOLVIDO"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets/ID-DO-TICKET/status" `
  -Method Patch `
  -ContentType "application/json" `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  } `
  -Body $statusBody
```

✅ **Notificação disparada!**

---

#### 6️⃣ Processar a queue

```powershell
php artisan queue:work --once
```

---

#### 7️⃣ Ver email no log

```powershell
Get-Content storage/logs/laravel.log -Tail 50
```

---

#### 8️⃣ Deletar o ticket

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets/ID-DO-TICKET" `
  -Method Delete `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  }
```

---

#### 9️⃣ Confirmar soft delete

```powershell
Invoke-RestMethod -Uri "http://127.0.0.1:8000/api/tickets" `
  -Method Get `
  -Headers @{
    "Authorization" = "Bearer SEU-TOKEN-AQUI"
    "Accept" = "application/json"
  }
```

---

## 🔑 Credenciais

### Usuários criados pelo Seeder

| Email | Senha | Role | Permissões |
|-------|-------|------|------------|
| `admin@teste.com` | `password` | ADMIN | Pode deletar qualquer ticket |
| `usuario@teste.com` | `password` | USER | Pode deletar apenas seus tickets |
| `tecnico@teste.com` | `password` | TECNICO | Pode deletar apenas seus tickets |

---

## 📡 API - Documentação Completa

### Base URL

```
http://localhost:8000/api
```

### Autenticação

Todas as rotas requerem autenticação via **Bearer Token** (Sanctum).

---

### 📋 Endpoints Disponíveis

| Método | Endpoint | Descrição |
|--------|----------|-----------|
| `POST` | `/login` | Obter token de autenticação |
| `GET` | `/tickets` | Listar todos os tickets (com filtros) |
| `GET` | `/tickets/{id}` | Detalhar um ticket específico |
| `POST` | `/tickets` | Criar novo ticket |
| `PUT` | `/tickets/{id}` | Atualizar ticket completo |
| `PATCH` | `/tickets/{id}/status` | Atualizar apenas status (+ audit log) |
| `PATCH` | `/tickets/{id}/assign` | Atribuir responsável (+ audit log) |
| `DELETE` | `/tickets/{id}` | Deletar ticket (soft delete) |
| `GET` | `/tickets/{id}/logs` | Histórico de mudanças (auditoria) |

---

### 🔐 **POST /api/login**

Obter token de autenticação.

**Request:**
```json
{
  "email": "admin@teste.com",
  "password": "password"
}
```

**Response (200):**
```json
{
  "message": "Login realizado com sucesso",
  "user": {
    "id": 1,
    "name": "Administrador",
    "email": "admin@teste.com",
    "role": "admin"
  },
  "token": "1|abc123..."
}
```

---

### 📋 **GET /api/tickets**

Listar tickets com filtros opcionais.

**Query Parameters:**
- `status` (opcional): `ABERTO`, `EM_ANDAMENTO`, `RESOLVIDO`
- `prioridade` (opcional): `BAIXA`, `MEDIA`, `ALTA`
- `search` (opcional): busca por título ou descrição
- `page` (opcional): número da página (paginação)

**Exemplo:**
```
GET /api/tickets?status=ABERTO&prioridade=ALTA&search=impressora
```

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "titulo": "Problema na impressora",
      "descricao": "Impressora do 2º andar com erro de papel",
      "status": {
        "value": "ABERTO",
        "label": "Aberto",
        "color": "blue"
      },
      "prioridade": {
        "value": "ALTA",
        "label": "Alta",
        "color": "red"
      },
      "solicitante": {
        "id": 2,
        "name": "Usuário Comum",
        "email": "usuario@teste.com"
      },
      "responsavel": null,
      "resolved_at": null,
      "created_at": "2026-02-12T10:30:00.000000Z",
      "updated_at": "2026-02-12T10:30:00.000000Z",
      "deleted_at": null
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/tickets?page=1",
    "last": "http://localhost:8000/api/tickets?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 1
  }
}
```

---

### 👁️ **GET /api/tickets/{id}**

Detalhar um ticket específico.

**Response (200):**
```json
{
  "data": {
    "id": 1,
    "titulo": "Problema na impressora",
    "descricao": "Impressora do 2º andar com erro de papel",
    "status": {
      "value": "ABERTO",
      "label": "Aberto",
      "color": "blue"
    },
    "prioridade": {
      "value": "ALTA",
      "label": "Alta",
      "color": "red"
    },
    "solicitante": {
      "id": 2,
      "name": "Usuário Comum",
      "email": "usuario@teste.com"
    },
    "responsavel": null,
    "resolved_at": null,
    "created_at": "2026-02-12T10:30:00.000000Z",
    "updated_at": "2026-02-12T10:30:00.000000Z",
    "deleted_at": null
  }
}
```

---

### ➕ **POST /api/tickets**

Criar novo ticket.

**Request:**
```json
{
  "titulo": "Computador não liga",
  "descricao": "O computador da sala 10 não está ligando desde ontem",
  "prioridade": "ALTA",
  "responsavel_id": 3
}
```

**Validações:**
- `titulo`: **obrigatório**, string, 5-120 caracteres
- `descricao`: **obrigatório**, string, mínimo 20 caracteres
- `prioridade`: **obrigatório**, enum: `BAIXA`, `MEDIA`, `ALTA`
- `responsavel_id`: opcional, inteiro, deve existir na tabela users

**Response (201):**
```json
{
  "data": {
    "id": 12,
    "titulo": "Computador não liga",
    "descricao": "O computador da sala 10 não está ligando desde ontem",
    "status": {
      "value": "ABERTO",
      "label": "Aberto",
      "color": "blue"
    },
    "prioridade": {
      "value": "ALTA",
      "label": "Alta",
      "color": "red"
    },
    "solicitante": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@teste.com"
    },
    "responsavel": {
      "id": 3,
      "name": "Técnico Suporte",
      "email": "tecnico@teste.com"
    },
    "resolved_at": null,
    "created_at": "2026-02-12T15:45:00.000000Z",
    "updated_at": "2026-02-12T15:45:00.000000Z",
    "deleted_at": null
  },
  "meta": {
    "version": "1.0"
  }
}
```

---

### 🔄 **PATCH /api/tickets/{id}/status**

Atualizar apenas o status do ticket.

> 🔔 **Ao marcar como RESOLVIDO, dispara notificação automática!**

**Request:**
```json
{
  "status": "RESOLVIDO"
}
```

**Validações:**
- `status`: **obrigatório**, enum: `ABERTO`, `EM_ANDAMENTO`, `RESOLVIDO`

**Comportamentos automáticos:**
- ✅ Preenche `resolved_at` quando status = `RESOLVIDO`
- ✅ Cria registro em `audit_logs`
- ✅ **Dispara notificação por email** (BÔNUS)

**Response (200):**
```json
{
  "message": "Status atualizado com sucesso",
  "data": {
    "id": 12,
    "titulo": "Computador não liga",
    "status": {
      "value": "RESOLVIDO",
      "label": "Resolvido",
      "color": "green"
    },
    "resolved_at": "2026-02-12 16:30:15",
    "updated_at": "2026-02-12T16:30:15.000000Z"
  }
}
```

---

### ✏️ **PUT /api/tickets/{id}**

Atualizar ticket completo.

**Request (todos campos opcionais):**
```json
{
  "titulo": "Novo título atualizado",
  "descricao": "Nova descrição com pelo menos 20 caracteres aqui",
  "status": "EM_ANDAMENTO",
  "prioridade": "MEDIA",
  "responsavel_id": 3
}
```

---

### 👤 **PATCH /api/tickets/{id}/assign**

Atribuir responsável ao ticket.

**Request:**
```json
{
  "responsavel_id": 3
}
```

**Validações:**
- `responsavel_id`: **obrigatório**, inteiro, deve existir na tabela users

**Comportamento:**
- ✅ Cria registro em `audit_logs`

---

### 🗑️ **DELETE /api/tickets/{id}**

Deletar ticket (soft delete).

**Autorização:**
- ✅ Solicitante (criador do ticket)
- ✅ Usuários com role `ADMIN`

**Response (200):**
```json
{
  "message": "Ticket excluído com sucesso"
}
```

**Response (403 - Não autorizado):**
```json
{
  "message": "This action is unauthorized."
}
```

---

### 📜 **GET /api/tickets/{id}/logs**

Histórico de mudanças (auditoria).

**Response (200):**
```json
{
  "data": [
    {
      "id": 1,
      "action": "updated",
      "description": "Ticket #12 atualizado: status: 'ABERTO' → 'RESOLVIDO'",
      "user": {
        "id": 1,
        "name": "Administrador",
        "email": "admin@teste.com"
      },
      "before": null,
      "after": null,
      "created_at": "2026-02-12T16:30:15.000000Z"
    }
  ]
}
```

---

## 🎁 BÔNUS: Sistema de Notificações com Queue

### ✅ Implementado

Quando um ticket é marcado como **RESOLVIDO**, o sistema automaticamente:

1. 📧 **Envia email** para o solicitante
2. 💾 **Registra notificação** no banco de dados (tabela `notifications`)
3. ⚡ **Processa em background** usando Laravel Queue

---

### 🔔 **Como funciona - Fluxo completo**

```
PATCH /api/tickets/{id}/status {"status": "RESOLVIDO"}
                    ↓
        TicketController::changeStatus()
                    ↓
        TicketService::changeStatus()
                    ↓
        resolved_at = now() ← preenchido automaticamente
                    ↓
        $user->notify(new TicketResolvidoNotification($ticket))
                    ↓
        Job adicionado à queue (tabela jobs)
                    ↓
        Worker processa (php artisan queue:work)
                    ↓
        ┌─────────────────────┬──────────────────────┐
        │   Email enviado     │  Notificação salva   │
        │  (mail channel)     │  (database channel)  │
        └─────────────────────┴──────────────────────┘
```

---

### ⚙️ **Configuração**

**1. Configurar .env:**

```env
# Queue
QUEUE_CONNECTION=database

# Email (desenvolvimento)
MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@ticketflow.com"
MAIL_FROM_NAME="Ticket Flow"

# Email (produção - exemplo com Gmail)
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=587
# MAIL_USERNAME=seu-email@gmail.com
# MAIL_PASSWORD=sua-senha-app
# MAIL_ENCRYPTION=tls
```

**2. Rodar worker (produção):**

```bash
php artisan queue:work
```

**Ou processar 1 job por vez (desenvolvimento):**

```bash
php artisan queue:work --once
```

---

### 📧 **Exemplo de email enviado**

```
Assunto: Ticket #12 foi resolvido! 🎉

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Olá, Administrador!

Seu ticket #12 - Computador não liga foi marcado como RESOLVIDO.

Descrição: O computador da sala 10 não está ligando desde ontem
Prioridade: Alta
Resolvido em: 12/02/2026 16:30

┌──────────────┐
│  Ver Ticket  │  ← botão clicável
└──────────────┘

Obrigado por usar nosso sistema!

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
© 2026 Ticket Flow. Todos os direitos reservados.
```

---

### 💾 **Tabelas criadas**

- `jobs` - Fila de processamento (queue)
- `notifications` - Notificações enviadas
- `failed_jobs` - Jobs que falharam (retry)

---

### 🧪 **Testes do sistema de notificações**

```bash
php artisan test --filter TicketNotificationTest
```

**Testes implementados:**

```
✓ notificacao e enviada quando ticket e resolvido
✓ notificacao nao e enviada quando status nao e resolvido
```

**Cobertura:**
- ✅ Disparo de notificação ao resolver ticket
- ✅ Não disparo quando status não é RESOLVIDO
- ✅ Email adicionado à queue
- ✅ Notificação salva no banco

---

### 📊 **Ver notificações de um usuário (programaticamente)**

```php
// Todas notificações
$user->notifications;

// Apenas não lidas
$user->unreadNotifications;

// Marcar como lida
$notification->markAsRead();

// Deletar notificação
$notification->delete();
```

---

### 📌 **Tecnologias usadas**

- ✅ Laravel Queues (database driver)
- ✅ Laravel Notifications (mail + database channels)
- ✅ Jobs assíncronos com `ShouldQueue`
- ✅ Mailables personalizados
- ✅ Event/Listener pattern (opcional)

---

## 🏗️ Arquitetura

### 📐 Camadas da aplicação (Clean Architecture)

```
┌──────────────────────────────────────────────────────────┐
│              HTTP Layer (Controller)                     │
│  - Recebe requisições HTTP                               │
│  - Validação com Form Requests                           │
│  - Autorização com Policies                              │
│  - Retorna API Resources (JSON formatado)                │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│           Service Layer (Business Logic)                 │
│  - Lógica de negócio                                     │
│  - changeStatus() → preenche resolved_at                 │
│  - assignResponsible()                                   │
│  - Criação de audit logs                                 │
│  - Disparo de notificações                               │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│         Repository Layer (Data Access)                   │
│  - Acesso aos dados                                      │
│  - Filtros e queries complexas                           │
│  - Paginação                                             │
│  - Eager loading (N+1 problem)                           │
└──────────────────────────────────────────────────────────┘
                          ↓
┌──────────────────────────────────────────────────────────┐
│              Model Layer (Eloquent ORM)                  │
│  - Mapeamento objeto-relacional                          │
│  - Relacionamentos (BelongsTo, HasMany)                  │
│  - Scopes                                                │
│  - Accessors/Mutators                                    │
└──────────────────────────────────────────────────────────┘
```

---

### 📁 Estrutura de diretórios

```
app/
├── Enums/
│   ├── TicketStatus.php          # ABERTO, EM_ANDAMENTO, RESOLVIDO
│   ├── TicketPriority.php        # BAIXA, MEDIA, ALTA
│   └── UserRole.php              # USER, TECNICO, ADMIN
│
├── Http/
│   ├── Controllers/
│   │   ├── TicketController.php         # CRUD + endpoints especiais
│   │   └── Auth/LoginController.php     # Login API
│   │
│   ├── Requests/
│   │   ├── StoreTicketRequest.php       # Validações de criação
│   │   └── UpdateTicketRequest.php      # Validações de atualização
│   │
│   └── Resources/
│       ├── TicketResource.php           # Formatação JSON
│       └── AuditLogResource.php
│
├── Models/
│   ├── Ticket.php               # Model principal
│   ├── AuditLog.php             # Auditoria
│   └── User.php                 # Usuário + role
│
├── Notifications/
│   └── TicketResolvidoNotification.php  # Email + Database
│
├── Policies/
│   └── TicketPolicy.php         # Autorização (delete)
│
├── Repositories/
│   ├── Contracts/
│   │   └── TicketRepositoryInterface.php
│   └── TicketRepository.php     # Queries + filtros
│
└── Services/
    └── TicketService.php        # Lógica de negócio

database/
├── factories/
│   └── TicketFactory.php        # Fake data para testes
│
├── migrations/
│   ├── 2026_02_09_create_users_table.php
│   ├── 2026_02_09_create_tickets_table.php
│   ├── 2026_02_12_create_audit_logs_table.php
│   ├── 2026_02_12_create_notifications_table.php
│   └── 2026_02_12_create_jobs_table.php
│
└── seeders/
    ├── DatabaseSeeder.php
    └── UserSeeder.php           # 3 usuários (admin, user, tecnico)

tests/
├── Feature/
│   ├── TicketTest.php                   # CRUD tests
│   ├── TicketValidationTest.php         # Validation tests
│   └── TicketNotificationTest.php       # Notification tests
│
└── Unit/
    └── ExampleTest.php

routes/
├── api.php                      # Rotas da API
└── web.php                      # Rotas web (dashboard)
```

---

### 🗄️ Diagrama de Entidades (ER)

```
┌─────────────────────┐           ┌─────────────────────┐
│       USERS         │           │      TICKETS        │
├─────────────────────┤           ├─────────────────────┤
│ id (PK)             │◄─────┐    │ id (PK)             │
│ name                │      │    │ titulo              │
│ email (unique)      │      └────┤ solicitante_id (FK) │
│ password            │      ┌────┤ responsavel_id (FK) │
│ role (enum)         │◄─────┘    │ status (enum)       │
│ created_at          │           │ prioridade (enum)   │
│ updated_at          │           │ descricao (text)    │
└─────────────────────┘           │ resolved_at         │
                                  │ created_at          │
                                  │ updated_at          │
                                  │ deleted_at          │
                                  └─────────────────────┘
                                           │
                                           │ 1:N
                                           ▼
                                  ┌─────────────────────┐
                                  │    AUDIT_LOGS       │
                                  ├─────────────────────┤
                                  │ id (PK)             │
                                  │ auditable_type      │
                                  │ auditable_id (FK)   │
                                  │ user_id (FK)        │
                                  │ action              │
                                  │ description         │
                                  │ changes (JSON)      │
                                  │ created_at          │
                                  └─────────────────────┘

┌─────────────────────┐
│    NOTIFICATIONS    │
├─────────────────────┤
│ id (UUID, PK)       │
│ type                │
│ notifiable_type     │
│ notifiable_id (FK)  │
│ data (JSON)         │
│ read_at             │
│ created_at          │
│ updated_at          │
└─────────────────────┘

┌─────────────────────┐
│        JOBS         │
├─────────────────────┤
│ id (PK)             │
│ queue               │
│ payload (JSON)      │
│ attempts            │
│ reserved_at         │
│ available_at        │
│ created_at          │
└─────────────────────┘
```

---

### 🎨 Enums (Type-Safe)

#### TicketStatus

```php
enum TicketStatus: string
{
    case ABERTO = 'ABERTO';
    case EM_ANDAMENTO = 'EM_ANDAMENTO';
    case RESOLVIDO = 'RESOLVIDO';

    public function label(): string;
    public function color(): string;
}
```

| Valor | Label | Cor |
|-------|-------|-----|
| `ABERTO` | Aberto | blue |
| `EM_ANDAMENTO` | Em Andamento | yellow |
| `RESOLVIDO` | Resolvido | green |

---

#### TicketPriority

```php
enum TicketPriority: string
{
    case BAIXA = 'BAIXA';
    case MEDIA = 'MEDIA';
    case ALTA = 'ALTA';

    public function label(): string;
    public function color(): string;
    public function peso(): int;
}
```

| Valor | Label | Cor | Peso |
|-------|-------|-----|------|
| `BAIXA` | Baixa | green | 1 |
| `MEDIA` | Média | yellow | 2 |
| `ALTA` | Alta | red | 3 |

---

#### UserRole

```php
enum UserRole: string
{
    case USER = 'user';
    case TECNICO = 'tecnico';
    case ADMIN = 'admin';
}
```

---

## 📊 Estatísticas do Projeto

```
📁 Arquivos criados/modificados: 20+
📝 Linhas de código: +1176 / -793
🧪 Testes: 32 passando (85 assertions)
⏱️ Duração dos testes: ~2.5s
📧 Sistema de notificações: Implementado
⚡ Queue processing: Implementado
📚 Documentação: Completa
🎯 Cobertura de requisitos: 100% + BÔNUS
```

---

## 📝 Licença

Este projeto foi desenvolvido como teste técnico/desafio de backend Laravel.

**MIT License** - Sinta-se livre para usar como referência ou estudo.

---

## 👨‍💻 Desenvolvedor

Desenvolvido com ❤️ por **[@devfellsp](https://github.com/devfellsp)**

🚀 **GitHub:** [github.com/devfellsp/ticketflow-laravel](https://github.com/devfellsp/ticketflow-laravel)

---

## 🙏 Agradecimentos

- Laravel Framework
- Comunidade PHP
- Pest Testing Framework
- Todos que contribuíram com feedback

---

<p align="center">
  <strong>⭐ Se este projeto foi útil, considere dar uma estrela no GitHub! ⭐</strong>
</p>